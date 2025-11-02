<?php

namespace App\Http\Controllers\Clean\Formateur\Formation;

use App\Models\Formation;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormationEntryQuizController
{
    public function edit(Formation $formation)
    {
        $quiz = $formation->entryQuiz()
            ->with('quizQuestions.quizChoices')
            ->first();

        return view('out-application.formateur.formation.entry-quiz.edit', compact('formation', 'quiz'));
    }

    public function store(Formation $formation, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
        ]);

        $passingScore = $validated['passing_score'] ?? 80;

        $quiz = $formation->entryQuiz;

        if ($quiz) {
            $quiz->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'passing_score' => $passingScore,
                'max_attempts' => null,
            ]);
        } else {
            $quiz = Quiz::create([
                'formation_id' => $formation->id,
                'lesson_id' => null,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'type' => Quiz::TYPE_ENTRY,
                'passing_score' => $passingScore,
                'max_attempts' => null,
            ]);
        }

        return redirect()
            ->route('formateur.formation.entry-quiz.questions', $formation)
            ->with('success', 'Quiz d’entrée enregistré. Vous pouvez maintenant ajouter des questions.');
    }

    public function manageQuestions(Formation $formation)
    {
        $quiz = $formation->entryQuiz()
            ->with('quizQuestions.quizChoices')
            ->first();

        if (! $quiz) {
            return redirect()
                ->route('formateur.formation.entry-quiz.edit', $formation)
                ->withErrors(['error' => 'Veuillez d’abord créer le quiz d’entrée.']);
        }

        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        return view('out-application.formateur.formation.entry-quiz.manage-questions', compact('formation', 'quiz', 'questions'));
    }

    public function storeQuestion(Formation $formation, Request $request)
    {
        $quiz = $formation->entryQuiz;

        if (! $quiz) {
            return redirect()
                ->route('formateur.formation.entry-quiz.edit', $formation)
                ->withErrors(['error' => 'Veuillez d’abord créer le quiz d’entrée.']);
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false',
            'choices' => 'required|string',
        ]);

        $choices = json_decode($validated['choices'], true);

        if (! $choices || ! is_array($choices)) {
            return back()->withInput()->withErrors(['error' => 'Format des réponses invalide.']);
        }

        DB::beginTransaction();

        try {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $validated['question_text'],
                'type' => $validated['question_type'],
            ]);

            foreach ($choices as $choiceData) {
                QuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }

            DB::commit();

            return back()->with('success', 'Question ajoutée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Erreur lors de l’ajout de la question : '.$e->getMessage(),
            ]);
        }
    }

    public function updateQuestion(Formation $formation, QuizQuestion $question, Request $request)
    {
        $quiz = $formation->entryQuiz;

        if (! $quiz || $question->quiz_id !== $quiz->id) {
            return back()->withErrors(['error' => 'Question introuvable pour ce quiz.']);
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false',
            'choices' => 'required|string',
        ]);

        $choices = json_decode($validated['choices'], true);

        if (! $choices || ! is_array($choices)) {
            return back()->withInput()->withErrors(['error' => 'Format des réponses invalide.']);
        }

        DB::beginTransaction();

        try {
            $question->update([
                'question' => $validated['question_text'],
                'type' => $validated['question_type'],
            ]);

            $question->quizChoices()->delete();

            foreach ($choices as $choiceData) {
                QuizChoice::create([
                    'question_id' => $question->id,
                    'choice_text' => $choiceData['text'],
                    'is_correct' => $choiceData['is_correct'],
                ]);
            }

            DB::commit();

            return back()->with('success', 'Question mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Erreur lors de la mise à jour de la question : '.$e->getMessage(),
            ]);
        }
    }

    public function deleteQuestion(Formation $formation, QuizQuestion $question)
    {
        $quiz = $formation->entryQuiz;

        if (! $quiz || $question->quiz_id !== $quiz->id) {
            return back()->withErrors(['error' => 'Question introuvable pour ce quiz.']);
        }

        $question->delete();

        return back()->with('success', 'Question supprimée avec succès.');
    }
}
