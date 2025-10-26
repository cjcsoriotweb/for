<?php

namespace App\Livewire\Eleve;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizChoice;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizComponentO extends Component
{
    private const STEP_INIT = 0;

    private const STEP_LOADING = 1;

    private const STEP_RUNNING = 2;

    private const STEP_REVIEW = 3;

    public Team $team;

    public Formation $formation;

    public Chapter $chapter;

    public Lesson $lesson;

    public Quiz $quiz;

    /** @var Collection<int,\App\Models\QuizQuestion> */
    public Collection $questions;

    public int $currentQuestionStep = 0;

    public int $step = self::STEP_INIT;

    public int $countdown = 10;

    public int $countdownPast = 0;

    public int $heartbeat = 0;

    /**
     * Réponses utilisateur :
     * - single : [question_id => choice_id]
     * - multiple : [question_id => [choice_id, ...]]
     */
    public array $reponse = [];

    public function leave()
    {
        $this->redirectRoute('eleve.formation.show', [$this->team, $this->formation]);
    }

    public function mount(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson): void
    {
        $this->team = $team;
        $this->formation = $formation;
        $this->chapter = $chapter;
        $this->lesson = $lesson;

        $user = Auth::user();
        if (! $this->studentFormationService()->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        if ($lesson->lessonable_type !== Quiz::class) {
            abort(404, 'Quiz non trouvé.');
        }

        if ($this->isLessonCompleted($lesson, Auth::user())) {
            $this->redirectRoute('eleve.formation.show', [$team, $formation]);
        }

        $this->ensureLessonStarted();

        $this->quiz = $lesson->lessonable;

        $this->questions = $this->quiz
            ->quizQuestions()
            ->with('quizChoices')
            ->get();

        $this->step = self::STEP_LOADING;
    }

    public function render()
    {
        return match ($this->step) {
            self::STEP_LOADING => view('livewire.eleve.quiz.loading-module'),
            self::STEP_RUNNING => $this->countdown >= 1
                ? view('livewire.eleve.quiz.question')
                : view('livewire.eleve.quiz.timeleft'),
            self::STEP_REVIEW => view('livewire.eleve.quiz.reponse'),
            default => view('livewire.eleve.quiz.init-quiz'),
        };
    }

    public function launchQuiz(int $initialSeconds = 30): void
    {
        $this->countdown = max(1, $initialSeconds);
        $this->countdownPast = 0;
        $this->currentQuestionStep = 0;
        $this->reponse = [];
        $this->step = self::STEP_RUNNING;
    }

    /** Toggle une réponse selon le type de la question */
    public function selectReponse(int $choiceId): void
    {
        $choice = QuizChoice::query()
            ->select(['id', 'question_id', 'question_id']) // on prend les deux au cas où
            ->findOrFail($choiceId);

        // Détecter le bon question_id (selon ton schéma)
        $questionId = $choice->question_id
            ?? $choice->question_id
            ?? null;

        // Fallback ultra-sûr via la relation, si elle existe
        if (! $questionId && method_exists($choice, 'question')) {
            $questionId = (int) optional($choice->question)->getAttribute('id');
        }

        if (! $questionId) {
            // Rien à faire si on n’a pas pu retrouver la question
            return;
        }

        // Retrouver la question côté collection
        $q = $this->questions->firstWhere('id', (int) $questionId);
        if (! $q) {
            return;
        }

        $type = $this->normalizeType($q->type);

        if ($type === 'multiple_choice') {
            $bucket = $this->reponse[$questionId] ?? [];
            if (! is_array($bucket)) {
                $bucket = [];
            }

            // toggle
            if (in_array($choiceId, $bucket, true)) {
                $bucket = array_values(array_diff($bucket, [$choiceId]));
                if (empty($bucket)) {
                    unset($this->reponse[$questionId]);
                } else {
                    $this->reponse[$questionId] = $bucket;
                }
            } else {
                $bucket[] = $choiceId;
                $this->reponse[$questionId] = array_values(array_unique($bucket));
            }
        } else {
            // single / true_false
            $this->reponse[$questionId] = $choiceId;
        }
    }

    /** Désélection : enlève un seul choix en multiple, ou vide en single */
    public function unSelectReponse(int $questionId, ?int $choiceId = null): void
    {
        $q = $this->questions->firstWhere('id', (int) $questionId);
        if (! $q) {
            return;
        }

        $type = $this->normalizeType($q->type);

        if ($type === 'multiple_choice') {
            if (! isset($this->reponse[$questionId]) || ! is_array($this->reponse[$questionId])) {
                return;
            }

            if ($choiceId !== null) {
                $this->reponse[$questionId] = array_values(array_diff($this->reponse[$questionId], [$choiceId]));
            } else {
                $this->reponse[$questionId] = [];
            }

            if (empty($this->reponse[$questionId])) {
                unset($this->reponse[$questionId]);
            }
        } else {
            unset($this->reponse[$questionId]);
        }
    }

    public function nextQuestion(): void
    {
        if ($this->hasNextQuestion()) {
            $this->currentQuestionStep++;
            $this->countdown = 30; // optionnel
        } else {
            $this->validateQuiz();
        }
    }

    public function prevQuestion(): void
    {
        if ($this->currentQuestionStep > 0) {
            $this->currentQuestionStep--;
        }
    }

    public function dIntCountdown(): void
    {
        if ($this->countdown > 0) {
            $this->countdown--;
            $this->countdownPast++;
        }
    }

    public function heartBeat(): void
    {
        $this->heartbeat++;
    }

    public function setStep(int $int): void
    {
        if ($int === self::STEP_RUNNING && $this->step !== self::STEP_RUNNING) {
            $this->launchQuiz(100);

            return;
        }
        if ($int === self::STEP_REVIEW && $this->step !== self::STEP_REVIEW) {
            $this->validateQuiz();

            return;
        }
        $this->step = $int;
    }

    /** Score par question selon le type */
    public function validateQuiz(): void
    {
        $questionSummaries = [];
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($this->questions as $question) {
            $questionId = (int) $question->id;
            $type = $this->normalizeType($question->type);
            $points = (int) ($question->points ?? 1);
            $totalPoints += max(1, $points);

            $correctIds = $question->quizChoices
                ->where('is_correct', true)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->values();

            $selectedIds = $type === 'multiple_choice'
                ? collect($this->reponse[$questionId] ?? [])
                    ->map(fn ($value) => (int) $value)
                    ->unique()
                    ->values()
                : collect(isset($this->reponse[$questionId]) ? [(int) $this->reponse[$questionId]] : []);

            $sortedSelected = $selectedIds->sort()->values();
            $sortedCorrect = $correctIds->sort()->values();

            $isQuestionCorrect = $sortedSelected->isNotEmpty()
                && $sortedSelected->count() === $sortedCorrect->count()
                && $sortedSelected->every(fn ($value, $index) => $value === ($sortedCorrect[$index] ?? null));

            if ($type !== 'multiple_choice') {
                $isQuestionCorrect = $selectedIds->isNotEmpty() && $correctIds->contains($selectedIds->first());
            }

            if ($isQuestionCorrect) {
                $earnedPoints += max(1, $points);
            }

            $questionSummaries[] = [
                'question' => $question,
                'selected_ids' => $selectedIds,
                'correct_ids' => $correctIds,
                'has_selection' => $selectedIds->isNotEmpty(),
            ];
        }

        $scorePercent = $totalPoints > 0
            ? (int) round(($earnedPoints / $totalPoints) * 100)
            : 0;

        $timestamp = now();

        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => Auth::id(),
            'score' => max(0, min(100, $scorePercent)),
            'max_score' => max(1, $totalPoints),
            'duration_seconds' => $this->countdownPast,
            'submitted_at' => $timestamp,
            'started_at' => $timestamp->copy()->subSeconds($this->countdownPast),
        ]);

        $answerRows = [];

        foreach ($questionSummaries as $summary) {
            /** @var \App\Models\QuizQuestion $question */
            $question = $summary['question'];
            $selectedIds = $summary['selected_ids'];
            $correctIds = $summary['correct_ids'];

            if ($selectedIds->isEmpty()) {
                $answerRows[] = [
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'choice_id' => null,
                    'text_answer' => null,
                    'is_correct' => false,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
                continue;
            }

            foreach ($selectedIds as $choiceId) {
                $answerRows[] = [
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'choice_id' => $choiceId,
                    'text_answer' => null,
                    'is_correct' => $correctIds->contains($choiceId),
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        if (! empty($answerRows)) {
            QuizAnswer::insert($answerRows);
        }

        $this->lesson->learners()->updateExistingPivot(Auth::id(), [
            'status' => 'completed',
            'completed_at' => now(),
            'last_activity_at' => now(),
        ]);

        // Mettre à jour la progression globale de la formation
        $this->updateFormationProgress(Auth::user(), $this->formation);

        $this->step = self::STEP_REVIEW;
    }

    public function currentQuestion()
    {
        return $this->questions[$this->currentQuestionStep] ?? null;
    }

    public function hasNextQuestion(): bool
    {
        return isset($this->questions[$this->currentQuestionStep + 1]);
    }

    public function isSelected(int $choiceId, int $questionId): bool
    {
        if (! isset($this->reponse[$questionId])) {
            return false;
        }

        $value = $this->reponse[$questionId];

        return is_array($value)
            ? in_array($choiceId, $value, true)
            : ((int) $value === (int) $choiceId);
    }

    private function normalizeType(?string $type): string
    {
        // tolère la coquille "multiple_choise"
        $t = strtolower(trim((string) $type));
        if ($t === 'multiple_choise' || $t === 'multiple-choice') {
            return 'multiple_choice';
        }
        if ($t === 'multiple_choice') {
            return 'multiple_choice';
        }
        if ($t === 'true_false' || $t === 'true-false' || $t === 'boolean' || $t === 'single_choice') {
            return 'true_false';
        }

        // défaut : single
        return 'true_false';
    }

    private function isLessonCompleted(Lesson $lesson, User $user): bool
    {
        $pivot = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot;

        return $pivot && $pivot->status === 'completed';
    }

    private function ensureLessonStarted(): void
    {
        $user = Auth::user();

        // Check if lesson_user record exists
        $lessonUser = $this->lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        if (! $lessonUser) {
            // Create lesson_user record with in_progress status
            $this->lesson->learners()->attach($user->id, [
                'status' => 'in_progress',
                'started_at' => now(),
                'last_activity_at' => now(),
            ]);
        }
    }

    private function studentFormationService()
    {
        return app(\App\Services\Formation\StudentFormationService::class);
    }

    /**
     * Mettre à jour la progression globale d'une formation
     */
    private function updateFormationProgress(User $user, Formation $formation): void
    {
        // Récupérer tous les chapitres avec leurs leçons et la progression de l'utilisateur
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($query) use ($user) {
                $query->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->get();

        // Calculer la progression basée sur les leçons terminées
        $totalLessons = $chapters->pluck('lessons')->flatten()->count();
        $completedLessons = 0;

        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonProgress = $lesson->learners->first();
                if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
                    $completedLessons++;
                }
            }
        }

        $progressPercent = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

        // Mettre à jour le current_lesson_id
        $this->updateCurrentLessonId($user, $formation);

        // Mettre à jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Mettre à jour le current_lesson_id pour pointer vers la prochaine leçon non terminée
     */
    private function updateCurrentLessonId(User $user, Formation $formation): void
    {
        // Récupérer la formation avec tous les chapitres et leçons ordonnés
        $formationWithLessons = $formation->load([
            'chapters' => function ($query) {
                $query->orderBy('position')
                    ->with(['lessons' => function ($lessonQuery) {
                        $lessonQuery->orderBy('position');
                    }]);
            },
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et leçons pour trouver la première non terminée
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // Vérifier si cette leçon est terminée
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (! $lessonProgress || $lessonProgress->pivot->status !== 'completed') {
                    // Cette leçon n'est pas terminée, c'est la suivante
                    $nextLessonId = $lesson->id;
                    break 2; // Sortir des deux boucles
                }
            }
        }

        // Mettre à jour le current_lesson_id dans formation_user
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'current_lesson_id' => $nextLessonId,
                'last_seen_at' => now(),
            ],
        ]);
    }
}
