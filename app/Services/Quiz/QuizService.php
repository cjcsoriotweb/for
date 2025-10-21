<?php

namespace App\Services\Quiz;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class QuizService
{
    /**
     * Soumettre un quiz et calculer les résultats
     */
    public function submitQuiz(User $user, Quiz $quiz, Lesson $lesson, array $answers, ?int $startTime = null): array
    {
        // Calculer le score
        $result = $this->calculateScore($quiz, $answers);

        // Calculer la durée si un temps de début est fourni
        $duration = null;
        if ($startTime) {
            $duration = time() - $startTime;
        }

        // Créer une tentative de quiz avec la durée
        $attempt = $this->createQuizAttempt($user, $quiz, $result, $duration);

        // Enregistrer les réponses individuelles
        $this->saveQuizAnswers($attempt, $quiz, $answers);

        // Mettre à jour la progression de la leçon
        $this->updateLessonProgress($user, $lesson, $result);

        return [
            'success' => true,
            'score' => $result['score'],
            'passed' => $result['passed'],
            'correct_answers' => $result['correct_answers'],
            'total_questions' => $result['total_questions'],
            'max_score' => $result['max_score'],
            'attempt_id' => $attempt->id,
            'duration_seconds' => $duration,
        ];
    }

    /**
     * Calculer le score d'un quiz
     */
    private function calculateScore(Quiz $quiz, array $answers): array
    {
        $totalQuestions = $quiz->quizQuestions()->count();
        $correctAnswers = 0;
        $maxScore = 0;

        foreach ($quiz->quizQuestions as $question) {
            $maxScore += $question->points;

            if (isset($answers[$question->id])) {
                $userAnswer = $answers[$question->id];
                $correctChoice = $question->quizChoices()->where('is_correct', true)->first();

                if ($correctChoice && $correctChoice->id == $userAnswer) {
                    $correctAnswers++;
                }
            }
        }

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $score >= $quiz->passing_score;

        return [
            'score' => $score,
            'passed' => $passed,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
            'max_score' => $maxScore,
        ];
    }

    /**
     * Créer une tentative de quiz
     */
    private function createQuizAttempt(User $user, Quiz $quiz, array $result, ?int $duration = null): QuizAttempt
    {
        return QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $result['score'],
            'max_score' => $result['max_score'],
            'duration_seconds' => $duration ?? 0,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);
    }

    /**
     * Enregistrer les réponses individuelles du quiz
     */
    private function saveQuizAnswers(QuizAttempt $attempt, Quiz $quiz, array $answers): void
    {
        foreach ($answers as $questionId => $choiceId) {
            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'choice_id' => $choiceId,
                'is_correct' => $quiz->quizQuestions()
                    ->find($questionId)
                    ?->quizChoices()
                    ->find($choiceId)
                    ?->is_correct ?? false,
            ]);
        }
    }

    /**
     * Mettre à jour la progression de la leçon après un quiz
     */
    private function updateLessonProgress(User $user, Lesson $lesson, array $result): void
    {
        $attempts = ($lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot?->attempts ?? 0) + 1;

        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'attempts' => $attempts,
                'best_score' => max(
                    $lesson->learners()
                        ->where('user_id', $user->id)
                        ->first()?->pivot?->best_score ?? 0,
                    $result['score']
                ),
                'max_score' => $result['max_score'],
                'last_activity_at' => now(),
                'completed_at' => $result['passed'] ? now() : null,
                'status' => $result['passed'] ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Vérifier si un utilisateur peut tenter un quiz
     */
    public function canAttemptQuiz(User $user, Lesson $lesson, Quiz $quiz): array
    {
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        $attempts = $lessonProgress?->pivot?->attempts ?? 0;

        $canAttempt = true;
        $reason = null;

        if ($quiz->max_attempts > 0 && $attempts >= $quiz->max_attempts) {
            $canAttempt = false;
            $reason = "Vous avez atteint le nombre maximum de tentatives ({$attempts}/{$quiz->max_attempts}).";
        }

        return [
            'can_attempt' => $canAttempt,
            'attempts' => $attempts,
            'max_attempts' => $quiz->max_attempts,
            'reason' => $reason,
        ];
    }

    /**
     * Récupérer les statistiques d'un quiz pour un utilisateur
     */
    public function getQuizStats(User $user, Quiz $quiz): array
    {
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->get();

        if ($attempts->isEmpty()) {
            return [
                'total_attempts' => 0,
                'best_score' => 0,
                'average_score' => 0,
                'last_attempt' => null,
            ];
        }

        $bestScore = $attempts->max('score');
        $averageScore = $attempts->avg('score');
        $lastAttempt = $attempts->sortByDesc('created_at')->first();

        return [
            'total_attempts' => $attempts->count(),
            'best_score' => $bestScore,
            'average_score' => round($averageScore, 2),
            'last_attempt' => $lastAttempt,
        ];
    }

    /**
     * Récupérer les détails d'une tentative de quiz
     */
    public function getAttemptDetails(int $attemptId): ?QuizAttempt
    {
        return QuizAttempt::with(['answers.question.quizChoices', 'quiz.quizQuestions'])
            ->find($attemptId);
    }

    /**
     * Récupérer les statistiques avancées d'un quiz (pour les formateurs)
     */
    public function getQuizAnalytics(Quiz $quiz): array
    {
        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->with('user')
            ->get();

        if ($attempts->isEmpty()) {
            return [
                'total_attempts' => 0,
                'unique_users' => 0,
                'average_score' => 0,
                'pass_rate' => 0,
                'average_duration' => 0,
            ];
        }

        $totalAttempts = $attempts->count();
        $uniqueUsers = $attempts->pluck('user_id')->unique()->count();
        $averageScore = $attempts->avg('score');
        $passedAttempts = $attempts->where('score', '>=', $quiz->passing_score)->count();
        $passRate = $totalAttempts > 0 ? ($passedAttempts / $totalAttempts) * 100 : 0;
        $averageDuration = $attempts->where('duration_seconds', '>', 0)->avg('duration_seconds');

        return [
            'total_attempts' => $totalAttempts,
            'unique_users' => $uniqueUsers,
            'average_score' => round($averageScore, 2),
            'pass_rate' => round($passRate, 2),
            'average_duration' => round($averageDuration, 0),
        ];
    }

    /**
     * Récupérer les réponses détaillées par question pour l'analyse
     */
    public function getQuestionAnalytics(Quiz $quiz): array
    {
        $questions = $quiz->quizQuestions()->with(['quizChoices', 'answers'])->get();
        $analytics = [];

        foreach ($questions as $question) {
            $totalAnswers = $question->answers()->count();
            $correctAnswers = $question->answers()->where('is_correct', true)->count();
            $successRate = $totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0;

            $choiceStats = [];
            foreach ($question->quizChoices as $choice) {
                $choiceCount = $question->answers()->where('choice_id', $choice->id)->count();
                $choiceStats[] = [
                    'choice_id' => $choice->id,
                    'choice_text' => $choice->choice,
                    'is_correct' => $choice->is_correct,
                    'count' => $choiceCount,
                    'percentage' => $totalAnswers > 0 ? round(($choiceCount / $totalAnswers) * 100, 2) : 0,
                ];
            }

            $analytics[] = [
                'question_id' => $question->id,
                'question_text' => $question->question,
                'points' => $question->points,
                'total_answers' => $totalAnswers,
                'correct_answers' => $correctAnswers,
                'success_rate' => round($successRate, 2),
                'choice_stats' => $choiceStats,
            ];
        }

        return $analytics;
    }

    /**
     * Récupérer le classement des étudiants pour un quiz
     */
    public function getQuizLeaderboard(Quiz $quiz, int $limit = 10): array
    {
        return QuizAttempt::where('quiz_id', $quiz->id)
            ->with('user')
            ->where('score', '>=', $quiz->passing_score)
            ->orderBy('score', 'desc')
            ->orderBy('duration_seconds', 'asc')
            ->limit($limit)
            ->get()
            ->map(function ($attempt) {
                return [
                    'user_id' => $attempt->user_id,
                    'user_name' => $attempt->user->name,
                    'score' => $attempt->score,
                    'duration_seconds' => $attempt->duration_seconds,
                    'submitted_at' => $attempt->submitted_at,
                ];
            })
            ->toArray();
    }

    /**
     * Nettoyer les anciennes tentatives de quiz (pour la rétention des données)
     */
    public function cleanupOldAttempts(int $daysToKeep = 365): int
    {
        return QuizAttempt::where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }
}
