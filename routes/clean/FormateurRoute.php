<?php


use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use App\Http\Controllers\Clean\Formateur\Formation\Chapter\FormateurFormationChapterController;
use App\Http\Controllers\Clean\Formateur\Formation\Chapter\Lesson\FormateurFormationChapterLesson;
use App\Http\Controllers\Clean\Formateur\Formation\Chapter\Lesson\FormateurFormationChapterLessonController;
use Illuminate\Support\Facades\Route;

Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [FormateurPageController::class, 'home'])->name('home');

        // Formation routes
        Route::get('/formation/{formation}/show', [FormateurFormationChapterLessonController::class, 'showFormation'])->name('formation.edit');

        // Chapter routes
        Route::get('/formation/{formation}/chapitre/{chapter}/show', [FormateurFormationChapterLessonController::class, 'editChapter'])->name('formation.chapter.edit');

        // Lesson routes
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormateurFormationChapterLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/add', [FormateurFormationChapterLessonController::class, 'createLesson'])->name('formation.chapter.lesson.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/{lesson}/delete', [FormateurFormationChapterLessonController::class, 'deleteLesson'])->name('formation.chapter.lesson.delete.post');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormateurFormationChapterLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define.post');

        // Chapter management routes
        Route::put('/formation/{formation}/chapitre/{chapter}/put', [FormateurFormationChapterController::class, 'updateChapter'])->name('formation.chapter.update.put');
        Route::post('/formation-edit/{formation}/chapitre/add', [FormateurFormationChapterController::class, 'createChapter'])->name('formation.chapter.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/delete', [FormateurFormationChapterController::class, 'deleteChapter'])->name('formation.chapter.delete.post');

        // Lesson type specific routes (Quiz)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormateurFormationChapterLessonController::class, 'createQuiz'])->name('formation.chapter.lesson.quiz.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormateurFormationChapterLessonController::class, 'storeQuiz'])->name('formation.chapter.lesson.quiz.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormateurFormationChapterLessonController::class, 'editQuiz'])->name('formation.chapter.lesson.quiz.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/edit', [FormateurFormationChapterLessonController::class, 'updateQuiz'])->name('formation.chapter.lesson.quiz.update');

        // Lesson type specific routes (Video)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormateurFormationChapterLessonController::class, 'createVideo'])->name('formation.chapter.lesson.video.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormateurFormationChapterLessonController::class, 'storeVideo'])->name('formation.chapter.lesson.video.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/edit', [FormateurFormationChapterLessonController::class, 'editVideo'])->name('formation.chapter.lesson.video.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/edit', [FormateurFormationChapterLessonController::class, 'updateVideo'])->name('formation.chapter.lesson.video.update');

        // Lesson type specific routes (Text)
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormateurFormationChapterLessonController::class, 'createText'])->name('formation.chapter.lesson.text.create');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormateurFormationChapterLessonController::class, 'storeText'])->name('formation.chapter.lesson.text.store');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/edit', [FormateurFormationChapterLessonController::class, 'editText'])->name('formation.chapter.lesson.text.edit');
        Route::put('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/edit', [FormateurFormationChapterLessonController::class, 'updateText'])->name('formation.chapter.lesson.text.update');
    });
