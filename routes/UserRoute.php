<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\Clean\Account\AccountInvitationController;
use App\Http\Controllers\Clean\Account\AccountPageController;
use Illuminate\Support\Facades\Route;

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

        // AI streaming endpoint
        Route::post('/ai/stream', [AiController::class, 'stream'])->name('ai.stream');

        // Dock iframe routes
        Route::get('/assistant-chat', function () {
            return view('in-application.user.dock.assistant-chat');
        })->name('dock.assistant-chat');

        Route::get('/professeur', function () {
            return view('in-application.user.dock.professeur');
        })->name('dock.professeur');

        Route::get('/support', function () {
            return view('in-application.user.dock.support');
        })->name('dock.support');

        Route::get('/recherche', function () {
            return view('in-application.user.dock.recherche');
        })->name('dock.recherche');

    });
