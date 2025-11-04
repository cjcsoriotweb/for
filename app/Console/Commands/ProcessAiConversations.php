<?php

namespace App\Console\Commands;

use App\Models\AiConversation;
use App\Services\Ai\AiConversationResponder;
use Illuminate\Console\Command;
use Throwable;

class ProcessAiConversations extends Command
{
    protected $signature = 'ai:process-conversations {--limit=10 : Nombre max de conversations à traiter}';

    protected $description = 'Générer les réponses IA pour les conversations en attente.';

    public function handle(AiConversationResponder $responder): int
    {
        $limit = (int) max(1, $this->option('limit') ?? 10);

        $conversations = AiConversation::query()
            ->awaitingAi()
            ->with(['messages' => fn ($query) => $query->orderBy('id')])
            ->orderByDesc('last_message_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();

        if ($conversations->isEmpty()) {
            $this->info('Aucune conversation IA à traiter.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Traitement de %d conversation(s)...', $conversations->count()));

        $processed = 0;

        foreach ($conversations as $conversation) {
            $this->line(sprintf(
                'Conversation #%d (user:%d, trainer:%s)',
                $conversation->id,
                $conversation->user_id,
                $conversation->metadata['trainer'] ?? 'n/a'
            ));
            try {
                if ($responder->respond($conversation)) {
                    $processed++;
                    $this->components->info('→ Réponse envoyée');
                } else {
                    $this->components->warn('→ Aucune réponse générée');
                }
            } catch (Throwable $e) {
                report($e);
                echo $e;
                $this->components->error('→ Erreur: '.$e->getMessage());
            }
        }

        $this->info(sprintf('%d conversation(s) traitée(s).', $processed));

        return self::SUCCESS;
    }
}
