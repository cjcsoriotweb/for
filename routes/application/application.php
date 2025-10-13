<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Team\DashboardController;
use App\Http\Controllers\Team\TeamPhotoController;



/*
|--------------------------------------------------------------------------
| Espace d'équipe (scopé par id)
|--------------------------------------------------------------------------
*/

Route::prefix('application/{team:id}')
    ->as('team.')
    ->middleware(['auth', 'verified', 'can:access-team,team'])
    ->scopeBindings()
    ->group(function () {

        include __DIR__.'/dashboard.php';
        include __DIR__.'/formation.php';
        include __DIR__.'/admin/admin.php';
        
});
// Modifier ici car emplacement en dehors
include __DIR__.'/admin/photo.php';


