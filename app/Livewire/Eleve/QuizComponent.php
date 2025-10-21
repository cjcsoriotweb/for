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

    // Tracking du temps
    public $startTime = null;
    public $elapsedTime = 0;

    public $countdownDefault = 10000;
    public $start = 10000;

    // Initialisation du composant
    public function mount(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Vérification des permissions
        if (!$this->studentFormationService()->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérification que la leçon est bien un quiz
        if ($lesson->lessonable_type !== Quiz::class) {
            abort(404, 'Quiz non trouvé.');
        }

        // Initialisation des propriétés
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
    }

    // Commencer le quiz
    public function begin()
    {
        while ($this->start >= 0) {
            $this->stream(to: 'count', content: $this->start, replace: true);
            sleep(1);
            $this->start = $this->start - 1;
            if ($this->start == 0) {
                $this->submitQuiz();
            }
        }
    }

    // Réinitialiser le quiz
    public function retryQuiz()
    {
        $this->start = $this->countdownDefault;
        $this->showResults = false;
        $this->answers = [];
        $this->score = 0;
        $this->passed = false;
        $this->correctAnswers = 0;
        $this->totalQuestions = 0;
    }

    // Démarrer le timer du quiz
    public function startQuizTimer()
    {
        $this->startTime = time();
        $this->elapsedTime = 0;
    }

    // Mettre à jour le timer
    public function updateTimer()
    {
        if ($this->startTime) {
            $this->elapsedTime = time() - $this->startTime;
        }
    }

    // Soumettre le quiz
    public function submitQuiz()
    {
        dd('ok');
        $user = Auth::user();
        $quizService = app(\App\Services\Quiz\QuizService::class);
        $result = $quizService->submitQuiz($user, $this->quiz, $this->lesson, $this->answers, $this->startTime);

        // Mise à jour des résultats
        $this->score = $result['score'];
        $this->passed = $result['passed'];
        $this->correctAnswers = $result['correct_answers'];
        $this->totalQuestions = $result['total_questions'];
        $this->attempts++;

        // Mise à jour de la progression si le quiz est réussi
        if ($this->passed) {
            $this->updateFormationProgress($user, $this->formation);
        }

        $this->showResults = true;
    }

    // Service de gestion des formations d'élève
    private function studentFormationService()
    {
        return app(StudentFormationService::class);
    }

    // Mettre à jour la progression dans la formation
    private function updateFormationProgress(User $user, Formation $formation)
    {
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

        // Mise à jour de la progression de l'élève
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'progress_percent' => $progressPercent,
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    // Affichage du composant
    public function render()
    {
        if ($this->passed || $this->quiz->max_attempts == 0 || $this->attempts < $this->quiz->max_attempts) {
            return view('livewire.eleve.quiz-component');
        }

        // Rediriger si le nombre d'essais est dépassé
        $this->redirect(route('eleve.formation.show', [$this->team, $this->formation]));
    }
}
