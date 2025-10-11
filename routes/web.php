<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

use Illuminate\Http\Request;
use Laravel\Jetstream\TeamInvitation;

Route::get('/invites/check', function (Request $request) {
    $email = (string) $request->query('email');
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json(['has' => false]);
    }
    $has = TeamInvitation::where('email', $email)->exists();
    return response()->json(['has' => $has]);
})->name('invites.check');