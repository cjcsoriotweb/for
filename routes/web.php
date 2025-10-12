<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\Team\DashboardController;
use App\Http\Controllers\Team\TeamPhotoController;

// Accueil public
Route::get('/', fn () => view('welcome'));

// Espace perso générique (hors team)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});

// ============================
//  Zone Application par équipe
// ============================
Route::middleware(['auth','verified'])
    ->prefix('application/{team}')
    ->as('team.')
    ->scopeBindings()
    ->group(function () {

        // Accès membres (doit appartenir à l’équipe)
        Route::middleware('can:access-team,team')->group(function () {

            // Tableau de bord d’équipe
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            // Formations visibles dans l’app
            Route::resource('formations', FormationsController::class)
                ->only(['index','show']);

            // Admin (owner/admin uniquement)
            Route::middleware('can:access-admin,team')->group(function () {
                Route::get('/admin', fn () => view('team.admin.index'))
                    ->name('admin.index');
            });

            // -------- Logo / photo d’équipe (dans le même contexte) --------
            Route::put('/photo', [TeamPhotoController::class, 'update'])
                ->name('photo.update')
                ->can('update','team');

            Route::delete('/photo', [TeamPhotoController::class, 'destroy'])
                ->name('photo.destroy')
                ->can('update','team');
        });
    });
