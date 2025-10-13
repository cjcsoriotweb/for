<?php

use App\Http\Controllers\TeamAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/formations', [TeamAdminController::class, 'formationsIndex'])->name('formations.index');
Route::post('/formations/disable/{formationid}', [TeamAdminController::class, 'formationsDisable'])->name('formation.disable');
Route::post('/formations/enable/{formationid}', [TeamAdminController::class, 'formationsEnable'])->name('formation.enable');
