<?php

use App\Http\Controllers\Clean\Account\AccountPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('mon-compte')
    ->name('user.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AccountPageController::class, 'dashboard'])->name('dashboard');
    });
