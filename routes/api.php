<?php

use App\Http\Controllers\AiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// AI API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/ai/stream', [AiController::class, 'stream']);
    Route::post('/ai/conversations', [AiController::class, 'createConversation']);
    Route::get('/ai/conversations', [AiController::class, 'listConversations']);
    Route::get('/ai/conversations/{conversation}', [AiController::class, 'showConversation']);
    Route::get('/ai/users', [AiController::class, 'listUsers']);
});
