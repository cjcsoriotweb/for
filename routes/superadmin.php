<?php

use Illuminate\Support\Facades\Route;

Route::prefix('superadmin')
    ->name('superadmin.')
    ->scopeBindings()
    ->middleware(['auth', 'can:isSuperAdmin'])
    ->group(function () {

        Route::view('/', 'superadmin.index')->name('home');
        Route::view('/create-team', 'superadmin.create-team')->name('team.create');

    });
