<?php

use App\Http\Controllers\Clean\Organisateur\OrganisateurPageController;
use Illuminate\Support\Facades\Route;

Route::prefix('organisateur')
    ->name('organisateur.')
    ->middleware(['auth', 'organisateur'])
    ->group(function () {
        // Main routes - ordered by specificity (most specific first)
        Route::get('/{team}/formations/{formation}/students', [OrganisateurPageController::class, 'students'])->name('formations.students');
        Route::get('/{team}/formations/{formation}/students/costs', [OrganisateurPageController::class, 'studentsCost'])->name('formations.students.cost');
        // Student report: split into server-rendered sections
        Route::get('/{team}/formations/{formation}/students/{student}/report', [OrganisateurPageController::class, 'studentReport'])->name('formations.students.report');
        Route::get('/{team}/formations/{formation}/students/{student}/report/overview', [OrganisateurPageController::class, 'studentReportSection'])
            ->defaults('section', 'overview')
            ->name('formations.students.report.overview');
        Route::get('/{team}/formations/{formation}/students/{student}/report/progress', [OrganisateurPageController::class, 'studentReportSection'])
            ->defaults('section', 'progress')
            ->name('formations.students.report.progress');
        Route::get('/{team}/formations/{formation}/students/{student}/report/quizzes', [OrganisateurPageController::class, 'studentReportSection'])
            ->defaults('section', 'quizzes')
            ->name('formations.students.report.quizzes');
        Route::get('/{team}/formations/{formation}/students/{student}/report/activity', [OrganisateurPageController::class, 'studentReportSection'])
            ->defaults('section', 'activity')
            ->name('formations.students.report.activity');
        Route::get('/{team}/formations/{formation}/students/{student}/report/documents', [OrganisateurPageController::class, 'studentReportSection'])
            ->defaults('section', 'documents')
            ->name('formations.students.report.documents');
        Route::get('/{team}/formations/{formation}', [OrganisateurPageController::class, 'show'])->name('formations.show');
        Route::get('/{team}/catalogue', [OrganisateurPageController::class, 'catalogue'])->name('catalogue');
        Route::get('/{team}/users', [OrganisateurPageController::class, 'users'])->name('users');
        Route::get('/{team}', [OrganisateurPageController::class, 'home'])->name('index');

        // PDF routes
        Route::get('/{team}/formations/{formation}/students/{student}/report/pdf', [OrganisateurPageController::class, 'studentReportPdf'])->name('formations.students.report.pdf');
        Route::get('/{team}/formations/{formation}/students/{student}/report/pdf/download', [OrganisateurPageController::class, 'studentReportPdfDownload'])->name('formations.students.report.pdf.download');
    });
