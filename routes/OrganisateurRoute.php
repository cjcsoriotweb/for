<?php

use App\Http\Controllers\Clean\Organisateur\OrganisateurPageController;
use App\Http\Controllers\Clean\Organisateur\RechargeController;
use Illuminate\Support\Facades\Route;

Route::prefix('organisateur')
    ->name('organisateur.')
    ->middleware(['auth', 'organisateur'])
    ->scopeBindings()
    ->group(function () {
        // Main routes - ordered by specificity (most specific first)
        Route::get('/{team}/formations/{formation}/students', [OrganisateurPageController::class, 'students'])->name('formations.students');
        Route::get('/{team}/formations/{formation}/students/costs', [OrganisateurPageController::class, 'studentsCost'])->name('formations.students.cost');
        Route::get('/{team}/formations/{formation}/students/{student}/report', [OrganisateurPageController::class, 'studentReport'])->name('formations.students.report');
        Route::get('/{team}', [OrganisateurPageController::class, 'home'])->name('index');

        // PDF routes
        Route::get('/{team}/formations/{formation}/students/{student}/report/pdf', [OrganisateurPageController::class, 'studentReportPdf'])->name('formations.students.report.pdf');
        Route::get('/{team}/formations/{formation}/students/{student}/report/pdf/download', [OrganisateurPageController::class, 'studentReportPdfDownload'])->name('formations.students.report.pdf.download');

        // Recharge routes
        Route::get('/{team}/recharge', [RechargeController::class, 'show'])->name('recharge.show');
        Route::post('/{team}/recharge/checkout', [RechargeController::class, 'createCheckoutSession'])->name('recharge.checkout');
        Route::get('/{team}/recharge/success', [RechargeController::class, 'success'])->name('recharge.success');
    });
