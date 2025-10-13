<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamSwitchController;

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