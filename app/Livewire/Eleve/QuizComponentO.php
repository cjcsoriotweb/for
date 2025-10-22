<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizChoice;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizComponentO extends Component
{
    /** Étapes d’affichage */
    private const STEP_INIT     = 0;
    private const STEP_LOADING  = 1;
    private const STEP_RUNNING  = 2; // question en cours / timer
    private const STEP_REVIEW   = 3; // résultats

    /** Contexte */
    public Team $team;
    public Formation $formation;
    public Chapter $chapter;
    public Lesson $lesson;
    public Quiz $quiz;

    /** Données du quiz */
    /** @var Collection<int,\App\Models\QuizQuestion> */
    public Collection $questions;

    /** Index de la question courante (0-based) */
    public int $currentQuestionStep = 0;

    /** UI/state */
    public int $step = self::STEP_INIT;
    public int $countdown = 10;      // secondes restantes sur la question/phase
    public int $countdownPast = 0;   // temps total écoulé (s)
    public int $heartbeat = 0;       // ping UI

    /**
     * Réponses sélectionnées par l’utilisateur.
     * On stocke uniquement les IDs pour éviter de sérialiser des modèles Eloquent.
     * Format: [question_id => choice_id]
     */
    public array $reponse = [];

    /* ------------------------------ Lifecycle ------------------------------ */

    public function mount(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson): void
    {
        $this->team      = $team;
        $this->formation = $formation;
        $this->chapter   = $chapter;
        $this->lesson    = $lesson;

        // Vérification inscription
        $user = Auth::user();
        if (!$this->studentFormationService()->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier que la leçon est un Quiz
        if ($lesson->lessonable_type !== Quiz::class) {
            abort(404, 'Quiz non trouvé.');
        }

        $this->quiz = $lesson->lessonable;

        // Précharger les questions + choix
        $this->questions = $this->quiz
            ->quizQuestions()
            ->with('quizChoices') // évite le N+1 dans la vue
            ->get();

        // Passage à l’étape de “pré-chargement”
        $this->step = self::STEP_LOADING;
    }

    /* -------------------------------- Render -------------------------------- */

    public function render()
    {
        // Router d’affichage léger et lisible
        return match ($this->step) {
            self::STEP_LOADING => view('livewire.eleve.quiz.loading-module'),
            self::STEP_RUNNING => $this->countdown >= 1
                ? view('livewire.eleve.quiz.question')
                : view('livewire.eleve.quiz.timeleft'),
            self::STEP_REVIEW  => view('livewire.eleve.quiz.reponse'),
            default            => view('livewire.eleve.quiz.init-quiz'),
        };
    }

    /* ------------------------------ Actions UI ------------------------------ */

    /** Démarre réellement le quiz (lance le timer) */
    public function launchQuiz(int $initialSeconds = 100): void
    {
        $this->countdown = max(1, $initialSeconds);
        $this->countdownPast = 0;
        $this->currentQuestionStep = 0;
        $this->reponse = [];
        $this->step = self::STEP_RUNNING;
    }

    /** Sélection d’un choix pour la question courante */
    public function selectReponse(int $choiceId): void
    {
        $choice = QuizChoice::query()
            ->select(['id', 'question_id'])
            ->findOrFail($choiceId);


        $this->reponse[$choice->question_id] = $choice->id;
    }

    /** Désélection pour une question donnée */
    public function unSelectReponse(int $questionId): void
    {
        unset($this->reponse[$questionId]);
    }

    /** Aller à la question suivante ou passer aux résultats si fini */
    public function nextQuestion(): void
    {
        if ($this->hasNextQuestion()) {
            $this->currentQuestionStep++;
            // on peut remettre un petit compte à rebours question si besoin
            // $this->countdown = 30;
        } else {
            $this->validateQuiz();
        }
    }

    /** Revenir à la question précédente */
    public function prevQuestion(): void
    {
        if ($this->currentQuestionStep > 0) {
            $this->currentQuestionStep--;
        }
    }

    /** Tick de timer côté client (wire:poll / setInterval en JS) */
    public function dIntCountdown(): void
    {
        if ($this->countdown > 0) {
            $this->countdown--;
            $this->countdownPast++;
        }
    }

    /** Heartbeat pour forcer un léger refresh sans logique métier */
    public function heartBeat(): void
    {
        $this->heartbeat++;
    }

    /** Changer d’étape explicitement (avec garde) */
    public function setStep(int $int): void
    {
        // Si on demande de RUN directement, lancer le quiz avec un timer par défaut
        if ($int === self::STEP_RUNNING && $this->step !== self::STEP_RUNNING) {
            $this->launchQuiz(100);
            return;
        }

        // Protection : pas de passage en REVIEW si pas de réponses/fin
        if ($int === self::STEP_REVIEW && $this->step !== self::STEP_REVIEW) {
            $this->validateQuiz();
            return;
        }

        $this->step = $int;
    }

    /* ---------------------------- Validation/Score --------------------------- */

    public function validateQuiz(): void
    {
        // Calcule le score à partir des IDs sélectionnés
        $selectedChoiceIds = array_values($this->reponse);
        if (empty($selectedChoiceIds)) {
            $score = 0;
        } else {
            $score = QuizChoice::query()
                ->whereIn('id', $selectedChoiceIds)
                ->where('is_correct', true)
                ->count();
        }

        // Persistance de la tentative
        QuizAttempt::create([
            'quiz_id'          => $this->quiz->id,
            'user_id'          => Auth::id(),
            'score'            => $score,
            'duration_seconds' => $this->countdownPast,
            'submitted_at'     => now(),
            'started_at'       => now()->subSeconds($this->countdownPast),
        ]);

        // Passage en écran de résultats
        $this->step = self::STEP_REVIEW;
    }

    /* -------------------------------- Helpers -------------------------------- */

    /** Question courante (ou null si out-of-range) */
    public function currentQuestion()
    {
        return $this->questions[$this->currentQuestionStep] ?? null;
    }

    /** Y a-t-il une question suivante ? */
    public function hasNextQuestion(): bool
    {
        return isset($this->questions[$this->currentQuestionStep + 1]);
    }

    /** Le choix est-il sélectionné pour sa question ? (helper pratique pour la vue) */
    public function isSelected(int $choiceId, int $questionId): bool
    {
        return isset($this->reponse[$questionId]) && $this->reponse[$questionId] === $choiceId;
    }

    private function studentFormationService()
    {
        // résout App\Services\Formation\StudentFormationService via le container
        return app(\App\Services\Formation\StudentFormationService::class);
    }
}
