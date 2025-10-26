<?php

use App\Http\Controllers\Clean\Organisateur\OrganisateurPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('organisateur')
    ->name('organisateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        // Main routes - ordered by specificity (most specific first)
        Route::get('/{team}', [OrganisateurPageController::class, 'home'])->name('index');
        Route::get('/{team}/formations/{formation}/students', [OrganisateurPageController::class, 'students'])->name('formations.students');
        Route::get('/{team}/formations/{formation}/students/{student}/report', [OrganisateurPageController::class, 'studentReport'])->name('formations.students.report');
    });
