<?php

namespace App\Services\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use App\Services\Ai\ChatCompletionClient;
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

        return $this->resolveDefaultSiteTrainer()
            ?? $query->orderByDesc('is_default')->orderBy('name')->first();
    }

    public function getOrCreateConversation(AiTrainer $trainer, User $user, ?Formation $formation = null, ?Team $team = null): AiConversation
    {
        return DB::transaction(function () use ($trainer, $user, $formation, $team) {
            $conversation = AiConversation::query()->firstOrCreate(
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

            $this->syncUserContext($conversation, $user);

            return $conversation->fresh();
        });
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

    public function startNewConversation(AiTrainer $trainer, User $user, ?Formation $formation = null, ?Team $team = null): AiConversation
    {
        return DB::transaction(function () use ($trainer, $user, $formation, $team) {
            $query = AiConversation::query()
                ->where('ai_trainer_id', $trainer->id)
                ->where('user_id', $user->id);

            $query = $formation
                ? $query->where('formation_id', $formation->id)
                : $query->whereNull('formation_id');

            $query = $team
                ? $query->where('team_id', $team->id)
                : $query->whereNull('team_id');

            /** @var Collection<int, AiConversation> $existing */
            $existing = $query->get();

            foreach ($existing as $conversation) {
                if ($conversation->status === AiConversation::STATUS_ACTIVE) {
                    $conversation->archive();
                }
            }

            $conversation = AiConversation::query()->create([
                'ai_trainer_id' => $trainer->id,
                'user_id' => $user->id,
                'formation_id' => $formation?->id,
                'team_id' => $team?->id,
                'status' => AiConversation::STATUS_ACTIVE,
                'metadata' => $existing->first()?->metadata ?? [],
                'last_message_at' => now(),
            ]);

            $this->syncUserContext($conversation, $user);

            return $conversation->fresh();
        });
    }

    public function syncUserContext(AiConversation $conversation, User $user): void
    {
        $context = trim((string) $user->getIaContext());
        $metadata = $conversation->metadata ?? [];
        $hash = $context === '' ? null : sha1($context);
        $currentHash = Arr::get($metadata, 'user_context.hash');

        if ($hash === null) {
            if ($currentHash !== null) {
                Arr::forget($metadata, 'user_context');
                $conversation->forceFill(['metadata' => $metadata])->save();
            }

            return;
        }

        if ($currentHash === $hash) {
            return;
        }

        $metadata['user_context'] = [
            'hash' => $hash,
            'content' => $context,
            'updated_at' => now()->toIso8601String(),
        ];

        $conversation->forceFill(['metadata' => $metadata])->save();
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

        $client = ChatCompletionClient::fromConfig($providerConfig);

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

        $userContext = Arr::get($conversation->metadata, 'user_context.content');

        if (is_string($userContext) && trim($userContext) !== '') {
            $messages[] = [
                'role' => AiConversationMessage::ROLE_SYSTEM,
                'content' => $userContext,
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
            'model' => $trainer->model ?: Arr::get($providerConfig, 'default_model', 'llama3'),
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

    private function resolveDefaultSiteTrainer(): ?AiTrainer
    {
        $config = config('ai.default_site_trainer');
        $slug = null;

        if (is_array($config)) {
            $slug = $config['slug'] ?? null;
        }

        if (! is_string($slug) || trim($slug) === '') {
            $slug = config('ai.default_trainer_slug');
        }

        $slug = is_string($slug) ? trim($slug) : '';

        if ($slug === '') {
            return null;
        }

        $trainer = AiTrainer::query()->where('slug', $slug)->first();

        if (! $trainer && is_array($config)) {
            $provider = $config['provider'] ?? config('ai.default_driver', 'ollama');
            $model = $config['model'] ?? config("ai.providers.$provider.default_model", 'llama3');

            $settings = isset($config['settings']) && is_array($config['settings'])
                ? $config['settings']
                : [];

            if (! array_key_exists('temperature', $settings)) {
                $settings['temperature'] = (float) config("ai.providers.$provider.temperature", 0.7);
            } else {
                $settings['temperature'] = (float) $settings['temperature'];
            }

            $trainer = AiTrainer::query()->firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $config['name'] ?? 'Assistant IA',
                    'provider' => $provider,
                    'model' => $model,
                    'description' => $config['description'] ?? null,
                    'prompt' => $config['prompt'] ?? null,
                    'avatar_path' => $config['avatar_path'] ?? null,
                    'is_default' => true,
                    'is_active' => true,
                    'settings' => $settings,
                ]
            );
        }

        if (! $trainer) {
            return null;
        }

        $updates = [];

        if (! $trainer->is_active) {
            $updates['is_active'] = true;
        }

        if (! $trainer->is_default) {
            $updates['is_default'] = true;
        }

        if ($updates !== []) {
            $trainer->forceFill($updates)->save();
            $trainer->refresh();
        }

        return $trainer;
    }
}
