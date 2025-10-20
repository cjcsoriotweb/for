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
        Route::get('/{team}/formation/{formation}', [ElevePageController::class, 'showFormation'])->name('formation.show');
        Route::post('/{team}/formation/{formation}/enroll', [ElevePageController::class, 'enroll'])->name('formation.enroll');
        Route::post('/{team}/formation/{formation}/reset-progress', [ElevePageController::class, 'resetProgress'])->name('formation.reset-progress');
        Route::get('/{team}/api/formations', [ElevePageController::class, 'apiFormations'])->name('api.formations');
    });
