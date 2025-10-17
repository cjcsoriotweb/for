<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountRouting;

Route::prefix('vous')
    ->name('vous.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [AccountRouting::class, 'index'])->name('index');

        Route::patch('invitation/accept/{id}', [AccountRouting::class, 'acceptInvitation'])->name('invitation.accept');
    });
