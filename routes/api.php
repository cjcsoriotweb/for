<?php

use App\Http\Controllers\AiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoint unique pour le streaming IA
Route::post('/ai/stream', [AiController::class, 'stream'])
    ->middleware('auth:sanctum')
    ->name('ai.stream');
