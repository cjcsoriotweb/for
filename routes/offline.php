<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/


Route::get('/', [WelcomeController::class, 'home'])->name('home');
Route::view('/legale', [WelcomeController::class, 'policy'])->name('privacy');