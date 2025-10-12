<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\Team\DashboardController;
use App\Http\Controllers\Team\TeamPhotoController;
use App\Http\Controllers\TeamAdminController;
use App\Http\Controllers\TeamSwitchController;
use App\Http\Controllers\WelcomeController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [WelcomeController::class, 'home'])->name('home');
Route::view('/legale', [WelcomeController::class, 'policy'])->name('privacy');

/*
|--------------------------------------------------------------------------
| Espace perso (auth générique)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'account-dashboard')->name('dashboard');
    Route::view('/applications', 'application-list')->name('yoursApplications');

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
    ->middleware(['auth', 'verified', 'can:access-team,team'])
    ->scopeBindings()
    ->group(function () {

        // Tableau de bord d’équipe
        Route::get('/', [DashboardController::class, 'show'])->name('dashboard');

        // Catalogue / Détails des formations (lecture seule ici)
        Route::resource('formations', FormationsController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'formations.index',
                'show'  => 'formations.show',
            ]);

        // Zone admin d’équipe
        Route::prefix('admin')
            ->as('admin.')
            ->middleware('can:admin,team')
            ->group(function () {
                Route::get('/', [TeamAdminController::class, 'index'])->name('index');
                Route::get('/formations', [TeamAdminController::class, 'formationsIndex'])->name('formations.index');
                Route::get('/users', [TeamAdminController::class, 'usersIndex'])->name('members.index');
            });
    });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::put('/teams/{team}/photo', [TeamPhotoController::class, 'update'])
        ->name('teams.photo.update')
        ->can('update', 'team');

    Route::delete('/teams/{team}/photo', [TeamPhotoController::class, 'destroy'])
        ->name('teams.photo.destroy')
        ->can('update', 'team');
});
/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    abort(404);
});
