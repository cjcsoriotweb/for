<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Team\TeamPhotoController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::put('/teams/{team}/photo', [TeamPhotoController::class, 'update'])
        ->name('teams.photo.update')
        ->can('update', 'team');

    Route::delete('/teams/{team}/photo', [TeamPhotoController::class, 'destroy'])
        ->name('teams.photo.destroy')
        ->can('update', 'team');
});