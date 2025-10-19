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
        Route::get('/formation/{formation}/show', [FormateurPageController::class, 'showFormation'])->name('formation.edit');
        Route::get('/formation/{formation}/chapitre/{chapter}/show', [FormateurPageController::class, 'editChapter'])->name('formation.chapter.edit');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormateurPageController::class, 'editLessonDefine'])->name('formation.chapter.lesson.define');
    });





Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::put('/formation/{formation}/chapitre/{chapter}/put', [FormateurFormationChapterController::class, 'updateChapter'])->name('formation.chapter.update.put');
        Route::post('/formation-edit/{formation}/chapitre/add', [FormateurFormationChapterController::class, 'createChapter'])->name('formation.chapter.add.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/delete', [FormateurFormationChapterController::class, 'deleteChapter'])->name('formation.chapter.delete.post');
        Route::post('/formation-edit/{formation}/chapitre/{chapter}/lesson/add', [FormateurFormationChapterLessonController::class, 'createLesson'])->name('formation.chapter.lesson.add.post');
        Route::post('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/define', [FormateurFormationChapterLessonController::class, 'defineLesson'])->name('formation.chapter.lesson.define.post');

        // Lesson type specific routes
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/quiz/create', [FormateurFormationChapterLessonController::class, 'createQuiz'])->name('formation.chapter.lesson.quiz.create');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/video/create', [FormateurFormationChapterLessonController::class, 'createVideo'])->name('formation.chapter.lesson.video.create');
        Route::get('/formation/{formation}/chapitre/{chapter}/lesson/{lesson}/text/create', [FormateurFormationChapterLessonController::class, 'createText'])->name('formation.chapter.lesson.text.create');
    });
