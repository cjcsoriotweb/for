<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
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
    });
