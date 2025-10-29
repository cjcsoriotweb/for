<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\PageNoteController;
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
        Route::get('/ai-trainers', [AdminPageController::class, 'aiIndex'])->name('ai.index');

        Route::prefix('page-notes')
            ->name('page-notes.')
            ->group(function (): void {
                Route::get('/', [PageNoteController::class, 'index'])->name('index');
                Route::post('/', [PageNoteController::class, 'store'])->name('store');
                Route::patch('/{pageNote}', [PageNoteController::class, 'update'])->name('update');
                Route::delete('/{pageNote}', [PageNoteController::class, 'destroy'])->name('destroy');
            });
    });
