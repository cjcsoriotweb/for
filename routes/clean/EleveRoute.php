<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\Configuration\AdminConfigurationController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationController;
use App\Http\Controllers\Clean\Eleve\ElevePageController;
use Illuminate\Support\Facades\Route;

Route::prefix('eleve')
    ->name('eleve.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/{team}', [ElevePageController::class, 'home'])->name('index');
        Route::get('/{team}/formation/{formation}', [ElevePageController::class, 'showFormation'])->name('formation.show');
        Route::post('/{team}/formation/{formation}/enroll', [ElevePageController::class, 'enroll'])->name('formation.enroll');
        Route::post('/{team}/formation/{formation}/reset-progress', [ElevePageController::class, 'resetProgress'])->name('formation.reset-progress');
        Route::get('/{team}/api/formations', [ElevePageController::class, 'apiFormations'])->name('api.formations');

        // Lesson viewing routes
        Route::get('/{team}/formation/{formation}/chapter/{chapter}/lesson/{lesson}', [ElevePageController::class, 'showLesson'])->name('lesson.show');
        Route::post('/{team}/formation/{formation}/chapter/{chapter}/lesson/{lesson}/start', [ElevePageController::class, 'startLesson'])->name('lesson.start');
        Route::post('/{team}/formation/{formation}/chapter/{chapter}/lesson/{lesson}/complete', [ElevePageController::class, 'completeLesson'])->name('lesson.complete');
        Route::post('/{team}/formation/{formation}/chapter/{chapter}/lesson/{lesson}/progress', [ElevePageController::class, 'updateProgress'])->name('lesson.progress');

        // Quiz attempt routes
        Route::get('/{team}/formation/{formation}/chapter/{chapter}/lesson/{lesson}/quiz/attempt', [ElevePageController::class, 'attemptQuiz'])->name('lesson.quiz.attempt');
        Route::post('/{team}/formation/{formation}/chapter/{chapter}/lesson/{lesson}/quiz/submit', [ElevePageController::class, 'submitQuiz'])->name('lesson.quiz.submit');
    });
