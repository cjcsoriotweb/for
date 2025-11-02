<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\SuperadminTestController;
use App\Http\Controllers\Clean\Superadmin\FormationCategoryController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function (): void {
        Route::get('/', [AdminPageController::class, 'overview'])->name('overview');
        Route::get('/teams', [AdminPageController::class, 'teamsIndex'])->name('teams.index');
        Route::get('/users', [AdminPageController::class, 'usersIndex'])->name('users.index');
        Route::get('/formations', [AdminPageController::class, 'formationsIndex'])->name('formations.index');
        Route::get('/support', [AdminPageController::class, 'supportIndex'])->name('support.index');
        Route::get('/ai', function () {
            return view('out-application.superadmin.superadmin-ia-page');
        })->name('ai.index');
        Route::get('/formation-categories', [FormationCategoryController::class, 'index'])->name('formation-categories.index');
        Route::post('/formation-categories', [FormationCategoryController::class, 'store'])->name('formation-categories.store');
        Route::put('/formation-categories/{formationCategory}', [FormationCategoryController::class, 'update'])->name('formation-categories.update');
        Route::delete('/formation-categories/{formationCategory}', [FormationCategoryController::class, 'destroy'])->name('formation-categories.destroy');
        Route::get('/tests', [SuperadminTestController::class, 'index'])->name('tests.index');
        Route::post('/tests/run', [SuperadminTestController::class, 'run'])->name('tests.run');
    });
