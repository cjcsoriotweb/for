<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

Route::prefix('application/{team:id}')
    ->name('application.')
    ->scopeBindings()
    ->group(function () {

        /*
        |------------------------------
        | Accès membre d'équipe
        |------------------------------
        */
        Route::middleware('can:access-team,team')->group(function () {
            Route::get('/', [ApplicationController::class, 'index'])->name('index');
            Route::get('/show', [ApplicationController::class, 'show'])->name('show');

            // Switch d'application / d'équipe
            Route::post('/switch', [ApplicationController::class, 'switch'])->name('switch');
        });


    });
