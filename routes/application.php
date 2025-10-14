<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Application\Admin\TeamPhotoController;

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

        /*
        |------------------------------
        | Photo d'équipe (admin)
        |------------------------------
        | On garde ici (sans /admin dans l'URL), mais protégé par la policy admin.
        */
        Route::middleware('can:admin,team')->group(function () {
            Route::put('/photo', [TeamPhotoController::class, 'update'])->name('photo.update');
            Route::delete('/photo', [TeamPhotoController::class, 'destroy'])->name('photo.destroy');
        });
    });
