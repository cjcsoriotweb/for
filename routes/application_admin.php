<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationAdminController;

Route::prefix('application/{team:id}/admin')
    ->name('application.admin.')
    ->scopeBindings()
    ->middleware('can:admin,team')
    ->group(function () {

        Route::get('/', [ApplicationAdminController::class, 'index'])->name('index');

        // Formations
        Route::middleware('can:manage_formation,team')->prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'formationsIndex'])->name('index');
        });

        // Configuration
        Route::prefix('configuration')->name('configuration.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'configurationIndex'])->middleware('can:configuration,team')->name('index');
            Route::get('/name', [ApplicationAdminController::class, 'configurationName'])->middleware('can:configuration,team')->name('name');
            Route::get('/logo', [ApplicationAdminController::class, 'configurationLogo'])->middleware('can:configuration,team')->name('logo');
        });

        // Utilisateurs
        Route::middleware('can:manage_users,team')->prefix('users')->name('users.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'usersIndex'])->name('index');
            Route::get('/manager', [ApplicationAdminController::class, 'usersManager'])->middleware('can:invite_users,team')->name('manager');
            Route::get('/list', [ApplicationAdminController::class, 'usersList'])->middleware('can:list_users,team')->name('list');
        });


    });
