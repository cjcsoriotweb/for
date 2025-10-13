<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Team\DashboardController;

Route::get('/', [DashboardController::class, 'show'])->name('dashboard');
