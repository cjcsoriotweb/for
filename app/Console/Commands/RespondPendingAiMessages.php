<?php

namespace App\Console\Commands;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RespondPendingAiMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assistants:respond-pending
                            {--message= : Message a envoyer (placeholders: user_name, conversation_id, message_id, message_created_at)}
                            {--min-age=5 : Delai minimal (en minutes) avant de traiter un message}
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
        $messageTemplate = (string) ($this->option('message') ?: config('ai.fallback_message', $this->defaultMessage()));
        $minAgeMinutes = (int) max(0, $this->option('min-age') ?? 5);
        $limit = (int) max(1, $this->option('limit') ?? 50);
        $dryRun = (bool) $this->option('dry-run');

        $fallbackSender = $this->resolveFallbackSender();
        if (! $fallbackSender) {
            $this->error("Impossible de determiner l'utilisateur expediant les messages fallback. Configurez AI_FALLBACK_SENDER_USER_ID ou creez un superadmin.");

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
            DB::transaction(function () use ($userMessage, $messageTemplate, $fallbackSender, $dryRun, &$processed): void {
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

                $metadata = $message->metadata ?? [];
                if (Arr::get($metadata, 'fallback.sent')) {
                    return;
                }

                $content = $this->renderMessage($message, $messageTemplate);

                if ($dryRun) {
                    $this->line(sprintf(
                        '[DRY RUN] Conversation user:%d-ai:%d (message %d) -> "%s"',
                        $user->id,
                        $aiId,
                        $message->id,
                        $content
                    ));
                } else {
                    $assistantMessage = Chat::query()->create([
                        'sender_user_id' => $fallbackSender->id,
                        'sender_ia_id' => $aiId,
                        'receiver_user_id' => $user->id,
                        'content' => $content,
                        'metadata' => [
                            'ai_id' => $aiId,
                            'role' => 'assistant',
                            'fallback' => [
                                'source' => 'assistants:respond-pending',
                                'original_message_id' => $message->id,
                            ],
                        ],
                    ]);

                    $metadata['ai_id'] = $aiId;
                    $metadata['fallback'] = [
                        'sent' => true,
                        'message_id' => $assistantMessage->id,
                        'sent_at' => now()->toIso8601String(),
                        'command' => self::class,
                    ];

                    $message->forceFill(['metadata' => $metadata])->save();

                    $this->info(sprintf(
                        'Conversation user:%d-ai:%d : reponse fallback envoyee (message %d -> %d)',
                        $user->id,
                        $aiId,
                        $message->id,
                        $assistantMessage->id
                    ));
                }

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
     * Determine l'utilisateur a utiliser comme expéditeur fallback.
     */
    private function resolveFallbackSender(): ?User
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
            ->whereNull('metadata->fallback->sent')
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
     * Genere le message envoye a l'utilisateur.
     */
    private function renderMessage(Chat $message, string $template): string
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
     * Message par defaut utilise quand aucune option n'est fournie.
     */
    private function defaultMessage(): string
    {
        return "Bonjour {user_name}, notre assistant IA est indisponible pour le moment. Nous avons bien recu votre message #{message_id} et un membre de l'equipe vous repondra prochainement.";
    }
}
