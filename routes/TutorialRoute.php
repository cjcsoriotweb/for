<?php

use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

Route::prefix('tutorial')
    ->name('tutorial.')
    ->group(function () {
        Route::get('{tutorial}/intro', [TutorialController::class, 'intro'])->name('intro');
        Route::get('{tutorial}', [TutorialController::class, 'show'])->name('show');
        Route::post('{tutorial}/skip', [TutorialController::class, 'skip'])->name('skip');
    });
