<?php

use App\Http\Controllers\Clean\Admin\SuperadminTestController;
use App\Http\Controllers\Clean\Superadmin\FormationCategoryController;
use App\Http\Controllers\Clean\Superadmin\SuperadminPageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function (): void {
        Route::get('/', [SuperadminPageController::class, 'overview'])->name('overview');
        Route::get('/teams', [SuperadminPageController::class, 'teamsIndex'])->name('teams.index');
        Route::get('/users', [SuperadminPageController::class, 'usersIndex'])->name('users.index');
        Route::get('/formations', [SuperadminPageController::class, 'formationsIndex'])->name('formations.index');
        Route::get('/formations/{formation}', [SuperadminPageController::class, 'formationShow'])->name('formations.show');
        Route::get('/support', [SuperadminPageController::class, 'supportIndex'])->name('support.index');
        Route::prefix('ai')
            ->name('ai.')
            ->group(function (): void {
                Route::get('/', function () {
                    return view('out-application.superadmin.ai.index');
                })->name('index');

                Route::get('/trainers', function () {
                    return view('out-application.superadmin.ai.trainers');
                })->name('trainers');

                Route::get('/categories', function () {
                    return view('out-application.superadmin.ai.categories');
                })->name('categories');
            });
        Route::get('/formation-categories', [FormationCategoryController::class, 'index'])->name('formation-categories.index');
        Route::post('/formation-categories', [FormationCategoryController::class, 'store'])->name('formation-categories.store');
        Route::put('/formation-categories/{formationCategory}', [FormationCategoryController::class, 'update'])->name('formation-categories.update');
        Route::delete('/formation-categories/{formationCategory}', [FormationCategoryController::class, 'destroy'])->name('formation-categories.destroy');
        Route::get('/tests', [SuperadminTestController::class, 'index'])->name('tests.index');
        Route::post('/tests/run', [SuperadminTestController::class, 'run'])->name('tests.run');
    });
