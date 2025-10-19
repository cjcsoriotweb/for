<?php


use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use App\Http\Controllers\Clean\Formateur\Formation\FormateurFormationController;
use App\Http\Controllers\Clean\Formateur\Formation\FormateurLessonController;
use App\Http\Controllers\Clean\Formateur\Formation\Chapter\FormateurFormationChapterController;
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
        Route::get('/formation/{formation}/chapitre/{chapter}/show', [FormateurFormationChapterController::class, 'editChapter'])->name('formation.chapter.edit');

        // Lesson routes
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormateurLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/add', [FormateurLessonController::class, 'createLesson'])->name('formation.chapter.lesson.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/{lesson}/delete', [FormateurLessonController::class, 'deleteLesson'])->name('formation.chapter.lesson.delete.post');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormateurLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define.post');

        // Chapter management routes
        Route::put('/formation/{formation}/chapitre/{chapter}/put', [FormateurFormationChapterController::class, 'updateChapter'])->name('formation.chapter.update.put');
        Route::post('/formation-edit/{formation}/chapitre/add', [FormateurFormationChapterController::class, 'createChapter'])->name('formation.chapter.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/delete', [FormateurFormationChapterController::class, 'deleteChapter'])->name('formation.chapter.delete.post');

        // Lesson type specific routes (Quiz)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormateurLessonController::class, 'createQuiz'])->name('formation.chapter.lesson.quiz.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormateurLessonController::class, 'storeQuiz'])->name('formation.chapter.lesson.quiz.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormateurLessonController::class, 'editQuiz'])->name('formation.chapter.lesson.quiz.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormateurLessonController::class, 'updateQuiz'])->name('formation.chapter.lesson.quiz.update');

        // Lesson type specific routes (Video)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormateurLessonController::class, 'createVideo'])->name('formation.chapter.lesson.video.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormateurLessonController::class, 'storeVideo'])->name('formation.chapter.lesson.video.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/edit', [FormateurLessonController::class, 'editVideo'])->name('formation.chapter.lesson.video.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/edit', [FormateurLessonController::class, 'updateVideo'])->name('formation.chapter.lesson.video.update');

        // Lesson type specific routes (Text)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormateurLessonController::class, 'createText'])->name('formation.chapter.lesson.text.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormateurLessonController::class, 'storeText'])->name('formation.chapter.lesson.text.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/edit', [FormateurLessonController::class, 'editText'])->name('formation.chapter.lesson.text.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/edit', [FormateurLessonController::class, 'updateText'])->name('formation.chapter.lesson.text.update');
    });
