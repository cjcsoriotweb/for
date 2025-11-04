<?php

namespace App\Console\Commands;

use App\Models\IaChatMessage;
use App\Services\Ai\IaChatResponder;
use Illuminate\Console\Command;
use Throwable;

class ProcessAiConversations extends Command
{
    protected $signature = 'ai:process-conversations {--limit=10 : Nombre max de messages IA a traiter}';

    protected $description = 'Generer les reponses IA pour les messages en attente.';

    public function handle(IaChatResponder $responder): int
    {
        $limit = (int) max(1, $this->option('limit') ?? 10);

        $pendingMessages = IaChatMessage::query()
            ->where('role', IaChatMessage::ROLE_USER)
            ->where('status', IaChatMessage::STATUS_PENDING)
            ->whereDoesntHave('children', function ($query) {
                $query->where('role', IaChatMessage::ROLE_ASSISTANT);
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($pendingMessages->isEmpty()) {
            $this->info('Aucun message utilisateur a traiter.');

            return self::SUCCESS;
        }

        $this->info(sprintf('Traitement de %d message(s)...', $pendingMessages->count()));

        $processed = 0;

        foreach ($pendingMessages as $message) {
            $this->line(sprintf(
                'Message #%d (user:%d, trainer:%d)',
                $message->id,
                $message->user_id,
                $message->trainer_id
            ));

            try {
                if ($responder->respond($message)) {
                    $processed++;
                    $this->components->info('-> Reponse envoyee');
                } else {
                    $this->components->warn('-> Aucune reponse generee');
                }
            } catch (Throwable $e) {
                report($e);
                $this->components->error('-> Erreur: ' . $e->getMessage());
            }
        }

        $this->info(sprintf('%d message(s) traites.', $processed));

        return self::SUCCESS;
    }
}
