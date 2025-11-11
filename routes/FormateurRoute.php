<?php

use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use App\Http\Controllers\Clean\Formateur\Formation\FormateurFormationAiController;
use App\Http\Controllers\Clean\Formateur\Formation\FormateurFormationController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationChapterController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationCompletionDocumentController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationEntryQuizController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationExportController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationLessonController;
use App\Http\Middleware\FormateurMiddleware;
use App\Http\Middleware\FormateurOwner;
use Illuminate\Support\Facades\Route;

Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth', FormateurMiddleware::class])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [FormateurPageController::class, 'home'])->name('home');

        // Formation routes
        Route::get('/formation/create', [FormateurFormationController::class, 'createFormation'])->name('formations.create');

        // ici middleware admin
        Route::middleware(FormateurOwner::class)->group(function () {
            Route::get('/formation/{formation}/show', [FormateurFormationController::class, 'showFormation'])->name('formation.show');
            Route::get('/formation/{formation}/preview', [FormateurFormationController::class, 'previewFormation'])->name('formation.preview');
            Route::get('/formation/{formation}/teams', [FormateurFormationController::class, 'showFormationTeams'])->name('formation.teams.index');
            Route::get('/formation/{formation}/edit', [FormateurFormationController::class, 'editFormation'])->name('formation.edit');
            Route::get('/formation/{formation}/edit/title', [FormateurFormationController::class, 'editFormationTitle'])->name('formation.edit.title');
            Route::put('/formation/{formation}/edit/title', [FormateurFormationController::class, 'updateFormationTitle'])->name('formation.update.title');
            Route::get('/formation/{formation}/edit/description', [FormateurFormationController::class, 'editFormationDescription'])->name('formation.edit.description');
            Route::put('/formation/{formation}/edit/description', [FormateurFormationController::class, 'updateFormationDescription'])->name('formation.update.description');
            Route::get('/formation/{formation}/edit/cover', [FormateurFormationController::class, 'editFormationCoverImage'])->name('formation.edit.cover');
            Route::put('/formation/{formation}/edit/cover', [FormateurFormationController::class, 'updateFormationCoverImage'])->name('formation.update.cover');
            Route::get('/formation/{formation}/contenu', [FormateurFormationController::class, 'editPricing'])->name('formation.pricing.edit');
            Route::get('/formation/{formation}/chapters', [FormateurFormationController::class, 'manageChapters'])->name('formation.chapters.index');
            Route::get('/formation/{formation}/ai', [FormateurFormationAiController::class, 'edit'])->name('formation.ai.edit');
            Route::put('/formation/{formation}/ai', [FormateurFormationAiController::class, 'update'])->name('formation.ai.update');
            // Routes IA supprimées - trainers sont maintenant dans config/ai.php
            Route::put('/formation/{formation}/update', [FormateurFormationController::class, 'updateFormation'])->name('formation.update');
            Route::post('/formation/{formation}/toggle-status', [FormateurFormationController::class, 'toggleStatus'])->name('formation.toggle-status');
            Route::get('/formation/{formation}/completion-documents', [FormationCompletionDocumentController::class, 'index'])->name('formation.completion-documents.index');
            Route::post('/formation/{formation}/completion-documents', [FormationCompletionDocumentController::class, 'store'])->name('formation.completion-documents.store');
            Route::delete('/formation/{formation}/completion-documents/{document}', [FormationCompletionDocumentController::class, 'destroy'])->name('formation.completion-documents.destroy');

            // Export formation

            // Delete formation (superadmin only)
            Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
                Route::get('/formation/{formation}/delete', [FormateurFormationController::class, 'deleteFormation'])->name('formation.delete.show');
                Route::delete('/formation/{formation}/delete', [FormateurFormationController::class, 'destroyFormation'])->name('formation.delete.destroy');
            });

            // Entry quiz management
            Route::get('/formation/{formation}/entry-quiz', [FormationEntryQuizController::class, 'edit'])->name('formation.entry-quiz.edit');
            Route::post('/formation/{formation}/entry-quiz', [FormationEntryQuizController::class, 'store'])->name('formation.entry-quiz.store');
            Route::get('/formation/{formation}/entry-quiz/questions', [FormationEntryQuizController::class, 'manageQuestions'])->name('formation.entry-quiz.questions');
            Route::get('/formation/{formation}/entry-quiz/questions/create', [FormationEntryQuizController::class, 'createQuestion'])->name('formation.entry-quiz.questions.create');
            Route::get('/formation/{formation}/entry-quiz/questions/{question}/edit', [FormationEntryQuizController::class, 'editQuestion'])->name('formation.entry-quiz.questions.edit');
            Route::post('/formation/{formation}/entry-quiz/questions', [FormationEntryQuizController::class, 'storeQuestion'])->name('formation.entry-quiz.questions.store');
            Route::put('/formation/{formation}/entry-quiz/questions/{question}', [FormationEntryQuizController::class, 'updateQuestion'])->name('formation.entry-quiz.questions.update');
            Route::delete('/formation/{formation}/entry-quiz/questions/{question}', [FormationEntryQuizController::class, 'deleteQuestion'])->name('formation.entry-quiz.questions.delete');

            // Chapter routes
            Route::get('/formation/{formation}/chapitre/{chapter}/show', [FormationChapterController::class, 'editChapter'])->name('formation.chapter.edit');

            // Lesson routes
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormationLessonController::class, 'showDefineLesson'])->name('formation.chapter.lesson.define');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/add', [FormationLessonController::class, 'createLesson'])->name('formation.chapter.lesson.add');
            Route::delete('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}', [FormationLessonController::class, 'deleteLesson'])->name('formation.chapter.lesson.delete');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormationLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define.store');
            Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/title', [FormationLessonController::class, 'updateLessonTitle'])->name('formation.chapter.lesson.title.update');

            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/move-up', [FormationLessonController::class, 'moveLessonUp'])->name('formation.chapter.lesson.move-up');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/move-down', [FormationLessonController::class, 'moveLessonDown'])->name('formation.chapter.lesson.move-down');

            // Chapter management routes
            Route::put('/formation/{formation}/chapitre/{chapter}', [FormationChapterController::class, 'updateChapter'])->name('formation.chapter.update');
            Route::post('/formation/{formation}/chapitre', [FormationChapterController::class, 'createChapter'])->name('formation.chapter.store');
            Route::delete('/formation/{formation}/chapitre/{chapter}', [FormationChapterController::class, 'deleteChapter'])->name('formation.chapter.delete');

            // Lesson type specific routes (Quiz)
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormationLessonController::class, 'createQuiz'])->name('formation.chapter.lesson.quiz.create');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormationLessonController::class, 'storeQuiz'])->name('formation.chapter.lesson.quiz.store');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormationLessonController::class, 'editQuiz'])->name('formation.chapter.lesson.quiz.edit');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit/title', [FormationLessonController::class, 'editQuizTitle'])->name('formation.chapter.lesson.quiz.edit.title');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit/description', [FormationLessonController::class, 'editQuizDescription'])->name('formation.chapter.lesson.quiz.edit.description');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit/settings', [FormationLessonController::class, 'editQuizSettings'])->name('formation.chapter.lesson.quiz.edit.settings');
            Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormationLessonController::class, 'updateQuiz'])->name('formation.chapter.lesson.quiz.update');

            // Quiz Questions Management
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/{quiz}/questions', [FormationLessonController::class, 'manageQuestions'])->name('formation.chapter.lesson.quiz.questions');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/{quiz}/test', [FormationLessonController::class, 'testQuiz'])->name('formation.chapter.lesson.quiz.test');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/{quiz}/questions', [FormationLessonController::class, 'storeQuestion'])->name('formation.chapter.lesson.quiz.questions.store');
            Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/{quiz}/questions/{question}', [FormationLessonController::class, 'updateQuestion'])->name('formation.chapter.lesson.quiz.questions.update');
            Route::delete('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/{quiz}/questions/{question}', [FormationLessonController::class, 'deleteQuestion'])->name('formation.chapter.lesson.quiz.questions.delete');

            // Lesson type specific routes (Video)
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormationLessonController::class, 'createVideo'])->name('formation.chapter.lesson.video.create');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormationLessonController::class, 'storeVideo'])->name('formation.chapter.lesson.video.store');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/edit', [FormationLessonController::class, 'editVideo'])->name('formation.chapter.lesson.video.edit');
            Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/edit', [FormationLessonController::class, 'updateVideo'])->name('formation.chapter.lesson.video.update');

            // Lesson type specific routes (Text)
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormationLessonController::class, 'createText'])->name('formation.chapter.lesson.text.create');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormationLessonController::class, 'storeText'])->name('formation.chapter.lesson.text.store');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/edit', [FormationLessonController::class, 'editText'])->name('formation.chapter.lesson.text.edit');
            Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/edit', [FormationLessonController::class, 'updateText'])->name('formation.chapter.lesson.text.update');
            Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/resources', [FormationLessonController::class, 'showResources'])->name('formation.chapter.lesson.resources.index');
            Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/resources', [FormationLessonController::class, 'storeResources'])->name('formation.chapter.lesson.resources.store');
            Route::delete('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/resources/{resource}', [FormationLessonController::class, 'deleteResource'])->name('formation.chapter.lesson.resources.delete');
        });
    });

