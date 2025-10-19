<?php

use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [FormateurPageController::class, 'home'])->name('home');
        Route::get('/formation-edit/{formation}', [FormateurPageController::class, 'edit'])->name('formation.edit');
    });
