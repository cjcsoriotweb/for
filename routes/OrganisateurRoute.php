<?php

use App\Http\Controllers\Clean\Organisateur\OrganisateurPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('organisateur')
    ->name('organisateur.')
    ->middleware(['auth', 'organisateur'])
    ->scopeBindings()
    ->group(function () {
        // Dashboard & Home routes
        Route::get('/{team}', [OrganisateurPageController::class, 'home'])->name('index');

        // Formation management routes
        Route::prefix('formations')->name('formations.')->group(function () {
            // Student management routes - ordered by specificity (most specific first)
            Route::prefix('students')->name('students.')->group(function () {
                // Individual student routes
                Route::get('/{student}/report', [OrganisateurPageController::class, 'studentReport'])->name('report');
                Route::get('/{student}/report/pdf', [OrganisateurPageController::class, 'studentReportPdf'])->name('report.pdf');
                Route::get('/{student}/report/pdf/download', [OrganisateurPageController::class, 'studentReportPdfDownload'])->name('report.pdf.download');

                // General student routes
                Route::get('/', [OrganisateurPageController::class, 'students'])->name('index');
                Route::get('/costs', [OrganisateurPageController::class, 'studentsCost'])->name('cost');
            });
        });
    });
