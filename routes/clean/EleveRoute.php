<?php

use App\Http\Controllers\Clean\Eleve\ElevePageController;
use Illuminate\Support\Facades\Route;

Route::prefix('eleve')
    ->name('eleve.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {

        // Main routes - ordered by specificity (most specific first)
        Route::get('/{team}', [ElevePageController::class, 'home'])->name('index');

        // Formation management routes
        Route::prefix('formation')->name('formation.')->group(function () {
            Route::get('/{team}/available', [ElevePageController::class, 'availableFormations'])->name('available');
            Route::get('/{team}/{formation}', [ElevePageController::class, 'showFormation'])->name('show');
            Route::get('/{team}/{formation}/congratulation', [ElevePageController::class, 'formationCongratulation'])->name('congratulation');
            Route::post('/{team}/{formation}/enroll', [ElevePageController::class, 'enroll'])->name('enroll');
            Route::post('/{team}/{formation}/reset-progress', [ElevePageController::class, 'resetProgress'])->name('reset-progress');
        });

        // API routes
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/{team}/formations', [ElevePageController::class, 'apiFormations'])->name('formations');
            Route::get('/{team}/progress/{formation}', [ElevePageController::class, 'apiProgress'])->name('progress');
        });

        // Lesson routes - most specific first
        Route::prefix('lesson')->name('lesson.')->group(function () {
            // Quiz routes (most specific)
            Route::prefix('quiz')->name('quiz.')->group(function () {
                Route::get('/{team}/{formation}/{chapter}/{lesson}/attempt', [ElevePageController::class, 'attemptQuiz'])->name('attempt');
                Route::post('/{team}/{formation}/{chapter}/{lesson}/submit', [ElevePageController::class, 'submitQuiz'])->name('submit');
                Route::get('/{team}/{formation}/{chapter}/{lesson}/results/{attempt}', [ElevePageController::class, 'quizResults'])->name('results');
            });

            // General lesson routes
            Route::get('/{team}/{formation}/{chapter}/{lesson}', [ElevePageController::class, 'showLesson'])->name('show');
            Route::post('/{team}/{formation}/{chapter}/{lesson}/start', [ElevePageController::class, 'startLesson'])->name('start');
            Route::post('/{team}/{formation}/{chapter}/{lesson}/complete', [ElevePageController::class, 'completeLesson'])->name('complete');
            Route::post('/{team}/{formation}/{chapter}/{lesson}/progress', [ElevePageController::class, 'updateProgress'])->name('progress');
        });
    });
