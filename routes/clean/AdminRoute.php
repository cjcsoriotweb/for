<?php

use App\Http\Controllers\Clean\Account\AccountPageController;
use App\Http\Controllers\Clean\Admin\AdminPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('administrateur')
    ->name('application.admin.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/{team}', [AdminPageController::class, 'dashboard'])->name('index');
    });