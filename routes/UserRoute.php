<?php

use App\Http\Controllers\Clean\Account\AccountPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('mon-compte')
    ->name('user.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AccountPageController::class, 'dashboard'])
            ->middleware('tutorial:mon-compte')
            ->name('dashboard');
        Route::post('/switch/team/{team:id}', [AccountPageController::class, 'switch'])->name('switch');
    });
