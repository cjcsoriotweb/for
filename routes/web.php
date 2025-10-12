<?php

use App\Http\Controllers\FormationsController;
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
              Route::get('/formations', fn () => view('admin.formations.index'))->name('formations.index');

    Route::get('/formations/{team}/{formation}/start',
                [FormationsController::class, 'startFormation'])->name('teams.startFormation');


    Route::middleware(['web','auth','verified','can:access-admin'])
        ->prefix('admin')->as('admin.')
        ->group(function () {
            Route::get('/', fn () => view('admin.index'))->name('index');
  
            
        });


});

