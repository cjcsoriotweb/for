<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\Configuration\AdminConfigurationController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationController;
use Illuminate\Support\Facades\Route;

Route::prefix('administrateur')
    ->name('application.admin.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('{team}/home', [AdminPageController::class, 'home'])->name('index');
        Route::get('{team}/users', [AdminPageController::class, 'users'])->name('users.index');
        Route::get('{team}/formations', [AdminPageController::class, 'formations'])->name('formations.index');
        Route::get('{team}/configuration', [AdminPageController::class, 'configuration'])->name('configuration.index');
        Route::post('{team}/configuration/credit', [AdminConfigurationController::class, 'addCredit'])->name('configuration.credit');
        Route::put('{team}/photo', [AdminConfigurationController::class, 'updatePhoto'])->name('configuration.photo.update');
        Route::delete('{team}/photo', [AdminConfigurationController::class, 'destroy'])->name('configuration.photo.destroy');
        Route::post('{team}/formations/enable/{formation}', [AdminFormationController::class, 'updateVisibilityByTeam'])->name('formations.editVisibility');
    });
