<?php

namespace App\Jobs;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Services\Ai\OllamaClient;
use App\Services\Ai\ToolExecutor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessAiResponse implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $trainerSlug,
        public string $userMessage,
        public int $conversationId,
        public ?int $userId = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(OllamaClient $ollamaClient, ToolExecutor $toolExecutor): void
    {
        try {
            // Charger le trainer
            $trainer = AiTrainer::where('slug', $this->trainerSlug)->first();
            if (! $trainer) {
                return;
            }

            // Charger la conversation
            $conversation = AiConversation::find($this->conversationId);
            if (! $conversation) {
                return;
            }

            // Préparer les messages pour l'IA
            $messages = $this->prepareMessages($conversation, $trainer);

            // Appeler l'IA
            $result = $ollamaClient->chat($messages);
            $reply = $this->sanitizeUtf8String((string) ($result['text'] ?? 'Erreur: pas de réponse'));

            // Sauvegarder la réponse
            DB::transaction(function () use ($conversation, $reply) {
                AiConversationMessage::create([
                    'conversation_id' => $conversation->id,
                    'role' => AiConversationMessage::ROLE_ASSISTANT,
                    'content' => $reply,
                ]);

                $conversation->update([
                    'last_message_at' => now(),
                ]);
            });

            // Dispatch un événement pour mettre à jour l'interface
            // Note: Les événements Livewire ne fonctionnent pas directement depuis les jobs
            // Nous utiliserons une approche différente

        } catch (Throwable $exception) {
            report($exception);

            // En cas d'erreur, sauvegarder une réponse d'erreur
            try {
                DB::transaction(function () use ($conversation, $exception) {
                    $errorReply = 'Réponse IA simulée à : '.$this->userMessage.' (Erreur: '.$exception->getMessage().')';

                    AiConversationMessage::create([
                        'conversation_id' => $conversation->id,
                        'role' => AiConversationMessage::ROLE_ASSISTANT,
                        'content' => $errorReply,
                    ]);

                    $conversation->update([
                        'last_message_at' => now(),
                    ]);
                });
            } catch (Throwable $e) {
                report($e);
            }
        }
    }

    protected function prepareMessages(AiConversation $conversation, AiTrainer $trainer): array
    {
        $messages = [];

        $systemPrompt = $this->sanitizeUtf8String((string) $trainer->systemPrompt());
        if ($systemPrompt !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        if ($trainer->use_tools) {
            $messages[] = [
                'role' => 'system',
                'content' => $this->sanitizeUtf8String(ToolExecutor::getToolsPrompt()),
            ];
        }

        $historyLimit = (int) config('ai.history_limit', 30);
        $history = $conversation->messages()
            ->latest('id')
            ->limit($historyLimit)
            ->get()
            ->sortBy('id')
            ->values();

        foreach ($history as $msg) {
            if ($msg->role === AiConversationMessage::ROLE_SYSTEM) {
                continue;
            }

            $messages[] = [
                'role' => $msg->role,
                'content' => $this->sanitizeUtf8String((string) $msg->content),
            ];
        }

        return $messages;
    }

    protected function sanitizeUtf8String(string $input): string
    {
        if (mb_check_encoding($input, 'UTF-8')) {
            $s = $input;
        } else {
            $encoding = mb_detect_encoding($input, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'CP1252'], true) ?: 'ISO-8859-1';
            $s = @mb_convert_encoding($input, 'UTF-8', $encoding);
            if ($s === false) {
                $s = @iconv('UTF-8', 'UTF-8//IGNORE', $input) ?: '';
            }
        }

        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s) ?? $s;
        $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s) ?: '';

        return $s;
    }
}
