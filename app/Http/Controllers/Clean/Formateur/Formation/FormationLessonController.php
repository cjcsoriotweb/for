<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Services\FormationService;

class FormationLessonController
{
    public function createLesson(Formation $formation, Chapter $chapter, FormationService $formationService)
    {
        $lesson = $formationService->lessons()->createLesson($formation, $chapter);

        return redirect()->route('formateur.formation.show', $formation)->with('success', 'Leçon créée avec succès.');
    }

    public function deleteLesson(Formation $formation, Chapter $chapter, Lesson $lesson, FormationService $formationService)
    {
        try {
            $formationService->lessons()->deleteLesson($lesson);

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Leçon supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression de la leçon: '.$e->getMessage()]);
        }
    }

    public function defineLesson(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $lessonType = request()->input('lesson_type');

        if (! $lessonType) {
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

    public function showDefineLesson(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        return view('clean.formateur.Formation.Chapter.Lesson.Define', compact('formation', 'chapter', 'lesson'));
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
            'max_attempts' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            // Create the quiz
            $quiz = \App\Models\Quiz::create([
                'lesson_id' => $lesson->id,
                'title' => $validated['quiz_title'],
                'description' => $validated['quiz_description'],
                'passing_score' => 0,
                'max_attempts' => $validated['max_attempts'] ?? null,
            ]);

            // Update lesson with polymorphic relationship
            $lesson->update([
                'lessonable_type' => \App\Models\Quiz::class,
                'lessonable_id' => $quiz->id,
            ]);

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Quiz créé avec succès! Vous pouvez maintenant ajouter des questions.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du quiz: '.$e->getMessage()]);
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

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Vidéo ajoutée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'ajout de la vidéo: '.$e->getMessage()]);
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

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Contenu textuel créé avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du contenu: '.$e->getMessage()]);
        }
    }

    public function editQuiz(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $quiz = $lesson->lessonable;
        if (! $quiz || ! ($quiz instanceof \App\Models\Quiz)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Quiz non trouvé pour cette leçon.']);
        }

        return view('clean.formateur.Formation.Chapter.Lesson.EditQuiz', compact('formation', 'chapter', 'lesson', 'quiz'));
    }

    public function updateQuiz(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $quiz = $lesson->lessonable;
        if (! $quiz || ! ($quiz instanceof \App\Models\Quiz)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Quiz non trouvé pour cette leçon.']);
        }

        // Validate the quiz data
        $validated = request()->validate([
            'quiz_title' => 'required|string|max:255',
            'quiz_description' => 'nullable|string',
            'max_attempts' => 'nullable|integer|min:1|max:10',
        ]);

        try {
            // Update the quiz
            $quiz->update([
                'title' => $validated['quiz_title'],
                'description' => $validated['quiz_description'],
                'passing_score' => 0,
                'max_attempts' => $validated['max_attempts'] ?? null,
            ]);

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Quiz modifié avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification du quiz: '.$e->getMessage()]);
        }
    }

    public function manageQuestions(Formation $formation, Chapter $chapter, Lesson $lesson, \App\Models\Quiz $quiz)
    {
        // Ensure the quiz belongs to this lesson
        if ($quiz->lesson_id !== $lesson->id) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Quiz non trouvé pour cette leçon.']);
        }

        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        return view('clean.formateur.Formation.Chapter.Lesson.ManageQuestions', compact('formation', 'chapter', 'lesson', 'quiz', 'questions'));
    }

    public function storeQuestion(Formation $formation, Chapter $chapter, Lesson $lesson, \App\Models\Quiz $quiz)
    {
        // Ensure the quiz belongs to this lesson
        if ($quiz->lesson_id !== $lesson->id) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Quiz non trouvé pour cette leçon.']);
        }

        // Validate the question data
        $validated = request()->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false',
            'choices' => 'required|string', // JSON string from frontend
        ]);

        try {
            // Decode the choices JSON
            $choices = json_decode($validated['choices'], true);

            if (! $choices || ! is_array($choices)) {
                return back()->withInput()->withErrors(['error' => 'Format des réponses invalide.']);
            }

            // Create the question
            $question = \App\Models\QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $validated['question_text'],
                'type' => $validated['question_type'],
            ]);

            // Create the choices
            foreach ($choices as $choiceData) {
                \App\Models\QuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }

            return back()->with('success', 'Question ajoutée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l\'ajout de la question: '.$e->getMessage()]);
        }
    }

    public function updateQuestion(Formation $formation, Chapter $chapter, Lesson $lesson, \App\Models\Quiz $quiz, \App\Models\QuizQuestion $question)
    {
        // Ensure the question belongs to this quiz
        if ($question->quiz_id !== $quiz->id) {
            return back()->withErrors(['error' => 'Question non trouvée pour ce quiz.']);
        }

        // Validate the question data
        $validated = request()->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false',
            'choices' => 'required|string', // JSON string from frontend
        ]);

        try {
            // Decode the choices JSON
            $choices = json_decode($validated['choices'], true);

            if (! $choices || ! is_array($choices)) {
                return back()->withInput()->withErrors(['error' => 'Format des réponses invalide.']);
            }

            // Update the question
            $question->update([
                'question' => $validated['question_text'],
                'type' => $validated['question_type'],
            ]);

            // Delete existing choices and create new ones
            $question->quizChoices()->delete();
            foreach ($choices as $choiceData) {
                \App\Models\QuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }

            return back()->with('success', 'Question modifiée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification de la question: '.$e->getMessage()]);
        }
    }

    public function deleteQuestion(Formation $formation, Chapter $chapter, Lesson $lesson, \App\Models\Quiz $quiz, \App\Models\QuizQuestion $question)
    {
        // Ensure the question belongs to this quiz
        if ($question->quiz_id !== $quiz->id) {
            return back()->withErrors(['error' => 'Question non trouvée pour ce quiz.']);
        }

        try {
            // Delete the question (choices will be cascade deleted)
            $question->delete();

            return back()->with('success', 'Question supprimée avec succès!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression de la question: '.$e->getMessage()]);
        }
    }

    public function editVideo(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $videoContent = $lesson->lessonable;
        if (! $videoContent || ! ($videoContent instanceof \App\Models\VideoContent)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Vidéo non trouvée pour cette leçon.']);
        }

        return view('clean.formateur.Formation.Chapter.Lesson.EditVideo', compact('formation', 'chapter', 'lesson', 'videoContent'));
    }

    public function updateVideo(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $videoContent = $lesson->lessonable;
        if (! $videoContent || ! ($videoContent instanceof \App\Models\VideoContent)) {
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

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Vidéo modifiée avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification de la vidéo: '.$e->getMessage()]);
        }
    }

    public function editText(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $textContent = $lesson->lessonable;
        if (! $textContent || ! ($textContent instanceof \App\Models\TextContent)) {
            return redirect()->route('formateur.formation.chapter.lesson.define', [$formation, $chapter, $lesson])
                ->withErrors(['error' => 'Contenu textuel non trouvé pour cette leçon.']);
        }

        return view('clean.formateur.Formation.Chapter.Lesson.EditText', compact('formation', 'chapter', 'lesson', 'textContent'));
    }

    public function updateText(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $textContent = $lesson->lessonable;
        if (! $textContent || ! ($textContent instanceof \App\Models\TextContent)) {
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

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Contenu textuel modifié avec succès!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification du contenu: '.$e->getMessage()]);
        }
    }

    public function updateLessonTitle(Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        // Validate the lesson title
        $validated = request()->validate([
            'lesson_title' => 'required|string|max:255|min:1',
        ]);

        try {
            // Update the lesson title
            $lesson->update([
                'title' => $validated['lesson_title'],
            ]);

            // Return JSON response for AJAX request
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Titre de la leçon modifié avec succès!',
                    'new_title' => $validated['lesson_title'],
                ]);
            }

            return redirect()->route('formateur.formation.show', $formation)
                ->with('success', 'Titre de la leçon modifié avec succès!');
        } catch (\Exception $e) {
            // Return JSON error for AJAX request
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification du titre: '.$e->getMessage(),
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Erreur lors de la modification du titre: '.$e->getMessage()]);
        }
    }
}
