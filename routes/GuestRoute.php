<?php

use App\Http\Controllers\Clean\Guest\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('')
    ->name('guest.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/', [PageController::class, 'policy'])->name('policy');

    });
