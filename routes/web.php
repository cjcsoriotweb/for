<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\Team\DashboardController; // si tu l'utilises

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
                Route::view('/dashboard', 'account-dashboard')->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Espace d'équipe / application
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('application/{team:slug}')   // ← utilise le slug; repasse en {team} si binding par ID
    ->as('team.')
    ->scopeBindings()
    ->group(function () {

        // Accès membres de l’équipe
        Route::middleware('can:access-team,team')->group(function () {

          

            // Dashboard d'équipe (optionnel si tu as un controller)
            Route::get('/', [DashboardController::class, 'show'])->name('dashboard');

            // Formations visibles par les membres
            Route::resource('formations', FormationsController::class)
                ->only(['index', 'show'])
                ->names([
                    'index' => 'formations.index',
                    'show'  => 'formations.show',
                ]);

            // Accès admin (owner/admin)
            Route::prefix('admin')
                ->as('admin.')
                ->middleware('can:access-admin,team')
                ->group(function () {
                    Route::view('/', 'team.admin.index')->name('index');

                    // Liste/gestion des formations côté admin (vue simple)
                    Route::view('/formations', 'team.admin.formations.index')->name('formations.index');

                    // Si tu passes en controller plus tard :
                    // Route::resource('formations', \App\Http\Controllers\Team\Admin\FormationsController::class)->except('show');
                });
        });
    });

/*
|--------------------------------------------------------------------------
| Fallback (évite des erreurs brutes si un modèle n'est pas trouvé)
|--------------------------------------------------------------------------
*/
Route::missing(function () {
    abort(404);
});
