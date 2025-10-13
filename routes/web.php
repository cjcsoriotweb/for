<?php

use App\Http\Controllers\Team\ApplicationController;
use App\Http\Controllers\Team\FormationController;
use Illuminate\Support\Facades\Route;

Route::resource('formations', ApplicationController::class)
            ->only(['index','show'])
            ->names('formations')
            ->scoped()                            // {formation:slug} si getRouteKeyName()
            ->middleware('can:access-team,team');



Route::resource('formations', FormationController::class)
            ->only(['index','show'])
            ->names('formations')
            ->scoped()                            // {formation:slug} si getRouteKeyName()
            ->middleware('can:access-team,team');


include_once __DIR__.'/offline.php';
include __DIR__.'/auth.php';
include __DIR__.'/application/application.php';


