<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\Configuration\AdminConfigurationController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationController;
use App\Http\Controllers\Clean\Eleve\ElevePageController;
use Illuminate\Support\Facades\Route;

Route::prefix('eleve')
    ->name('eleve.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/{team}', [ElevePageController::class, 'home'])->name('index');
    });
