<?php
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationController;
use App\Http\Controllers\Clean\Admin\Configuration\AdminConfigurationController;

Route::prefix('administrateur')
    ->name('application.admin.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/home/{team}', [AdminPageController::class, 'home'])->name('index');
        Route::get('/users/{team}', [AdminPageController::class, 'users'])->name('users.index');


        Route::get('/formations/{team}', [AdminPageController::class, 'formations'])->name('formations.index');
        Route::post('/formations/{team}/enable/{formation}', [AdminFormationController::class, 'update'])->name('formations.editVisibility');


        
        Route::get('/configuration/{team}', [AdminPageController::class, 'configuration'])->name('configuration.index');


        Route::put('/photo', [AdminConfigurationController::class, 'update'])->name('configuration.photo.update');
        Route::delete('/photo', [AdminConfigurationController::class, 'destroy'])->name('configuration.photo.destroy');
    
    });