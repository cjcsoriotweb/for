<?php

use App\Http\Controllers\AccountRouting;
use App\Http\Controllers\OfflineRoutingController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');



Route::middleware(['auth','verified'])
    ->prefix('vous')
    ->as('vous.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AccountRouting::class, 'index'])
            ->name('index');
    });




Route::middleware(['auth','verified'])
    ->prefix('application/{team:id}')
    ->as('application.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])
            ->name('index')
            ->middleware('can:access-team,team');

        Route::post('/switch/application', [ApplicationController::class, 'switch'])
            ->name('switch')
            ->middleware('can:access-team,team');

   
        Route::get('/admin', [ApplicationController::class, 'admin'])
            ->name('admin')
            ->middleware('can:access-admin,team');

        Route::get('/show', [ApplicationController::class, 'show'])
            ->name('show')
            ->middleware('can:access-team,team');
    });

/*
include_once __DIR__.'/offline.php';
include __DIR__.'/auth.php';
include __DIR__.'/application/application.php';

*/


