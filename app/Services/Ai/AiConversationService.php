<?php

namespace App\Services\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AiConversationService
{
    /**
     * Resolve the AI trainer to use for a given formation.
     */
    public function resolveTrainer(?Formation $formation = null, ?int $trainerId = null): ?AiTrainer
    {
        $query = AiTrainer::query()->active();

        if ($trainerId) {
            $trainer = $query->whereKey($trainerId)->first();
            if ($trainer) {
                return $trainer;
            }
        }

        if ($formation) {
            $formation->loadMissing('aiTrainers');

            /** @var Collection<int, AiTrainer> $linkedTrainers */
            $linkedTrainers = $formation->aiTrainers->filter->is_active;

            $primary = $linkedTrainers->first(fn (AiTrainer $trainer) => (bool) $trainer->pivot?->is_primary);

            if ($primary) {
                return $primary;
            }

            if ($linkedTrainers->isNotEmpty()) {
                return $linkedTrainers->first();
            }
        }

        $defaultSlug = config('ai.default_trainer_slug');

        $trainer = $query
            ->where('slug', $defaultSlug)
            ->first()
            ?? $query->orderByDesc('is_default')->orderBy('name')->first();

        return $trainer;
    }

    public function getOrCreateConversation(AiTrainer $trainer, User $user, ?Formation $formation = null, ?Team $team = null): AiConversation
    {
        return AiConversation::query()->firstOrCreate(
            [
                'ai_trainer_id' => $trainer->id,
                'user_id' => $user->id,
                'formation_id' => $formation?->id,
            ],
            [
                'team_id' => $team?->id,
                'status' => AiConversation::STATUS_ACTIVE,
                'last_message_at' => now(),
            ]
        );
    }

    public function appendMessage(
        AiConversation $conversation,
        string $role,
        string $content,
        ?User $author = null,
        array $context = []
    ): AiConversationMessage {
        return DB::transaction(function () use ($conversation, $role, $content, $author, $context) {
            $message = $conversation->messages()->create([
                'role' => $role,
                'content' => $content,
                'user_id' => $author?->id,
                'context_label' => Str::limit($context['label'] ?? '', 120, ''),
                'context_path' => Str::limit($context['path'] ?? '', 255, ''),
                'metadata' => Arr::except($context, ['label', 'path']),
            ]);

            $conversation->forceFill([
                'last_message_at' => now(),
            ])->save();

            return $message;
        });
    }

    /**
     * Generate an assistant reply using the configured provider.
     *
     * @param  array<int, array<string, string>>  $pendingMessages
     */
    public function generateAssistantReply(AiConversation $conversation, AiTrainer $trainer, array $pendingMessages = []): AiConversationMessage
    {
        $providerConfig = config('ai.providers.'.$trainer->provider);

        if (! $providerConfig) {
            throw new RuntimeException(sprintf('Provider [%s] is not configured.', $trainer->provider));
        }

        $client = OpenAiClient::fromConfig($providerConfig);

        $historyLimit = max(config('ai.conversation.history_limit', 30), 1);

        $history = $conversation->messages()
            ->latest('id')
            ->limit($historyLimit)
            ->get()
            ->sortBy('created_at')
            ->values();

        $messages = [];

        if ($trainer->prompt) {
            $messages[] = [
                'role' => AiConversationMessage::ROLE_SYSTEM,
                'content' => $trainer->prompt,
            ];
        }

        /** @var AiConversationMessage $message */
        foreach ($history as $message) {
            $messages[] = [
                'role' => $message->role,
                'content' => $message->content,
            ];
        }

        foreach ($pendingMessages as $pending) {
            $messages[] = $pending;
        }

        $payload = [
            'model' => $trainer->model ?: Arr::get($providerConfig, 'default_model', 'gpt-4o-mini'),
            'messages' => $messages,
        ];

        $temperature = Arr::get($trainer->settings, 'temperature', Arr::get($providerConfig, 'temperature'));
        if ($temperature !== null) {
            $payload['temperature'] = (float) $temperature;
        }

        $response = $client->chat($payload);

        $choice = Arr::get($response, 'choices.0');
        $content = Arr::get($choice, 'message.content');

        if (! is_string($content) || trim($content) === '') {
            throw new RuntimeException('La reponse du modele est vide.');
        }

        $usage = Arr::get($response, 'usage', []);

        return $conversation->messages()->create([
            'role' => AiConversationMessage::ROLE_ASSISTANT,
            'content' => $content,
            'prompt_tokens' => Arr::get($usage, 'prompt_tokens'),
            'completion_tokens' => Arr::get($usage, 'completion_tokens'),
            'metadata' => [
                'provider' => $trainer->provider,
                'model' => $payload['model'],
            ],
        ]);
    }
}
