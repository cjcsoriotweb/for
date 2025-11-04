<?php

namespace App\Services\Ai;

use App\Models\AiTrainer;
use App\Models\IaChatMessage;
use Illuminate\Support\Facades\Log;

class IaChatResponder
{
    public function __construct(private readonly OllamaClient $ollama)
    {
    }

    public function respond(IaChatMessage $message): bool
    {
        if ($message->role !== IaChatMessage::ROLE_USER) {
            return false;
        }

        if ($message->status === IaChatMessage::STATUS_FAILED) {
            return false;
        }

        $trainer = AiTrainer::query()->active()->find($message->trainer_id);

        if (! $trainer) {
            $trainer = AiTrainer::query()->active()->where('slug', config('ai.default_trainer_slug'))->first();
        }

        if (! $trainer) {
            Log::warning('ia-chat:trainer-missing', [
                'message_id' => $message->id,
                'trainer_id' => $message->trainer_id,
            ]);

            return false;
        }

        $prompt = $this->buildPrompt($message, $trainer);
        if (empty($prompt)) {
            return false;
        }

        $message->forceFill(['status' => IaChatMessage::STATUS_SEEN])->save();

        try {
            $response = $this->ollama->chat($prompt, [
                'model' => $trainer->model ?: config('ai.default_model'),
                'temperature' => $trainer->temperature ?? (float) config('ai.temperature', 0.7),
            ]);

            $assistantText = trim($response['text'] ?? '');
            if ($assistantText === '') {
                return false;
            }

            IaChatMessage::query()->create([
                'user_id' => $message->user_id,
                'trainer_id' => $message->trainer_id,
                'parent_id' => $message->id,
                'role' => IaChatMessage::ROLE_ASSISTANT,
                'status' => IaChatMessage::STATUS_COMPLETED,
                'content' => $assistantText,
                'metadata' => [
                    'model' => $trainer->model,
                    'usage' => $response['usage'] ?? null,
                ],
            ]);

            return true;
        } catch (\Throwable $e) {
            $message->forceFill(['status' => IaChatMessage::STATUS_FAILED])->save();

            Log::error('ia-chat:responder-failed', [
                'message_id' => $message->id,
                'trainer_id' => $trainer->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function buildPrompt(IaChatMessage $message, AiTrainer $trainer): array
    {
        $messages = [];
        $systemPrompt = trim((string) $trainer->systemPrompt());

        if ($systemPrompt !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        $messages[] = [
            'role' => IaChatMessage::ROLE_USER,
            'content' => (string) $message->content,
        ];

        return $messages;
    }
}
