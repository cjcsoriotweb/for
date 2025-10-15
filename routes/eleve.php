<?php

use App\Http\Controllers\Application\Eleve\EleveController;
use Illuminate\Support\Facades\Route;

Route::prefix('application/{team:id}/apprentisage')
    ->name('application.eleve.')
    ->middleware('can:eleve,team')
    ->group(function () {

        Route::get('/', [EleveController::class, 'index'])->name('index');
        Route::get('/vos-formations', [EleveController::class, 'formationIndex'])->name('formation.index');
        
        Route::get('/formation-preview/{formation}', [EleveController::class, 'formationPreview'])->name('formation.preview');
        Route::get('/formation-continue/{formation}', [EleveController::class, 'formationContinue'])->name('formation.continue');
        Route::get('/formation-enable/{formation}', [EleveController::class, 'formationEnable'])->name('formation.enable');


    });
