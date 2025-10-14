<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountRouting;

Route::prefix('vous')
    ->name('vous.')
    ->group(function () {
        Route::get('/', [AccountRouting::class, 'index'])->name('index');
    });
