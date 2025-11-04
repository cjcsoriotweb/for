<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;

Artisan::command('ia:delete_message', function () {
    //
})->purpose('Retirer les messages plus vieux entre IA et utilisateur.');

Artisan::command('ai:test', function () {
    $baseUrl = rtrim(env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'), '/');

    $payload = [
        'model' => env('OLLAMA_DEFAULT_MODEL', 'llama3.2:3b'),
        'prompt' => 'Bonjour, comment vas-tu ?',
    ];

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->withOptions(['stream' => true])
        ->post($baseUrl.'/api/generate', $payload);

    if ($response->failed()) {
        $this->error('La requête a échoué : '.$response->body());

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

Schedule::command('ai:process-conversations --limit=5')
    ->everyMinute()
    ->withoutOverlapping();
