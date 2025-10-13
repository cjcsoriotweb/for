<?php

use App\Http\Controllers\AccountRouting;
use App\Http\Controllers\Application\Admin\TeamPhotoController;
use App\Http\Controllers\ApplicationAdminController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])
    ->prefix('vous')
    ->as('vous.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AccountRouting::class, 'index'])
            ->name('index');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('application/{team:id}')
    ->as('application.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])
            ->name('index')
            ->middleware('can:access-team,team');

        Route::get('/show', [ApplicationController::class, 'show'])
            ->name('show')
            ->middleware('can:access-team,team');

        /* Switch Team */
        Route::post('/switch/application', [ApplicationController::class, 'switch'])
            ->name('switch')
            ->middleware('can:access-team,team');

        /* Admin Routes */
        Route::prefix('admin')
            ->as('admin.')
            ->middleware('can:access-admin,team')
            ->group(function () {
                Route::get('/', [ApplicationAdminController::class, 'index'])
                    ->name('index')
                    ->middleware('can:access-admin,team');

                route::prefix('configuration')
                    ->as('configuration.')
                    ->group(function () {
                        Route::get('/', [ApplicationAdminController::class, 'configurationIndex'])
                            ->name('index')
                            ->middleware('can:access-admin,team');

                        Route::get('/name', [ApplicationAdminController::class, 'configurationName'])
                            ->name('name')
                            ->middleware('can:access-admin,team');        
                });

     
                Route::get('/users', [ApplicationAdminController::class, 'users'])
                    ->name('users')
                    ->middleware('can:access-admin,team');
            });


    });


Route::middleware(['auth','verified'])
    ->prefix('application/{team}')        // ou {team:slug} si tu as un slug
    ->as('teams.')
    ->scopeBindings()
    ->group(function () {
        Route::put('/photo', [TeamPhotoController::class, 'update'])
            ->name('photo.update')
            ->middleware('can:access-admin,team');

        Route::delete('/photo', [TeamPhotoController::class, 'destroy'])
            ->name('photo.destroy')
            ->middleware('can:access-admin,team');
    });