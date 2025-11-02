<?php

namespace App\Console\Commands;

use App\Models\AiTrainer;
use App\Models\Chat;
use App\Models\User;
use App\Notifications\ChatAiReplied;
use App\Services\Ai\OllamaClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class RespondPendingAiMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assistants:respond-pending
                            {--message= : Message de secours a envoyer en cas d\'indisponibilite IA (placeholders: user_name, conversation_id, message_id, message_created_at)}
                            {--min-age= : Delai minimal (en minutes) avant de traiter un message}
                            {--limit=50 : Nombre maximum de conversations a traiter par execution}
                            {--dry-run : Afficher les actions sans enregistrer de reponse}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repond automatiquement aux conversations IA restees sans reponse.';

    /**
     * Detailed help text for the command.
     */
    protected $help = "Placeholders disponibles dans l'option --message : {user_name}, {conversation_id}, {message_id}, {message_created_at}.";

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var OllamaClient $ollama */
        $ollama = app(OllamaClient::class);

        $fallbackTemplate = (string) ($this->option('message') ?: config('ai.fallback_message', $this->defaultFallbackMessage()));
        $minAgeOption = $this->option('min-age');
        $minAgeMinutes = $minAgeOption === null || $minAgeOption === ''
            ? (int) config('ai.fallback_min_age', 0)
            : (int) $minAgeOption;
        $minAgeMinutes = max(0, $minAgeMinutes);
        $limit = (int) max(1, $this->option('limit') ?? 50);
        $dryRun = (bool) $this->option('dry-run');

        $assistantUser = $this->resolveAssistantSender();
        if (! $assistantUser) {
            $this->error("Impossible de determiner l'utilisateur associe aux reponses IA. Configurez AI_FALLBACK_SENDER_USER_ID ou creez un superadmin.");

            return self::FAILURE;
        }

        $pendingMessages = $this->fetchPendingMessages($minAgeMinutes, $limit);

        if ($pendingMessages->isEmpty()) {
            $this->info('Aucun message en attente necessitant une reponse.');

            return self::SUCCESS;
        }

        $this->info(sprintf('%d message(s) en attente seront traites.', $pendingMessages->count()));

        $processed = 0;

        foreach ($pendingMessages as $userMessage) {
            DB::transaction(function () use ($userMessage, $assistantUser, $ollama, $fallbackTemplate, $dryRun, &$processed): void {
                /** @var Chat|null $message */
                $message = Chat::query()->with(['sender', 'receiverIa'])->find($userMessage->id);
                if (! $message) {
                    return;
                }

                $aiId = (int) $message->receiver_ia_id;
                $user = $message->sender;

                if (! $aiId || ! $user) {
                    return;
                }

                $meta = $message->metadata ?? [];
                if (Arr::get($meta, 'ai.reply_message_id')) {
                    return;
                }

                $trainer = AiTrainer::query()->active()->find($aiId);
                if (! $trainer) {
                    $this->sendFallbackResponse($message, $assistantUser, $aiId, $fallbackTemplate, 'trainer_unavailable', $dryRun);
                    $processed++;

                    return;
                }

                if ($dryRun) {
                    $preview = Str::limit($message->content ?? '', 120);
                    $this->line(sprintf(
                        '[DRY RUN] user:%d -> ai:%d (%s) // Dernier message #%d: "%s"',
                        $user->id,
                        $trainer->id,
                        $trainer->name,
                        $message->id,
                        $preview
                    ));
                    $processed++;

                    return;
                }

                [$assistantContent, $responseMeta] = $this->generateAiResponse($ollama, $message, $trainer, $user);

                if ($assistantContent === null || trim($assistantContent) === '') {
                    $reason = $responseMeta['error'] ?? 'unknown_error';
                    $this->warn(sprintf(
                        'Generation IA impossible pour message #%d (user:%d -> ai:%d). Raison: %s',
                        $message->id,
                        $user->id,
                        $trainer->id,
                        $reason
                    ));

                    $this->sendFallbackResponse($message, $assistantUser, $trainer->id, $fallbackTemplate, $reason, false);
                    $processed++;

                    return;
                }

                $assistantMessage = $this->createAssistantMessage(
                    sourceMessage: $message,
                    assistantUser: $assistantUser,
                    trainerId: $trainer->id,
                    content: $assistantContent,
                    metadata: [
                        'role' => 'assistant',
                        'ai_id' => $trainer->id,
                        'ai' => [
                            'trainer_id' => $trainer->id,
                            'model' => $responseMeta['model'] ?? null,
                            'temperature' => $responseMeta['temperature'] ?? null,
                            'usage' => $responseMeta['usage'] ?? null,
                            'fallback_used' => false,
                            'fallback_reason' => null,
                            'source' => 'ollama',
                            'original_message_id' => $message->id,
                        ],
                    ],
                );

                $this->markMessageReplied(
                    message: $message,
                    trainerId: $trainer->id,
                    replyMessage: $assistantMessage,
                    responseMeta: $responseMeta,
                    isFallback: false
                );

                $this->notifyUserOfReply($assistantMessage, $message);

                $this->info(sprintf(
                    'Conversation user:%d-ai:%d : reponse IA envoyee (message %d -> %d)',
                    $user->id,
                    $trainer->id,
                    $message->id,
                    $assistantMessage->id
                ));

                $processed++;
            });
        }

        if ($dryRun) {
            $this->info(sprintf('Simulation terminee : %d message(s) auraient ete traites.', $processed));
        } else {
            $this->info(sprintf('%d message(s) traites.', $processed));
        }

        return self::SUCCESS;
    }

    /**
     * Determine l'utilisateur a utiliser comme expéditeur des messages IA.
     */
    private function resolveAssistantSender(): ?User
    {
        $configuredId = config('ai.fallback_sender_user_id');
        if ($configuredId) {
            return User::query()->find($configuredId);
        }

        return User::query()
            ->where('superadmin', true)
            ->orderBy('id')
            ->first();
    }

    /**
     * Recupere les messages utilisateur (vers IA) qui attendent une reponse.
     */
    private function fetchPendingMessages(int $minAgeMinutes, int $limit): Collection
    {
        $threshold = now()->subMinutes($minAgeMinutes);

        $candidates = Chat::query()
            ->with(['sender'])
            ->whereNotNull('receiver_ia_id')
            ->whereNull('sender_ia_id')
            ->whereNull('metadata->ai->reply_message_id')
            ->where('created_at', '<=', $threshold)
            ->orderBy('created_at')
            ->limit($limit * 5)
            ->get();

        return $candidates
            ->filter(fn (Chat $message) => $this->isLatestUserMessage($message))
            ->take($limit)
            ->values();
    }

    /**
     * Verifie que le message est le dernier echange dans la conversation user/IA.
     */
    private function isLatestUserMessage(Chat $message): bool
    {
        $userId = (int) $message->sender_user_id;
        $aiId = (int) $message->receiver_ia_id;

        if (! $userId || ! $aiId) {
            return false;
        }

        $latest = Chat::query()
            ->where(function ($query) use ($userId, $aiId) {
                $query->where('sender_user_id', $userId)
                    ->where('receiver_ia_id', $aiId);
            })
            ->orWhere(function ($query) use ($userId, $aiId) {
                $query->where('receiver_user_id', $userId)
                    ->where('sender_ia_id', $aiId);
            })
            ->orderByDesc('id')
            ->first();

        return $latest
            && (int) $latest->id === (int) $message->id
            && (int) $latest->sender_user_id === $userId;
    }

    /**
     * Genere une reponse IA a partir de l'historique de conversation.
     *
     * @return array{0: ?string, 1: array<string, mixed>}
     */
    private function generateAiResponse(OllamaClient $ollama, Chat $message, AiTrainer $trainer, User $user): array
    {
        $options = [
            'model' => $trainer->model ?: config('ai.default_model'),
            'temperature' => $trainer->temperature ?? (float) config('ai.temperature', 0.7),
        ];

        $history = $this->buildConversationMessages($message, $trainer, $user);

        if (empty($history)) {
            return [null, [
                'error' => 'conversation_history_empty',
                'model' => $options['model'],
                'temperature' => $options['temperature'],
            ]];
        }

        try {
            $response = $ollama->chat($history, $options);
            $text = trim($response['text'] ?? '');

            if ($text === '') {
                return [null, [
                    'error' => 'empty_response',
                    'model' => $options['model'],
                    'temperature' => $options['temperature'],
                    'usage' => $response['usage'] ?? null,
                ]];
            }

            return [$text, [
                'model' => $options['model'],
                'temperature' => $options['temperature'],
                'usage' => $response['usage'] ?? null,
            ]];
        } catch (Throwable $e) {
            Log::error('assistants:respond-pending - generation IA echouee', [
                'message_id' => $message->id,
                'trainer_id' => $trainer->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [null, [
                'error' => $e->getMessage(),
                'model' => $options['model'],
                'temperature' => $options['temperature'],
            ]];
        }
    }

    /**
     * Construit le contexte de conversation pour Ollama.
     *
     * @return array<int, array{role:string, content:string}>
     */
    private function buildConversationMessages(Chat $message, AiTrainer $trainer, User $user): array
    {
        $historyLimit = max(1, (int) config('ai.history_limit', 30));
        $userId = (int) $message->sender_user_id;
        $aiId = (int) $message->receiver_ia_id;

        $conversation = Chat::query()
            ->where(function ($query) use ($userId, $aiId) {
                $query->where('sender_user_id', $userId)
                    ->where('receiver_ia_id', $aiId);
            })
            ->orWhere(function ($query) use ($userId, $aiId) {
                $query->where('receiver_user_id', $userId)
                    ->where('sender_ia_id', $aiId);
            })
            ->orderByDesc('id')
            ->limit($historyLimit)
            ->get()
            ->sortBy('id')
            ->values();

        $messages = [];

        $systemPrompt = trim((string) $trainer->systemPrompt());
        if ($systemPrompt !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        if (method_exists($user, 'getIaContext')) {
            $context = trim((string) $user->getIaContext());
            if ($context !== '') {
                $messages[] = [
                    'role' => 'system',
                    'content' => "Contexte utilisateur :\n" . $context,
                ];
            }
        }

        foreach ($conversation as $chatMessage) {
            if ($chatMessage->sender_ia_id) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => (string) $chatMessage->content,
                ];
            } else {
                $messages[] = [
                    'role' => 'user',
                    'content' => (string) $chatMessage->content,
                ];
            }
        }

        return $messages;
    }

    /**
     * Cree et enregistre un message assistant.
     */
    private function createAssistantMessage(Chat $sourceMessage, User $assistantUser, int $trainerId, string $content, array $metadata): Chat
    {
        return Chat::query()->create([
            'sender_user_id' => $assistantUser->id,
            'sender_ia_id' => $trainerId,
            'receiver_user_id' => $sourceMessage->sender_user_id,
            'content' => $content,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Met a jour le metadata du message utilisateur source.
     */
    private function markMessageReplied(Chat $message, int $trainerId, Chat $replyMessage, array $responseMeta, bool $isFallback): void
    {
        $metadata = $message->metadata ?? [];

        $metadata['ai_id'] = $trainerId;
        $metadata['ai'] = array_merge($metadata['ai'] ?? [], [
            'reply_message_id' => $replyMessage->id,
            'sent_at' => now()->toIso8601String(),
            'trainer_id' => $trainerId,
            'model' => $responseMeta['model'] ?? null,
            'temperature' => $responseMeta['temperature'] ?? null,
            'usage' => $responseMeta['usage'] ?? null,
            'fallback_used' => $isFallback,
            'fallback_reason' => $isFallback ? ($responseMeta['error'] ?? 'fallback') : null,
            'source' => $isFallback ? 'fallback' : 'ollama',
        ]);

        $message->forceFill(['metadata' => $metadata])->save();
    }

    /**
     * Envoie une reponse de secours.
     */
    private function sendFallbackResponse(Chat $message, User $assistantUser, int $trainerId, string $template, string $reason, bool $dryRun): void
    {
        $content = $this->renderFallbackMessage($message, $template);

        if ($dryRun) {
            $this->line(sprintf(
                '[DRY RUN | FALLBACK] user:%d -> ai:%d (message %d) // raison: %s',
                $message->sender_user_id,
                $trainerId,
                $message->id,
                $reason
            ));

            return;
        }

        $trainer = AiTrainer::query()->find($trainerId);

        $assistantMessage = $this->createAssistantMessage(
            sourceMessage: $message,
            assistantUser: $assistantUser,
            trainerId: $trainerId,
            content: $content,
            metadata: [
                'role' => 'assistant',
                'ai_id' => $trainerId,
                'ai' => [
                    'trainer_id' => $trainerId,
                    'model' => $trainer?->model,
                    'temperature' => $trainer?->temperature,
                    'usage' => null,
                    'fallback_used' => true,
                    'fallback_reason' => $reason,
                    'source' => 'fallback',
                    'original_message_id' => $message->id,
                ],
            ],
        );

        $this->markMessageReplied(
            message: $message,
            trainerId: $trainerId,
            replyMessage: $assistantMessage,
            responseMeta: [
                'model' => $trainer?->model,
                'temperature' => $trainer?->temperature,
                'error' => $reason,
            ],
            isFallback: true
        );

        $this->notifyUserOfReply($assistantMessage, $message);

        $this->info(sprintf(
            'Conversation user:%d-ai:%d : reponse fallback envoyee (message %d -> %d) [raison: %s]',
            $message->sender_user_id,
            $trainerId,
            $message->id,
            $assistantMessage->id,
            $reason
        ));
    }

    /**
     * Genere le message de secours pour l'utilisateur.
     */
    private function renderFallbackMessage(Chat $message, string $template): string
    {
        $user = $message->sender;
        $userName = $user?->name ?? 'utilisateur';
        $aiId = (int) $message->receiver_ia_id;

        $placeholders = [
            '{user_name}' => $userName,
            '{conversation_id}' => sprintf('user:%d-ai:%d', $message->sender_user_id, $aiId),
            '{message_id}' => (string) $message->id,
            '{message_created_at}' => optional($message->created_at)->toDateTimeString(),
        ];

        return strtr($template, $placeholders);
    }

    /**
     * Message fallback par defaut utilise quand aucune option n'est fournie.
     */
    private function defaultFallbackMessage(): string
    {
        return "Bonjour {user_name}, notre assistant IA est indisponible pour le moment. Nous avons bien recu votre message #{message_id} et un membre de l'equipe vous repondra prochainement.";
    }

    /**
     * Envoie une notification a l'utilisateur lorsque l'assistant repond.
     */
    private function notifyUserOfReply(Chat $assistantMessage, Chat $sourceMessage): void
    {
        $recipient = $sourceMessage->sender;
        if (! $recipient || (int) $recipient->id === (int) $assistantMessage->sender_user_id) {
            return;
        }

        try {
            $assistantMessage->loadMissing('senderIa');
            $recipient->notifyNow(new ChatAiReplied($assistantMessage, $sourceMessage));
        } catch (Throwable $e) {
            Log::warning('assistants:respond-pending - notification IA impossible', [
                'assistant_message_id' => $assistantMessage->id,
                'source_message_id' => $sourceMessage->id,
                'recipient_id' => $recipient->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
