<?php

use App\Http\Controllers\ApplicationAdminConfiguration;
use App\Http\Controllers\ApplicationAdminController;
use App\Http\Controllers\ApplicationAdminFormation;
use App\Http\Controllers\ApplicationAdminPhotoConfiguration;
use App\Http\Controllers\ApplicationAdminUsers;
use Illuminate\Support\Facades\Route;

Route::prefix('application/{team}/tableau-de-bord')
    ->name('application.admin.')
    ->scopeBindings()
    ->middleware('can:board,team')
    ->group(function () {

        Route::get('/', [ApplicationAdminController::class, 'index'])->name('index');

        // Formations
        Route::middleware('can:manage_formation,team')->prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [ApplicationAdminFormation::class, 'formationsIndex'])->name('index');
        });

        // Configuration
        Route::prefix('configuration')->middleware('can:configuration,team')->name('configuration.')->group(function () {
            Route::get('/', [ApplicationAdminConfiguration::class, 'configurationIndex'])->middleware('can:configuration,team')->name('index');
            Route::get('/name', [ApplicationAdminConfiguration::class, 'configurationName'])->middleware('can:configuration,team')->name('name');
            Route::get('/logo', [ApplicationAdminConfiguration::class, 'configurationLogo'])->middleware('can:configuration,team')->name('logo');
        });

        // Utilisateurs
        Route::prefix('users')->middleware('can:manage_users,team')->name('users.')->group(function () {
            Route::get('/', [ApplicationAdminUsers::class, 'usersIndex'])->name('index');
            Route::get('/manager', [ApplicationAdminUsers::class, 'usersManager'])->middleware('can:invite_users,team')->name('manager');
            Route::get('/list', [ApplicationAdminUsers::class, 'usersList'])->middleware('can:list_users,team')->name('list');
        });

        Route::middleware('can:admin,team')->group(function () {
            Route::put('/photo', [ApplicationAdminPhotoConfiguration::class, 'update'])->name('photo.update');
            Route::delete('/photo', [ApplicationAdminPhotoConfiguration::class, 'destroy'])->name('photo.destroy');
        });
    });
