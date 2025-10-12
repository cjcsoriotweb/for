<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationsController;
use App\Http\Controllers\Team\DashboardController; // adapte si besoin

// Accueil public
Route::get('/', function () {
    return view('welcome');
});

// Espace perso générique (hors team)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Espace "app d'équipe"
Route::middleware(['auth','verified'])
    ->prefix('application/{team}')   // {team} doit exister ici
    ->as('team.')
    ->scopeBindings()
    ->group(function () {

        // Accès membres
        Route::middleware('can:access-team,team')->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('formations', FormationsController::class)
                ->only(['index','show']);

            // Accès admin (owner/admin) — IMPORTANT: ,team
            Route::middleware('can:access-admin,team')->group(function () {
                Route::get('/admin', fn () => view('team.admin.index'))->name('admin.index');
            });
        });
    });