<?php

use Illuminate\Support\Facades\Route;

Route::prefix('superadmin')
    ->name('superadmin.')
    ->scopeBindings()
    ->middleware(['auth', 'can:isSuperAdmin'])
    ->group(function () {

        Route::view('/', 'welcome')->name('home');

    });
