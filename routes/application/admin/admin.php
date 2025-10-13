<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamAdminController;

Route::prefix('admin')
            ->as('admin.')
            ->middleware('can:admin,team')
            ->group(function () {
                Route::get('/', [TeamAdminController::class, 'index'])->name('index');
                
                Route::get('/users', [TeamAdminController::class, 'usersIndex'])->name('members.index');
                include __DIR__.'/formation.php';
        });