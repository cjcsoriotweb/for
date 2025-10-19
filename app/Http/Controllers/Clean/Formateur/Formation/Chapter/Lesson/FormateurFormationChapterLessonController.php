<?php

namespace App\Http\Controllers\Clean\Formateur\Formation\Chapter\Lesson;

use App\Http\Requests\Formateur\Formation\Chapter\DeleteChapter;
use App\Http\Requests\Formateur\Formation\Chapter\UpdateChapter;
use App\Models\Chapter;
use App\Models\Formation;
use App\Services\FormationService;

class FormateurFormationChapterLessonController
{
    public function createLesson(Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        $lesson = $formationService->lessons()->createLesson($formation, $chapter);
        return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])->with('success', 'Leçon créée avec succès.');
    }
}
