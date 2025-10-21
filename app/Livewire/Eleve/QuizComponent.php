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

    public function startQuizTimer()
    {
        $this->startTime = time();
        $this->elapsedTime = 0;
    }

    public function updateTimer()
    {
        if ($this->startTime) {
            $this->elapsedTime = time() - $this->startTime;
        }
    }

    public function submitQuiz()
    {
        $user = Auth::user();

        // Utiliser le service de quiz pour traiter la soumission avec le temps de début
        $quizService = app(\App\Services\Quiz\QuizService::class);
        $result = $quizService->submitQuiz($user, $this->quiz, $this->lesson, $this->answers, $this->startTime);

        // Mettre à jour les propriétés du composant
        $this->score = $result['score'];
        $this->passed = $result['passed'];
        $this->correctAnswers = $result['correct_answers'];
        $this->totalQuestions = $result['total_questions'];
        $this->attempts++;

        // Si le quiz est réussi, mettre à jour la progression globale
        if ($this->passed) {
            $this->updateFormationProgress($user, $this->formation);
        }

        $this->showResults = true;
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
        if (
            !$this->passed && $this->quiz->max_attempts == 0 || $this->attempts <
            $this->quiz->max_attempts
        ) {
        } else {
            $this->redirect(route('eleve.formation.show', [$this->team, $this->formation]));
        }
        return view('livewire.eleve.quiz-component');
    }
}
