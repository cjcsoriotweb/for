<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\Team\DashboardController; // si tu l'utilises
use App\Http\Controllers\TeamSwitchController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
                Route::view('/dashboard', 'account-dashboard')->name('dashboard');
});

Route::middleware(['auth','verified'])
    ->post('/teams/{team}/switch', [TeamSwitchController::class, 'store'])
    ->middleware('can:access-team,team')   // sécurité: appartient à l'équipe
    ->name('teams.switch');

/*
|--------------------------------------------------------------------------
| Espace d'équipe / application
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])
    ->prefix('team/{team:slug}')   // ← utilise le slug; repasse en {team} si binding par ID
    ->as('team.')
    ->scopeBindings()
    ->group(function () {
        Route::middleware('can:access-team,team')->group(function () {
            Route::get('/', [DashboardController::class, 'show'])->name('dashboard');


            Route::resource('formations', FormationsController::class)
                ->only(['index', 'show'])
                ->names([
                    'index' => 'formations.index',
                    'show'  => 'formations.show',
                ]);

            Route::prefix('admin')
                ->as('admin.')
                ->middleware('can:access-admin,team')
                ->group(function () {
                    Route::view('/', 'team.admin.index')->name('index');

                    Route::view('/formations', 'team.admin.formations.index')->name('formations.index');
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
