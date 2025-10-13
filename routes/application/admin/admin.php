<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamAdminController;

Route::prefix('admin')
            ->as('admin.')
            ->middleware('can:admin,team')
            ->group(function () {
                Route::get('/', [TeamAdminController::class, 'index'])->name('index');
                Route::get('/formations', [TeamAdminController::class, 'formationsIndex'])->name('formations.index');
                Route::post('/formations/disable/{formationid}', [TeamAdminController::class, 'formationsDisable'])->name('formation.disable');
                Route::post('/formations/enable/{formationid}', [TeamAdminController::class, 'formationsEnable'])->name('formation.enable');
                Route::get('/users', [TeamAdminController::class, 'usersIndex'])->name('members.index');

        });