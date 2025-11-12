<?php

use App\Http\Controllers\Clean\Guest\PageController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/vendor/livewire-powergrid/powergrid.js', function () {
    $path = base_path('vendor/power-components/livewire-powergrid/dist/powergrid.js');

    abort_unless(is_file($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/javascript',
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->name('assets.powergrid');

Route::prefix('')
    ->name('guest.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/policy', [PageController::class, 'policy'])
            ->name('policy');
        Route::get('/terms', [PageController::class, 'terms'])
            ->name('terms');

    });

Route::get('/legal/policy', [PageController::class, 'policy'])
    ->name('policy.show');

Route::get('/legal/terms', [PageController::class, 'terms'])
    ->name('terms.show');



Route::get('/test', function () {
    $base   = 'http://nas.goodview.fr';
    $token  = 'sk-caf6eaff4e514f47bf7dae014a37375d'; // remplace ensuite
    $model  = 'admin'; // pris tel quel depuis /api/models

    // 1) Créer le chat (modèle déclaré)
    $create = Http::withToken($token)->asJson()->post($base.'/api/v1/chats/new', [
        'chat' => [
            'title'    => 'Test',
            'models'   => [$model],
            'messages' => [],
            'history'  => ['current_id' => null, 'messages' => (object)[]],
        ],
    ]);
    if ($create->failed()) {
        return response()->json(['step'=>'create_chat','status'=>$create->status(),'error'=>$create->json() ?: $create->body()], 500);
    }
    $chatId = data_get($create->json(), 'chat.id') ?? data_get($create->json(), 'id');

    // 2) Demander la complétion avec le même modèle
    $resp = Http::withToken($token)->asJson()->post($base.'/api/chat/completions', [
        'chat_id'  => $chatId,
        'model'    => $model,
        'messages' => [['role' => 'user', 'content' => 'Liste les utilisateurs !']],
        'stream'   => false,
    ]);

    return response()->json([
        'ok'     => $resp->successful(),
        'status' => $resp->status(),
        'body'   => $resp->json() ?: $resp->body(),
    ], $resp->status() ?: 200);
});


