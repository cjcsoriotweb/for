<?php

use App\Http\Controllers\Application\Admin\ApplicationAdminController;
use App\Http\Controllers\Application\Admin\Configuration\ApplicationAdminConfiguration;
use App\Http\Controllers\Application\Admin\Configuration\ApplicationAdminPhotoConfiguration;
use App\Http\Controllers\Application\Admin\Formation\ApplicationAdminFormation;
use App\Http\Controllers\Application\Admin\Users\ApplicationAdminUsers;
use Illuminate\Support\Facades\Route;

Route::prefix('application/{team:id}/tableau-de-bord')
    ->name('application.admin.')
    ->scopeBindings()
    ->middleware('can:admin,team')
    ->group(function () {

        Route::get('/', [ApplicationAdminController::class, 'index'])->name('index');

        // Formations
        Route::middleware('can:admin,team')->prefix('formations')->name('formations.')->group(function () {
            Route::get('/', [ApplicationAdminFormation::class, 'formationsIndex'])->name('index');
            Route::get('/formations-list', [ApplicationAdminFormation::class, 'formationsList'])->name('list');

            Route::post('/formation/enable', [ApplicationAdminFormation::class, 'formationEnable'])->name('enable');
            Route::post('/formation/disable', [ApplicationAdminFormation::class, 'formationDisable'])->name('disable');


        });




        // Configuration
        Route::prefix('configuration')->middleware('can:admin,team')->name('configuration.')->group(function () {
            Route::get('/', [ApplicationAdminConfiguration::class, 'configurationIndex'])->middleware('can:admin,team')->name('index');
            Route::get('/name', [ApplicationAdminConfiguration::class, 'configurationName'])->middleware('can:admin,team')->name('name');
            Route::get('/logo', [ApplicationAdminConfiguration::class, 'configurationLogo'])->middleware('can:admin,team')->name('logo');
        });

        // Utilisateurs
        Route::prefix('users')->middleware('can:admin,team')->name('users.')->group(function () {
            Route::get('/', [ApplicationAdminUsers::class, 'usersIndex'])->name('index');
            Route::get('/manager', [ApplicationAdminUsers::class, 'usersManager'])->middleware('can:admin,team')->name('manager');
        });

        Route::middleware('can:access-team,team')->group(function () {
            Route::put('/photo', [ApplicationAdminPhotoConfiguration::class, 'update'])->name('photo.update');
            Route::delete('/photo', [ApplicationAdminPhotoConfiguration::class, 'destroy'])->name('photo.destroy');
        });
    });
