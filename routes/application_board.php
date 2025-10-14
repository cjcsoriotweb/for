<?php

use App\Http\Controllers\Application\Admin\TeamPhotoController;
use App\Http\Controllers\ApplicationAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('application/{team:id}/tableau-de-bord')
    ->name('application.admin.')
    ->scopeBindings()
    ->middleware('can:board,team')
    ->group(function () {

        Route::get('/', [ApplicationAdminController::class, 'index'])->name('index');

        // Formations
        Route::middleware('can:manage_formation,team')->prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'formationsIndex'])->name('index');
        });

        // Configuration
        Route::prefix('configuration')->middleware('can:configuration,team')->name('configuration.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'configurationIndex'])->middleware('can:configuration,team')->name('index');
            Route::get('/name', [ApplicationAdminController::class, 'configurationName'])->middleware('can:configuration,team')->name('name');
            Route::get('/logo', [ApplicationAdminController::class, 'configurationLogo'])->middleware('can:configuration,team')->name('logo');
        });

        // Utilisateurs
        Route::prefix('users')->middleware('can:manage_users,team')->name('users.')->group(function () {
            Route::get('/', [ApplicationAdminController::class, 'usersIndex'])->name('index');
            Route::get('/manager', [ApplicationAdminController::class, 'usersManager'])->middleware('can:invite_users,team')->name('manager');
            Route::get('/list', [ApplicationAdminController::class, 'usersList'])->middleware('can:list_users,team')->name('list');
        });

        Route::middleware('can:admin,team')->group(function () {
            Route::put('/photo', [TeamPhotoController::class, 'update'])->name('photo.update');
            Route::delete('/photo', [TeamPhotoController::class, 'destroy'])->name('photo.destroy');
        });

    });
