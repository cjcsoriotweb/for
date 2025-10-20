<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\Configuration\AdminConfigurationController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationController;
use Illuminate\Support\Facades\Route;

Route::prefix('eleve')
    ->name('eleve.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AdminPageController::class, 'home'])->name('index');
        Route::get('{team}/home', [AdminPageController::class, 'home'])->name('index');
    });