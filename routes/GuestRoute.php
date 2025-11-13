<?php

use App\Http\Controllers\Clean\Guest\PageController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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


