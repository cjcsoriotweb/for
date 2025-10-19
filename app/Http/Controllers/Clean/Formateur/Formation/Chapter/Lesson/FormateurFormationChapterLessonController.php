<?php

namespace App\Http\Controllers\Clean\Formateur\Formation\Chapter\Lesson;

use App\Http\Requests\Formateur\Formation\Chapter\DeleteChapter;
use App\Http\Requests\Formateur\Formation\Chapter\UpdateChapter;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Services\FormationService;

class FormateurFormationChapterLessonController
{
    public function createLesson(Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        $lesson = $formationService->lessons()->createLesson($formation, $chapter);
        return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])->with('success', 'Leçon créée avec succès.');
    }

    public function defineLesson(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $lessonType = request()->input('lesson_type');

        if (!$lessonType) {
            return back()->withErrors(['lesson_type' => 'Veuillez sélectionner un type de leçon.']);
        }

        // Redirect to the appropriate creation page based on lesson type
        switch ($lessonType) {
            case 'quiz':
                return redirect()->route('formateur.formation.chapter.lesson.quiz.create', [$formation, $chapter, $lesson])
                    ->with('success', 'Type de leçon défini. Vous pouvez maintenant créer votre quiz.');

            case 'video':
                return redirect()->route('formateur.formation.chapter.lesson.video.create', [$formation, $chapter, $lesson])
                    ->with('success', 'Type de leçon défini. Vous pouvez maintenant ajouter votre vidéo.');

            case 'text':
                return redirect()->route('formateur.formation.chapter.lesson.text.create', [$formation, $chapter, $lesson])
                    ->with('success', 'Type de leçon défini. Vous pouvez maintenant ajouter votre contenu textuel.');

            default:
                return back()->withErrors(['lesson_type' => 'Type de leçon invalide.']);
        }
    }

    public function createQuiz(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        return view('clean.formateur.Formation.Chapter.Lesson.CreateQuiz', compact('formation', 'chapter', 'lesson'));
    }

    public function createVideo(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        return view('clean.formateur.Formation.Chapter.Lesson.CreateVideo', compact('formation', 'chapter', 'lesson'));
    }

    public function createText(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        return view('clean.formateur.Formation.Chapter.Lesson.CreateText', compact('formation', 'chapter', 'lesson'));
    }
}
