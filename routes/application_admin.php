<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationAdminController;

Route::prefix('application/{team:id}/admin')
    ->name('application.admin.')
    ->scopeBindings()
    ->middleware('can:access-admin,team')
    ->group(function () {

        Route::get('/', [ApplicationAdminController::class, 'index'])->name('index');

        // Formations
        Route::prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'formationsIndex'])->name('index');
        });

        // Configuration
        Route::prefix('configuration')->name('configuration.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'configurationIndex'])->name('index');
            Route::get('/name', [ApplicationAdminController::class, 'configurationName'])->name('name');
            Route::get('/logo', [ApplicationAdminController::class, 'configurationLogo'])->name('logo');
        });

        // Utilisateurs
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'usersIndex'])->name('index');
            Route::get('/manager', [ApplicationAdminController::class, 'usersManager'])->name('manager');
            Route::get('/list', [ApplicationAdminController::class, 'usersList'])->name('list');
        });
    });
