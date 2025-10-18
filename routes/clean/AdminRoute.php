<?php

use App\Http\Controllers\Clean\Account\AccountPageController;
use App\Http\Controllers\Clean\Admin\AdminPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('administrateur')
    ->name('application.admin.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/home/{team}', [AdminPageController::class, 'home'])->name('index');
        Route::get('/users/{team}', [AdminPageController::class, 'users'])->name('users.index');
        Route::get('/formations/{team}', [AdminPageController::class, 'users'])->name('formations.index');
    });