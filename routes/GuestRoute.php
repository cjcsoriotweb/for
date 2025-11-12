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


