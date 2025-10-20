<?php


use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use App\Http\Controllers\Clean\Formateur\Formation\FormateurFormationController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationLessonController;
use App\Http\Controllers\Clean\Formateur\Formation\FormationChapterController;
use Illuminate\Support\Facades\Route;

Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [FormateurPageController::class, 'home'])->name('home');

        // Formation routes
        Route::get('/formation/{formation}/show', [FormateurFormationController::class, 'showFormation'])->name('formation.edit');

        // Chapter routes
        Route::get('/formation/{formation}/chapitre/{chapter}/show', [FormationChapterController::class, 'editChapter'])->name('formation.chapter.edit');

        // Lesson routes
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormationLessonController::class, 'showDefineLesson'])->name('formation.chapter.lesson.define');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/add', [FormationLessonController::class, 'createLesson'])->name('formation.chapter.lesson.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/{lesson}/delete', [FormationLessonController::class, 'deleteLesson'])->name('formation.chapter.lesson.delete.post');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormationLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define.post');

        // Chapter management routes
        Route::put('/formation/{formation}/chapitre/{chapter}/put', [FormationChapterController::class, 'updateChapter'])->name('formation.chapter.update.put');
        Route::post('/formation-edit/{formation}/chapitre/add', [FormationChapterController::class, 'createChapter'])->name('formation.chapter.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/delete', [FormationChapterController::class, 'deleteChapter'])->name('formation.chapter.delete.post');

        // Lesson type specific routes (Quiz)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormationLessonController::class, 'createQuiz'])->name('formation.chapter.lesson.quiz.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormationLessonController::class, 'storeQuiz'])->name('formation.chapter.lesson.quiz.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormationLessonController::class, 'editQuiz'])->name('formation.chapter.lesson.quiz.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormationLessonController::class, 'updateQuiz'])->name('formation.chapter.lesson.quiz.update');

        // Quiz Questions Management
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/{quiz}/questions', [FormationLessonController::class, 'manageQuestions'])->name('formation.chapter.lesson.quiz.questions');
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
    });
