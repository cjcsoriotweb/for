<?php

use App\Http\Controllers\Clean\Admin\AdminPageController;
use App\Http\Controllers\Clean\Admin\Configuration\AdminConfigurationController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationController;
use App\Http\Controllers\Clean\Admin\Formations\AdminFormationStudentController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('administrateur')
    ->name('application.admin.')
    ->middleware(['auth', 'signature', AdminMiddleware::class])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [AdminPageController::class, 'overview'])->name('overview');
        Route::get('{team}/home', [AdminPageController::class, 'home'])
            ->name('index');
        Route::get('{team}/users', [AdminPageController::class, 'users'])->name('users.index');
        Route::get('{team}/formations', [AdminPageController::class, 'formations'])->name('formations.index');
        Route::get('{team}/formations/{formation}/students/{student}', [AdminFormationStudentController::class, 'show'])->name('formations.students.show');
        Route::post('{team}/formations/{formation}/students/{student}/lessons/{lesson}/complete', [AdminFormationStudentController::class, 'completeLesson'])->name('formations.students.lessons.complete');
        Route::post('{team}/formations/{formation}/students/{student}/reset', [AdminFormationStudentController::class, 'reset'])->name('formations.students.reset');
        Route::delete('{team}/formations/{formation}/students/{student}', [AdminFormationStudentController::class, 'unenroll'])->name('formations.students.unenroll');
        Route::get('{team}/configuration', [AdminPageController::class, 'configuration'])->name('configuration.index');
        Route::get('{team}/configuration/credits', [AdminConfigurationController::class, 'credits'])->name('configuration.credits');
        Route::post('{team}/configuration/credit', [AdminConfigurationController::class, 'addCredit'])->name('configuration.credit');
        Route::put('{team}/photo', [AdminConfigurationController::class, 'updatePhoto'])->name('configuration.photo.update');
        Route::delete('{team}/photo', [AdminConfigurationController::class, 'destroy'])->name('configuration.photo.destroy');
        Route::post('{team}/formations/enable/{formation}', [AdminFormationController::class, 'updateVisibilityByTeam'])->name('formations.editVisibility');
    });
