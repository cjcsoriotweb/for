<?php

use App\Http\Controllers\Clean\Account\AccountInvitationController;
use App\Http\Controllers\Clean\Account\AccountPageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function (): void {
    Route::get('/user/profile', [AccountPageController::class, 'profile'])
        ->name('user.profile');

    Route::get('/user/profile/informations', [AccountPageController::class, 'editProfileInformation'])
        ->name('user-profile-information.edit');

    Route::get('/user/profile/password', [AccountPageController::class, 'editPassword'])
        ->name('user-password.edit');

    Route::get('/mes-tickets', [AccountPageController::class, 'tickets'])
        ->name('user.tickets');

    Route::get('/mes-tickets/nouveau', [AccountPageController::class, 'ticketsCreate'])
        ->name('user.tickets.create');

    Route::get('/mes-tickets/{ticket}', [AccountPageController::class, 'ticketsShow'])
        ->whereNumber('ticket')
        ->name('user.tickets.show');
});

Route::prefix('mon-compte')
    ->name('user.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AccountPageController::class, 'dashboard'])
            ->name('dashboard');
        Route::post('/switch/team/{team:id}', [AccountPageController::class, 'switch'])->name('switch');
        Route::patch('/invitations/{invitation}/accept', [AccountInvitationController::class, 'accept'])
            ->name('invitation.accept');

    });


Route::get('test', function(){
    return view('test.chat');
});