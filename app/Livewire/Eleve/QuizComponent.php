<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\Team;
use App\Models\User;
use App\Services\Formation\StudentFormationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizComponent extends Component
{
    public Team $team;

    public Formation $formation;

    public Chapter $chapter;

    public Lesson $lesson;

    public Quiz $quiz;

    public $questions;

    public $answers = [];

    public $attempts = 0;

    // Résultats du quiz
    public $showResults = false;

    public $score = 0;

    public $passed = false;

    public $correctAnswers = 0;

    public $totalQuestions = 0;

    public function mount(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService()->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier que la leçon est bien un quiz
        if ($lesson->lessonable_type !== Quiz::class) {
            abort(404, 'Quiz non trouvé.');
        }

        $this->team = $team;
        $this->formation = $formation;
        $this->chapter = $chapter;
        $this->lesson = $lesson;
        $this->quiz = $lesson->lessonable;

        // Récupérer les questions du quiz
        $this->questions = $this->quiz->quizQuestions()->with('quizChoices')->get();

        // Récupérer le nombre de tentatives
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        $this->attempts = $lessonProgress?->pivot?->attempts ?? 0;

        // Debug: Afficher les informations sur les tentatives
        if (config('app.debug')) {
            logger("Quiz Debug - User ID: {$user->id}, Lesson ID: {$lesson->id}, Attempts: {$this->attempts}, Max Attempts: {$this->quiz->max_attempts}");
        }

        // Vérifier si l'étudiant a déjà atteint le nombre maximum de tentatives
        if ($this->quiz->max_attempts > 0 && $this->attempts >= $this->quiz->max_attempts) {
            $this->showResults = true;
            $this->passed = false;
            $this->score = $lessonProgress?->pivot?->best_score ?? 0;

            // Ajouter un message d'information pour l'utilisateur
            if ($this->attempts > 0) {
                session()->flash('warning', "Vous avez déjà utilisé toutes vos tentatives ({$this->attempts}/{$this->quiz->max_attempts}). Votre meilleur score est de {$this->score}%.");
            }
        }
    }

    public function submitQuiz()
    {
        $user = Auth::user();

        // Calculer le score
        $this->totalQuestions = $this->quiz->quizQuestions()->count();
        $this->correctAnswers = 0;
        $maxScore = 0;

        foreach ($this->quiz->quizQuestions as $question) {
            $maxScore += $question->points;

            if (isset($this->answers[$question->id])) {
                $userAnswer = $this->answers[$question->id];
                $correctChoice = $question->quizChoices()->where('is_correct', true)->first();

                if ($correctChoice && $correctChoice->id == $userAnswer) {
                    $this->correctAnswers++;
                }
            }
        }

        $this->score = $this->totalQuestions > 0 ? ($this->correctAnswers / $this->totalQuestions) * 100 : 0;
        $this->passed = $this->score >= $this->quiz->passing_score;

        // Enregistrer la tentative
        $this->attempts++;

        $this->lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'attempts' => $this->attempts,
                'best_score' => max($this->lesson->learners()->where('user_id', $user->id)->first()?->pivot?->best_score ?? 0, $this->score),
                'max_score' => $maxScore,
                'last_activity_at' => now(),
                'completed_at' => $this->passed ? now() : null,
                'status' => $this->passed ? 'completed' : 'in_progress',
            ],
        ]);

        // Créer d'abord un QuizAttempt
        $quizAttempt = \App\Models\QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $this->quiz->id,
            'score' => $this->score,
            'max_score' => $maxScore,
            'duration_seconds' => 0, // On pourrait calculer la durée réelle si nécessaire
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        // Enregistrer les réponses individuelles liées à cet attempt
        foreach ($this->answers as $questionId => $choiceId) {
            QuizAnswer::create([
                'quiz_attempt_id' => $quizAttempt->id,
                'question_id' => $questionId,
                'choice_id' => $choiceId,
                'is_correct' => $this->quiz->quizQuestions()->find($questionId)?->quizChoices()->find($choiceId)?->is_correct ?? false,
            ]);
        }

        // Si le quiz est réussi, mettre à jour la progression globale
        if ($this->passed) {
            $this->updateFormationProgress($user, $this->formation);
        }

        $this->showResults = true;
    }

    public function retryQuiz()
    {
        $this->showResults = false;
        $this->answers = [];
        $this->score = 0;
        $this->passed = false;
        $this->correctAnswers = 0;
        $this->totalQuestions = 0;
    }

    private function studentFormationService()
    {
        return app(StudentFormationService::class);
    }

    private function updateFormationProgress(User $user, Formation $formation)
    {
        // Calculer la progression basée sur les leçons terminées
        $totalLessons = $formation->chapters()->with('lessons')->get()->pluck('lessons')->flatten()->count();
        $completedLessons = 0;

        foreach ($formation->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
                if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
                    $completedLessons++;
                }
            }
        }

        $progressPercent = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        // Mettre à jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'progress_percent' => $progressPercent,
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.eleve.quiz-component');
    }
}
