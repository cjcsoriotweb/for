<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\Team\DashboardController;
use App\Http\Controllers\TeamSwitchController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Espace perso (auth générique)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified'])->group(function () {
    Route::view('/dashboard', 'account-dashboard')->name('dashboard');

    // Switch d’équipe (sécurisé: l’utilisateur doit appartenir à l’équipe)
    Route::post('/teams/{team}/switch', [TeamSwitchController::class, 'store'])
        ->middleware('can:access-team,team')
        ->name('teams.switch');
});

/*
|--------------------------------------------------------------------------
| Espace d'équipe (scopé par id)
|--------------------------------------------------------------------------
*/
Route::prefix('application/{team:id}')
    ->as('team.')
    ->middleware(['auth','verified','can:access-team,team'])
    ->scopeBindings()
    ->group(function () {

        // Tableau de bord d’équipe
        Route::get('/', [DashboardController::class, 'show'])->name('dashboard');

        // Catalogue / Détails des formations (lecture seule ici)
        Route::resource('formations', FormationsController::class)
            ->only(['index','show'])
            ->names([
                'index' => 'formations.index',
                'show'  => 'formations.show',
            ]);

        // Zone admin d’équipe
        Route::prefix('admin')
            ->as('admin.')
            ->middleware('can:access-admin,team')
            ->group(function () {
                Route::view('/', 'team.admin.index')->name('index');
                Route::view('/formations', 'team.admin.formations.index')->name('formations.index');
            });
    });

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});
