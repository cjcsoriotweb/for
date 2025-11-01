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
        
        // AI conversation management
        Route::post('/ai/conversations', [AiController::class, 'createConversation'])->name('ai.conversations.create');
        Route::get('/ai/conversations', [AiController::class, 'listConversations'])->name('ai.conversations.list');
        Route::get('/ai/conversations/{conversation}', [AiController::class, 'showConversation'])->name('ai.conversations.show');
        Route::get('/ai/users', [AiController::class, 'listUsers'])->name('ai.users');

    });
