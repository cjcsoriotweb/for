<?php

use App\Http\Controllers\Application\Eleve\EleveController;
use Illuminate\Support\Facades\Route;

Route::prefix('application/{team:id}/apprentisage')
    ->name('application.eleve.')
    ->scopeBindings()
    ->middleware('can:eleve,team')
    ->group(function () {

        Route::get('/', [EleveController::class, 'index'])->name('index');
        Route::get('/vos-formations', [EleveController::class, 'formationIndex'])->name('formation.index');
        Route::get('/formation/{formation:id}', [EleveController::class, 'formationShow'])->name('formation.show');


    });
