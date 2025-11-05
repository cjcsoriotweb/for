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
            'passing_score' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
                function ($attribute, $value, $fail) {
                    if ($value !== null && ($value == 0 || $value == 100)) {
                        $fail('Le seuil de passage doit être strictement entre 0% et 100%. Les valeurs 0% et 100% ne sont pas autorisées.');
                    }
                },
            ],
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
            ->with('success', 'Quiz d entree enregistre. Vous pouvez maintenant ajouter des questions.');
    }

    public function manageQuestions(Formation $formation)
    {
        $quiz = $formation->entryQuiz()
            ->with('quizQuestions.quizChoices')
            ->first();

        if (! $quiz) {
            return redirect()
                ->route('formateur.formation.entry-quiz.edit', $formation)
                ->withErrors(['error' => 'Veuillez d abord creer le quiz d entree.']);
        }

        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        return view('out-application.formateur.formation.entry-quiz.manage-questions', compact('formation', 'quiz', 'questions'));
    }

    public function createQuestion(Formation $formation)
    {
        $quiz = $formation->entryQuiz;

        if (! $quiz) {
            return redirect()
                ->route('formateur.formation.entry-quiz.edit', $formation)
                ->withErrors(['error' => 'Veuillez d abord creer le quiz d entree.']);
        }

        return view('out-application.formateur.formation.entry-quiz.create-question', compact('formation', 'quiz'));
    }

    public function editQuestion(Formation $formation, QuizQuestion $question)
    {
        $quiz = $formation->entryQuiz;

        if (! $quiz || $question->quiz_id !== $quiz->id) {
            return redirect()
                ->route('formateur.formation.entry-quiz.questions', $formation)
                ->withErrors(['error' => 'Question introuvable pour ce quiz.']);
        }

        $question->load('quizChoices');

        return view('out-application.formateur.formation.entry-quiz.edit-question', [
            'formation' => $formation,
            'quiz' => $quiz,
            'question' => $question,
            'choices' => $question->quizChoices->map(fn (QuizChoice $choice) => [
                'id' => $choice->id,
                'text' => $choice->choice_text,
                'is_correct' => (bool) $choice->is_correct,
            ]),
        ]);
    }

    public function storeQuestion(Formation $formation, Request $request)
    {
        $quiz = $formation->entryQuiz;

        if (! $quiz) {
            return redirect()
                ->route('formateur.formation.entry-quiz.edit', $formation)
                ->withErrors(['error' => 'Veuillez d abord creer le quiz d entree.']);
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false',
            'choices' => 'required|string',
        ]);

        $choices = json_decode($validated['choices'], true);

        if (! $choices || ! is_array($choices)) {
            return back()->withInput()->withErrors(['error' => 'Format des reponses invalide.']);
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

            return redirect()
                ->route('formateur.formation.entry-quiz.questions', $formation)
                ->with('success', 'Question ajoutee avec succes.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Erreur lors de l ajout de la question : '.$e->getMessage(),
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
            return back()->withInput()->withErrors(['error' => 'Format des reponses invalide.']);
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

            return redirect()
                ->route('formateur.formation.entry-quiz.questions', $formation)
                ->with('success', 'Question mise a jour avec succes.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Erreur lors de la mise a jour de la question : '.$e->getMessage(),
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

        return redirect()
            ->route('formateur.formation.entry-quiz.questions', $formation)
            ->with('success', 'Question supprimee avec succes.');
    }
}
