<?php

use App\Http\Controllers\Clean\Admin\SuperadminTestController;
use App\Http\Controllers\Clean\Superadmin\FormationCategoryController;
use App\Http\Controllers\Clean\Superadmin\SuperadminPageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth'])
    ->group(function (): void {
        Route::post('/impersonation/stop', [SuperadminPageController::class, 'stopImpersonation'])
            ->name('impersonation.stop');

        Route::middleware([AdminMiddleware::class])->group(function (): void {
            Route::get('/', [SuperadminPageController::class, 'overview'])->name('overview');
            Route::get('/teams', [SuperadminPageController::class, 'teamsIndex'])->name('teams.index');
            Route::get('/users', [SuperadminPageController::class, 'usersIndex'])->name('users.index');
            Route::get('/users/{user}', [SuperadminPageController::class, 'userShow'])->name('users.show');
            Route::post('/users/{user}/impersonate', [SuperadminPageController::class, 'impersonateUser'])
                ->name('users.impersonate');
            Route::delete('/users/{user}/formations/{formation}', [SuperadminPageController::class, 'removeUserFormation'])
                ->name('users.formations.destroy');
            Route::get('/formations', [SuperadminPageController::class, 'formationsIndex'])->name('formations.index');
            Route::get('/formations/{formation}', [SuperadminPageController::class, 'formationShow'])->name('formations.show');
            Route::get('/completion-requests', [SuperadminPageController::class, 'completionRequestsIndex'])->name('completion-requests.index');
            Route::get('/completion-requests/{formationUser}', [SuperadminPageController::class, 'completionRequestShow'])->name('completion-requests.show');
            Route::get('/completion-requests/{formationUser}/documents/{index}', [SuperadminPageController::class, 'downloadCompletionDocument'])->name('completion-requests.documents.download');
            Route::post('/completion-requests/{formationUser}/approve', [SuperadminPageController::class, 'approveCompletionRequest'])->name('completion-requests.approve');
            Route::post('/completion-requests/{formationUser}/reject', [SuperadminPageController::class, 'rejectCompletionRequest'])->name('completion-requests.reject');
            Route::post('/completion-requests/{formationUser}/cancel', [SuperadminPageController::class, 'cancelCompletionRequest'])->name('completion-requests.cancel');
            Route::get('/support', [SuperadminPageController::class, 'supportIndex'])->name('support.index');
            Route::get('/console', [SuperadminPageController::class, 'console'])->name('console');
            Route::get('/assistant', [SuperadminPageController::class, 'assistant'])->name('assistant');
            Route::get('/compta', [SuperadminPageController::class, 'comptaDashboard'])->name('compta.index');
            Route::get('/db', [SuperadminPageController::class, 'databasePage'])->name('db');
            Route::post('/db/backup', [SuperadminPageController::class, 'backupDatabase'])->name('db.backup');
            Route::get('/db/backup/{file}', [SuperadminPageController::class, 'downloadBackup'])->name('db.backup.download')->where('file', '[A-Za-z0-9_.-]+');
            Route::get('/formation-categories', [FormationCategoryController::class, 'index'])->name('formation-categories.index');
            Route::post('/formation-categories', [FormationCategoryController::class, 'store'])->name('formation-categories.store');
            Route::put('/formation-categories/{formationCategory}', [FormationCategoryController::class, 'update'])->name('formation-categories.update');
            Route::delete('/formation-categories/{formationCategory}', [FormationCategoryController::class, 'destroy'])->name('formation-categories.destroy');
            Route::get('/tests', [SuperadminTestController::class, 'index'])->name('tests.index');
            Route::post('/tests/run', [SuperadminTestController::class, 'run'])->name('tests.run');
        });
    });
