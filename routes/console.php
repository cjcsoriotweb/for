<?php

use App\Models\AiConversationMessage;
use App\Services\Ai\AiConversationResponder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;

Artisan::command('ia:delete_message', function () {
    

})->purpose('Retirer les messages plus vieux entre ia et user.');


Artisan::command('ai:test', function () {
    $baseUrl = rtrim(env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'), '/');
    $payload = [
        'model' => env('OLLAMA_DEFAULT_MODEL', 'llama3.2:3b'),
        'prompt' => 'Bonjour, comment vas-tu ?',
    ];

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->withOptions(['stream' => true])
      ->post($baseUrl . '/api/generate', $payload);

    if ($response->failed()) {
        $this->error('La requête a échoué : ' . $response->body());
        return;
    }

    $stream = $response->toPsrResponse()->getBody();
    while (! $stream->eof()) {
        $chunk = $stream->read(1024);
        if ($chunk !== '') {
            $this->output->write($chunk);
        }
    }

    $this->newLine();
})->purpose('Tester la génération via l’API Ollama.');

Artisan::command('ai:process-conversations {--limit=10 : Nombre max de conversations à traiter}', function (AiConversationResponder $responder) {
    $limit = (int) max(1, $this->option('limit') ?? 10);

    $pendingMessages = AiConversationMessage::query()
        ->with(['conversation.messages' => function ($query) {
            $query->orderBy('id');
        }])
        ->where('role', AiConversationMessage::ROLE_USER)
        ->whereRaw('NOT EXISTS (SELECT 1 FROM ai_conversation_messages AS newer WHERE newer.conversation_id = ai_conversation_messages.conversation_id AND newer.id > ai_conversation_messages.id)')
        ->orderBy('id')
        ->limit($limit)
        ->get();

    if ($pendingMessages->isEmpty()) {
        $this->info('Aucune conversation IA à traiter.');

        return self::SUCCESS;
    }

    $processed = 0;

    foreach ($pendingMessages as $message) {
        $conversation = $message->conversation;
        if (! $conversation) {
            continue;
        }

        $handled = $responder->respond($conversation);
        $processed += (int) $handled;

        $this->line(sprintf(
            '%s conversation #%d (user:%d, trainer:%s)',
            $handled ? '✔️ Réponse envoyée à' : '⚠️ Impossible de répondre à',
            $conversation->id,
            $conversation->user_id,
            $conversation->metadata['trainer'] ?? 'n/a'
        ));
    }

    $this->info(sprintf('%d conversation(s) traitée(s).', $processed));

    return self::SUCCESS;
})->purpose('Générer les réponses IA pour les conversations en attente.');

Schedule::command('ai:process-conversations --limit=5')
    ->everyMinute()
    ->withoutOverlapping();
