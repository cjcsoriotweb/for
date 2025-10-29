<?php

use App\Http\Controllers\Clean\Account\AccountPageController;
use App\Http\Controllers\Clean\Account\AccountInvitationController;
use Illuminate\Support\Facades\Route;

Route::prefix('mon-compte')
    ->name('user.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AccountPageController::class, 'dashboard'])
            ->middleware('tutorial:mon-compte')
            ->name('dashboard');
        Route::post('/switch/team/{team:id}', [AccountPageController::class, 'switch'])->name('switch');
        Route::patch('/invitations/{invitation}/accept', [AccountInvitationController::class, 'accept'])
            ->name('invitation.accept');
    });
