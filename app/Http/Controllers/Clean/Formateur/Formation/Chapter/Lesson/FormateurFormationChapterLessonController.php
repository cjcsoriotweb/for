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
    public function showFormation(Formation $formation)
    {
        return view('clean.formateur.Formation.FormationShow', compact('formation'));
    }

    public function editChapter(Formation $formation, Chapter $chapter)
    {
        // Logic to edit a chapter of the formation
        return view('clean.formateur.Formation.Chapter.ChapterEdit', compact('formation', 'chapter'));
    }

    public function createLesson(Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        $lesson = $formationService->lessons()->createLesson($formation, $chapter);
        return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])->with('success', 'Leçon créée avec succès.');
    }

    public function deleteLesson(Formation $formation, Chapter $chapter, Lesson $lesson, FormationService $formationService)
    {
        try {
            $formationService->lessons()->deleteLesson($lesson);
            return redirect()->route('formateur.formation.chapter.edit', [$formation, $chapter])
                ->with('success', 'Leçon supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression de la leçon: ' . $e->getMessage()]);
        }
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

    public function storeQuiz(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        // Validate the quiz data
        $validated = request()->validate([
            'quiz_title' => 'required|string|max:255',
            'quiz_description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            // Create the quiz
            $quiz = \App\Models\Quiz::create([
                'lesson_id' => $lesson->id,
                'title' => $validated['quiz_title'],
                'description' => $validated['quiz_description'],
                'passing_score' => $validated['passing_score'],
                'max_attempts' => $validated['max_attempts'],
            ]);

            // Update lesson with polymorphic relationship
            $lesson->update([
                'lessonable_type' => \App\Models\Quiz::class,
                'lessonable_id' => $quiz->id,
            ]);

            return redirect()->route('formateur.formation.chapter.edit', [$formation, $chapter])
                ->with('success', 'Quiz créé avec succès! Vous pouvez maintenant ajouter des questions.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du quiz: ' . $e->getMessage()]);
        }
    }

    public function storeVideo(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        // Validate the video data
        $validated = request()->validate([
            'video_title' => 'required|string|max:255',
            'video_description' => 'nullable|string',
            'video_source' => 'required|in:upload,url',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,webm|max:512000', // 500MB max
            'video_url' => 'nullable|url|required_if:video_source,url',
            'video_duration' => 'nullable|integer|min:1|max:300',
        ]);

        try {
            // Handle file upload or URL
            $videoPath = null;
            if ($validated['video_source'] === 'upload' && request()->hasFile('video_file')) {
                $videoPath = request()->file('video_file')->store('videos', 'public');
            }

            // Create the video content
            $videoContent = \App\Models\VideoContent::create([
                'lesson_id' => $lesson->id,
                'title' => $validated['video_title'],
                'description' => $validated['video_description'],
                'video_url' => $validated['video_source'] === 'url' ? $validated['video_url'] : null,
                'video_path' => $videoPath,
                'duration_minutes' => $validated['video_duration'],
            ]);

            // Update lesson with polymorphic relationship
            $lesson->update([
                'lessonable_type' => \App\Models\VideoContent::class,
                'lessonable_id' => $videoContent->id,
            ]);

            return redirect()->route('formateur.formation.chapter.edit', [$formation, $chapter])
                ->with('success', 'Vidéo ajoutée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'ajout de la vidéo: ' . $e->getMessage()]);
        }
    }

    public function storeText(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        // Validate the text content data
        $validated = request()->validate([
            'content_title' => 'required|string|max:255',
            'content_description' => 'nullable|string',
            'content_text' => 'required|string',
            'estimated_read_time' => 'nullable|integer|min:1|max:120',
            'allow_download' => 'nullable|boolean',
            'show_progress' => 'nullable|boolean',
        ]);

        try {
            // Create the text content
            $textContent = \App\Models\TextContent::create([
                'lesson_id' => $lesson->id,
                'title' => $validated['content_title'],
                'description' => $validated['content_description'],
                'content' => $validated['content_text'],
                'estimated_read_time' => $validated['estimated_read_time'],
                'allow_download' => $validated['allow_download'] ?? false,
                'show_progress' => $validated['show_progress'] ?? true,
            ]);

            // Update lesson with polymorphic relationship
            $lesson->update([
                'lessonable_type' => \App\Models\TextContent::class,
                'lessonable_id' => $textContent->id,
            ]);

            return redirect()->route('formateur.formation.chapter.edit', [$formation, $chapter])
                ->with('success', 'Contenu textuel créé avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du contenu: ' . $e->getMessage()]);
        }
    }

    public function editQuiz(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $quiz = $lesson->lessonable;
        if (!$quiz || !($quiz instanceof \App\Models\Quiz)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Quiz non trouvé pour cette leçon.']);
        }

        return view('clean.formateur.Formation.Chapter.Lesson.EditQuiz', compact('formation', 'chapter', 'lesson', 'quiz'));
    }

    public function updateQuiz(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $quiz = $lesson->lessonable;
        if (!$quiz || !($quiz instanceof \App\Models\Quiz)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Quiz non trouvé pour cette leçon.']);
        }

        // Validate the quiz data
        $validated = request()->validate([
            'quiz_title' => 'required|string|max:255',
            'quiz_description' => 'nullable|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            // Update the quiz
            $quiz->update([
                'title' => $validated['quiz_title'],
                'description' => $validated['quiz_description'],
                'passing_score' => $validated['passing_score'],
                'max_attempts' => $validated['max_attempts'],
            ]);

            return redirect()->route('formateur.formation.edit', $formation)
                ->with('success', 'Quiz modifié avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification du quiz: ' . $e->getMessage()]);
        }
    }

    public function editVideo(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $videoContent = $lesson->lessonable;
        if (!$videoContent || !($videoContent instanceof \App\Models\VideoContent)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Vidéo non trouvée pour cette leçon.']);
        }

        return view('clean.formateur.Formation.Chapter.Lesson.EditVideo', compact('formation', 'chapter', 'lesson', 'videoContent'));
    }

    public function updateVideo(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $videoContent = $lesson->lessonable;
        if (!$videoContent || !($videoContent instanceof \App\Models\VideoContent)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Vidéo non trouvée pour cette leçon.']);
        }

        // Validate the video data
        $validated = request()->validate([
            'video_title' => 'required|string|max:255',
            'video_description' => 'nullable|string',
            'video_source' => 'required|in:upload,url',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,webm|max:512000', // 500MB max
            'video_url' => 'nullable|url|required_if:video_source,url',
            'video_duration' => 'nullable|integer|min:1|max:300',
        ]);

        try {
            // Handle file upload or URL
            $videoPath = $videoContent->video_path; // Keep existing path by default
            if ($validated['video_source'] === 'upload' && request()->hasFile('video_file')) {
                $videoPath = request()->file('video_file')->store('videos', 'public');
            }

            // Update the video content
            $videoContent->update([
                'title' => $validated['video_title'],
                'description' => $validated['video_description'],
                'video_url' => $validated['video_source'] === 'url' ? $validated['video_url'] : null,
                'video_path' => $videoPath,
                'duration_minutes' => $validated['video_duration'],
            ]);

            return redirect()->route('formateur.formation.edit', $formation)
                ->with('success', 'Vidéo modifiée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification de la vidéo: ' . $e->getMessage()]);
        }
    }

    public function editText(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $textContent = $lesson->lessonable;
        if (!$textContent || !($textContent instanceof \App\Models\TextContent)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Contenu textuel non trouvé pour cette leçon.']);
        }

        return view('clean.formateur.Formation.Chapter.Lesson.EditText', compact('formation', 'chapter', 'lesson', 'textContent'));
    }

    public function updateText(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $textContent = $lesson->lessonable;
        if (!$textContent || !($textContent instanceof \App\Models\TextContent)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Contenu textuel non trouvé pour cette leçon.']);
        }

        // Validate the text content data
        $validated = request()->validate([
            'content_title' => 'required|string|max:255',
            'content_description' => 'nullable|string',
            'content_text' => 'required|string',
            'estimated_read_time' => 'nullable|integer|min:1|max:120',
            'allow_download' => 'nullable|boolean',
            'show_progress' => 'nullable|boolean',
        ]);

        try {
            // Update the text content
            $textContent->update([
                'title' => $validated['content_title'],
                'description' => $validated['content_description'],
                'content' => $validated['content_text'],
                'estimated_read_time' => $validated['estimated_read_time'],
                'allow_download' => $validated['allow_download'] ?? false,
                'show_progress' => $validated['show_progress'] ?? true,
            ]);

            return redirect()->route('formateur.formation.edit', $formation)
                ->with('success', 'Contenu textuel modifié avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification du contenu: ' . $e->getMessage()]);
        }
    }
}
