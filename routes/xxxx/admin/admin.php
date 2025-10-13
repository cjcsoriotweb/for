<?php

use App\Http\Controllers\Team\admin\RoutesTeamAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
            ->as('admin.')
            ->middleware('can:admin,team')
            ->group(function () {

                Route::resources([
                    '' => RoutesTeamAdminController::class,
                ]);
                include __DIR__.'/formation.php';
        });