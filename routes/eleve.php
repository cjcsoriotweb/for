<?php

use App\Http\Controllers\Application\Eleve\EleveController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('application/{team}/eleve')->name('application.eleve.')->group(function () {
    Route::get('/', [EleveController::class, 'index'])->name('index');

    Route::prefix('formations')->name('formations.')->group(function () {
        Route::get('/', [EleveController::class, 'formationIndex'])->name('list');

        Route::get('/{formation}', [EleveController::class, 'formationShow'])->name('show');
        Route::get('/{formation}/apercu', [EleveController::class, 'formationPreview'])->name('preview');
        Route::get('/{formation}/continuer', [EleveController::class, 'formationContinue'])->name('continue');
        Route::get('/{formation}/activer', function ($team, $formation) {
            return redirect()->route('application.eleve.formations.preview', [$team, $formation]);
        })->name('activer');

        // Formations
        Route::post('/{formation}/activer', [EleveController::class, 'formationEnable'])->name('enable');

        // Leçons
        Route::get('/{formation}/chapter/{chapter}/lesson/{lesson}', [EleveController::class, 'formationLesson'])->name('lesson');
        Route::post('/{formation}/chapter/{chapter}/lesson/{lesson}/complete', [EleveController::class, 'formationLessonComplete'])->name('lesson.complete');

        // Quiz
        Route::get('/{formation}/chapter/{chapter}/lesson/{lesson}/quiz/{quiz}', [EleveController::class, 'formationQuiz'])->name('quiz');
        Route::post('/{formation}/chapter/{chapter}/lesson/{lesson}/quiz/{quiz}/submit', [EleveController::class, 'formationQuizSubmit'])->name('quiz.submit');
    });
});
