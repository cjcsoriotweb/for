<?php


use App\Http\Controllers\Clean\Formateur\FormateurPageController;
use App\Http\Controllers\Clean\Formateur\Formation\Chapter\FormateurFormationChapterController;
use Illuminate\Support\Facades\Route;

Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::get('/', [FormateurPageController::class, 'home'])->name('home');
        Route::get('/formation/{formation}/show', [FormateurPageController::class, 'showFormation'])->name('formation.edit');
        Route::get('/formation/{formation}/chapitre/{chapter}/show', [FormateurPageController::class, 'editChapter'])->name('formation.chapter.edit');
    });


Route::prefix('formateur')
    ->name('formateur.')
    ->middleware(['auth'])
    ->scopeBindings()
    ->group(function () {
        Route::put('/formation/{formation}/chapitre/{chapter}/put', [FormateurFormationChapterController::class, 'updateChapter'])->name('formation.chapter.update.put');
        Route::post('/formation-edit/{formation}/chapitre/add', [FormateurFormationChapterController::class, 'createChapter'])->name('formation.chapter.add.post');
    });
