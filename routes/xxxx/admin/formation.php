<?php

use App\Http\Controllers\Team\admin\FormationTeamAdminController;
use App\Http\Controllers\Team\admin\RoutesTeamAdminController;
use Illuminate\Support\Facades\Route;

// Route::get('/afficher-page/formations', [RoutesTeamAdminController::class, 'formationsIndex'])->name('formations.index');

Route::resources([
    'formations' => RoutesTeamAdminController::class,
]);

Route::post('/formations/disable/{formationid}', [FormationTeamAdminController::class, 'formationsDisable'])->name('formation.disable');
Route::post('/formations/enable/{formationid}', [FormationTeamAdminController::class, 'formationsEnable'])->name('formation.enable');
