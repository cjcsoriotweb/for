<?php

use App\Http\Controllers\Application\Eleve\EleveController;
use Illuminate\Support\Facades\Route;

Route::prefix('application/{team:id}/apprentissage')
    ->name('application.eleve.')
    ->middleware('can:eleve,team')
    ->group(function () {

        Route::get('/', [EleveController::class, 'index'])->name('index');
        Route::get('/formations', [EleveController::class, 'formationIndex'])->name('formations.list');

        Route::get('/formations/{formation}', [EleveController::class, 'formationShow'])->name('formations.show');
        Route::get('/formations/{formation}/apercu', [EleveController::class, 'formationPreview'])->name('formations.preview');
        Route::get('/formations/{formation}/continuer', [EleveController::class, 'formationContinue'])->name('formations.continue');
        Route::get('/formations/{formation}/activer', function (Team $team, Formation $formation) {
            return redirect()->route('application.eleve.formations.preview', [$team, $formation]);
        })->middleware('auth');
        Route::post('/formations/{formation}/activer', [EleveController::class, 'formationEnable'])->name('formations.enable');


    });
