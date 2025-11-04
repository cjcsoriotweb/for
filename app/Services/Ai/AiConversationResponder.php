<?php

namespace App\Services\Ai;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AiConversationResponder
{
    public function __construct(private readonly OllamaClient $ollama)
    {
    }

    public function respond(AiConversation $conversation): bool
    {
        $trainerSlug = $conversation->metadata['trainer'] ?? config('ai.default_trainer_slug');

        /** @var AiTrainer|null $trainer */
        $trainer = AiTrainer::query()->active()->where('slug', $trainerSlug)->first();
        if (! $trainer) {
            Log::warning('ai-responder:trainer-missing', [
                'conversation_id' => $conversation->id,
                'trainer' => $trainerSlug,
            ]);

            return false;
        }

        $messages = $this->buildPrompt($conversation->messages ?? collect(), $trainer);
        if (empty($messages)) {
            return false;
        }

        try {
            $response = $this->ollama->chat($messages, [
                'model' => $trainer->model ?: config('ai.default_model'),
                'temperature' => $trainer->temperature ?? (float) config('ai.temperature', 0.7),
            ]);

            $assistantText = trim($response['text'] ?? '');
            if ($assistantText === '') {
                return false;
            }

            AiConversationMessage::query()->create([
                'conversation_id' => $conversation->id,
                'role' => AiConversationMessage::ROLE_ASSISTANT,
                'content' => $assistantText,
                'metadata' => [
                    'model' => $trainer->model,
                    'usage' => $response['usage'] ?? null,
                ],
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('ai-responder:failed', [
                'conversation_id' => $conversation->id,
                'trainer' => $trainer->slug,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @param  Collection<int, AiConversationMessage>  $history
     */
    private function buildPrompt(Collection $history, AiTrainer $trainer): array
    {
        $messages = [];
        $systemPrompt = trim((string) $trainer->systemPrompt());

        if ($systemPrompt !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        $latestUserMessage = $history
            ->sortByDesc('id')
            ->firstWhere('role', AiConversationMessage::ROLE_USER);

        if ($latestUserMessage) {
            $messages[] = [
                'role' => AiConversationMessage::ROLE_USER,
                'content' => (string) $latestUserMessage->content,
            ];
        }

        return $messages;
    }
}
