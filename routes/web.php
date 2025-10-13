<?php

use App\Http\Controllers\AccountRouting;
use App\Http\Controllers\Application\Admin\TeamPhotoController;
use App\Http\Controllers\ApplicationAdminController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Espace perso
    Route::prefix('vous')->name('vous.')->group(function () {
        Route::get('/', [AccountRouting::class, 'index'])->name('index');
    });

    // Espace application scoping par team (binding implicite sur id)
    Route::prefix('application/{team}')
        ->name('application.')
        ->scopeBindings()
        ->group(function () {

            /*
            |------------------------------
            | Accès membre d'équipe
            |------------------------------
            */
            Route::middleware('can:access-team,team')->group(function () {
                Route::get('/', [ApplicationController::class, 'index'])->name('index');
                Route::get('/show', [ApplicationController::class, 'show'])->name('show');

                // Switch d'application / d'équipe
                Route::post('/switch', [ApplicationController::class, 'switch'])->name('switch');
            });

            /*
            |------------------------------
            | Admin d'équipe
            |------------------------------
            */
            Route::prefix('admin')
                ->name('admin.')
                ->middleware('can:access-admin,team')
                ->group(function () {
                    Route::get('/', [ApplicationAdminController::class, 'index'])->name('index');

                    // Configuration
                    Route::prefix('configuration')->name('configuration.')->group(function () {
                        
                        Route::get('/', [ApplicationAdminController::class, 'configurationIndex'])->name('index');
                        Route::get('/name', [ApplicationAdminController::class, 'configurationName'])->name('name');
                        Route::get('/logo', [ApplicationAdminController::class, 'configurationLogo'])->name('logo');
                    
                    });

                    // Utilisateurs
                    Route::prefix('users')->name('users.')->group(function () {
                        Route::get('/', [ApplicationAdminController::class, 'users'])->name('index');
                    });
                });

            /*
            |------------------------------
            | Photo d'équipe (admin)
            |------------------------------
            */
            Route::middleware('can:access-admin,team')->group(function () {
                Route::put('/photo', [TeamPhotoController::class, 'update'])->name('photo.update');
                Route::delete('/photo', [TeamPhotoController::class, 'destroy'])->name('photo.destroy');
            });
        });
});
