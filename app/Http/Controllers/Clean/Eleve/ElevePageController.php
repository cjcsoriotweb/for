<?php

namespace App\Http\Controllers\Clean\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Team;
use App\Models\User;
use App\Services\Clean\Account\AccountService;
use App\Services\Formation\StudentFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElevePageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
        private readonly StudentFormationService $studentFormationService,
    ) {}


    public function home(Team $team)
    {
        $user = Auth::user();

        // Récupérer les formations actuelles de l'étudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Ajouter les données de progression pour chaque formation
        $formationsWithProgress = $formations->map(function ($formation) use ($user) {
            $progress = $this->studentFormationService->getStudentProgress($user, $formation);
            $formation->progress_data = $progress ?? [
                'status' => 'enrolled',
                'progress_percent' => 0,
                'current_lesson_id' => null,
                'enrolled_at' => now(),
                'last_seen_at' => now(),
                'completed_at' => null,
                'score_total' => 0,
                'max_score_total' => 0,
            ];
            $formation->is_completed = $this->studentFormationService->isFormationCompleted($user, $formation);
            return $formation;
        });

        // Paginer les formations pour l'API
        $formationsPaginees = $this->studentFormationService->paginateFormationCurrentByStudent($team, $user, 10);

        return view('clean.eleve.home', compact(
            'team',
            'formationsWithProgress',
            'formationsPaginees'
        ));
    }

    /**
     * Afficher les détails d'une formation pour un étudiant
     */
    public function showFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }
        $studentFormationService = $this->studentFormationService;

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        // Récupérer le progrès détaillé
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        return view('clean.eleve.formation.show', compact(
            'team',
            'studentFormationService',
            'formationWithProgress',
            'progress'
        ));
    }

    /**
     * Afficher la page de félicitations pour une formation terminée
     */
    public function formationCongratulation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation est terminée
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'La formation n\'est pas encore terminée.');
        }

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        return view('clean.eleve.formation.congratulation', compact(
            'team',
            'formationWithProgress'
        ));
    }

    /**
     * Inscrire un étudiant à une formation
     */
    public function enroll(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est déjà inscrit
        if ($this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('warning', 'Vous êtes déjà inscrit à cette formation.');
        }

        // Vérifier si la formation est disponible pour cette équipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->pluck('formations.id');

        if (! $availableFormations->contains($formation->id)) {
            return back()->with('error', 'Cette formation n\'est pas disponible pour votre équipe.');
        }

        // Inscrire l'étudiant à la formation
        try {
            // Récupérer la première leçon de la formation pour initialiser current_lesson_id
            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            $formation->learners()->attach($user->id, [
                'team_id' => $team->id,
                'status' => 'enrolled',
                'enrolled_at' => now(),
                'current_lesson_id' => $firstLesson?->id,
                'last_seen_at' => now(),
            ]);

            return back()->with('success', 'Vous avez été inscrit à la formation avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * Réinitialiser le progrès d'un étudiant dans une formation
     */
    public function resetProgress(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à cette formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('error', 'Vous n\'êtes pas inscrit à cette formation.');
        }

        try {
            // Récupérer la première leçon de la formation pour remettre current_lesson_id
            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            // Réinitialiser le progrès à 0
            $formation->learners()->updateExistingPivot($user->id, [
                'status' => 'enrolled',
                'enrolled_at' => now(),
                'current_lesson_id' => $firstLesson?->id,
                'last_seen_at' => now(),
            ]);

            return back()->with('success', 'Le progrès a été réinitialisé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la réinitialisation du progrès.');
        }
    }

    /**
     * Afficher les formations disponibles pour une équipe
     */
    public function availableFormations(Team $team)
    {
        $user = Auth::user();

        // Récupérer les formations disponibles pour cette équipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->with(['chapters.lessons'])
            ->get();

        // Vérifier l'inscription de l'utilisateur à chaque formation
        foreach ($availableFormations as $formation) {
            $formation->is_enrolled = $this->studentFormationService->isEnrolledInFormation($user, $formation, $team);
            $formation->progress = $formation->is_enrolled
                ? $this->studentFormationService->getStudentProgress($user, $formation)
                : null;
        }

        return view('clean.eleve.formations.available', compact(
            'team',
            'availableFormations'
        ));
    }

    /**
     * API endpoint pour récupérer les formations d'un étudiant (pour AJAX)
     */
    public function apiFormations(Team $team, Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('per_page', 15);

        $formations = $this->studentFormationService->paginateFormationCurrentByStudent(
            $team,
            $user,
            $perPage
        );

        return response()->json($formations);
    }

    /**
     * API endpoint pour récupérer la progression d'une formation
     */
    public function apiProgress(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return response()->json([
            'progress' => $progress,
            'formation' => $formationWithProgress,
        ]);
    }

    /**
     * Afficher le contenu d'une leçon
     */
    public function showLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier que la leçon appartient bien au chapitre et à la formation
        if ($lesson->chapter_id !== $chapter->id || $chapter->formation_id !== $formation->id) {
            abort(404, 'Leçon non trouvée.');
        }

        // Vérifier si la leçon est déjà terminée (sauf si c'est la première visite)
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
            // Rediriger vers la formation avec un message d'information
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette leçon est déjà terminée. Vous pouvez passer à la leçon suivante.');
        }

        // Récupérer le contenu de la leçon selon son type
        $lessonContent = null;
        $lessonType = null;

        if ($lesson->lessonable_type === \App\Models\VideoContent::class) {
            $lessonContent = $lesson->lessonable;
            $lessonType = 'video';
        } elseif ($lesson->lessonable_type === \App\Models\TextContent::class) {
            $lessonContent = $lesson->lessonable;
            $lessonType = 'text';
        } elseif ($lesson->lessonable_type === \App\Models\Quiz::class) {
            // Pour les quiz, rediriger directement vers la page de tentative
            return redirect()->route('eleve.lesson.quiz.attempt', [
                $team,
                $formation,
                $chapter,
                $lesson,
            ]);
        }

        if (! $lessonContent) {
            abort(404, 'Contenu de leçon non trouvé.');
        }

        // Démarrer automatiquement la leçon lors de la visite (seulement si pas déjà terminée)
        $this->startLessonAutomatically($team, $formation, $chapter, $lesson);

        // Récupérer la progression de l'étudiant pour cette leçon
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        // Récupérer les leçons précédente et suivante dans le chapitre
        $previousLesson = $chapter->lessons()
            ->where('position', '<', $lesson->position)
            ->orderBy('position', 'desc')
            ->first();

        $nextLesson = $chapter->lessons()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->first();

        // Récupérer les autres chapitres de la formation pour la navigation
        $otherChapters = $formation->chapters()
            ->where('id', '!=', $chapter->id)
            ->orderBy('position')
            ->get();

        return view('clean.eleve.lesson.show', compact(
            'team',
            'formation',
            'chapter',
            'lesson',
            'lessonContent',
            'lessonType',
            'lessonProgress',
            'previousLesson',
            'nextLesson',
            'otherChapters'
        ));
    }

    /**
     * Démarrer automatiquement une leçon lors de la visite
     */
    private function startLessonAutomatically(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Créer ou mettre à jour la progression de l'étudiant pour cette leçon
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'started_at' => now(),
                'last_activity_at' => now(),
                'status' => 'in_progress',
            ],
        ]);
    }

    /**
     * Démarrer une leçon (tracking du temps) - API endpoint
     */
    public function startLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Créer ou mettre à jour la progression de l'étudiant pour cette leçon
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'started_at' => now(),
                'last_activity_at' => now(),
                'status' => 'in_progress',
            ],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Marquer une leçon comme terminée
     */
    public function completeLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Marquer la leçon comme terminée
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'last_activity_at' => now(),
                'status' => 'completed',
            ],
        ]);

        // Mettre à jour la progression globale de la formation
        $this->updateFormationProgress($user, $formation);

        return response()->json(['success' => true]);
    }

    /**
     * Mettre à jour la progression d'une leçon (pour le contenu textuel)
     */
    public function updateProgress(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $readPercent = $request->input('read_percent', 0);

        // Mettre à jour la progression de lecture
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'read_percent' => $readPercent,
                'last_activity_at' => now(),
            ],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Afficher la page de quiz pour un étudiant
     */
    public function attemptQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier que la leçon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouvé.');
        }

        $quiz = $lesson->lessonable;

        // Récupérer les questions du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        // Vérifier si l'étudiant a déjà atteint le nombre maximum de tentatives
        $attempts = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot?->attempts ?? 0;

        if ($attempts >= $quiz->max_attempts && $quiz->max_attempts > 0) {
            // Marquer la leçon comme terminée même si le quiz n'est pas réussi
            // pour permettre à l'étudiant de continuer la formation
            $lesson->learners()->syncWithoutDetaching([
                $user->id => [
                    'completed_at' => now(),
                    'last_activity_at' => now(),
                    'status' => 'completed', // ✅ Marquer comme terminé pour débloquer la progression
                    'attempts' => $attempts,
                ],
            ]);

            // Mettre à jour la progression globale de la formation
            $this->updateFormationProgress($user, $formation);

            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz. Vous pouvez continuer avec la formation.');
        }

        return view('clean.eleve.lesson.quiz', compact(
            'team',
            'formation',
            'chapter',
            'lesson',
            'quiz',
            'questions'
        ));
    }

    /**
     * Soumettre les réponses d'un quiz
     */
    public function submitQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Vérifier que la leçon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            return response()->json(['error' => 'Quiz non trouvé'], 404);
        }

        $quiz = $lesson->lessonable;
        $answers = $request->input('answers', []);

        // Calculer le score
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

        // Enregistrer la tentative
        $attempts = ($lesson->learners()->where('user_id', $user->id)->first()?->pivot?->attempts ?? 0) + 1;

        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'attempts' => $attempts,
                'best_score' => max($lesson->learners()->where('user_id', $user->id)->first()?->pivot?->best_score ?? 0, $score),
                'max_score' => $maxScore,
                'last_activity_at' => now(),
                'completed_at' => now(), // ✅ Marquer comme terminé à chaque tentative
                'status' => 'completed', // ✅ La leçon est terminée (quiz fait)
            ],
        ]);

        // Enregistrer les réponses individuelles
        foreach ($answers as $questionId => $choiceId) {
            \App\Models\QuizAnswer::create([
                'user_id' => $user->id,
                'quiz_question_id' => $questionId,
                'quiz_choice_id' => $choiceId,
                'lesson_id' => $lesson->id,
                'is_correct' => $quiz->quizQuestions()->find($questionId)?->quizChoices()->find($choiceId)?->is_correct ?? false,
            ]);
        }

        // ✅ Mettre à jour la progression globale à chaque soumission de quiz
        $this->updateFormationProgress($user, $formation);

        // Rediriger vers la formation si le quiz est réussi
        if ($passed) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('success', 'Félicitations ! Vous avez réussi le quiz avec un score de ' . round($score, 1) . '%.');
        }

        // Retourner seulement les données nécessaires pour les quiz échoués (pas de vue complète)
        return response()->json([
            'success' => false,
            'passed' => false,
            'can_retry' => true,
            'message' => 'Quiz échoué. Vous pouvez réessayer.',
        ]);
    }

    /**
     * Afficher les résultats d'une tentative de quiz
     */
    public function quizResults(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier que la leçon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouvé.');
        }

        // Vérifier que la tentative appartient à l'utilisateur connecté
        if ($attempt->user_id !== $user->id || $attempt->lesson_id !== $lesson->id) {
            abort(403, 'Tentative non autorisée.');
        }

        $quiz = $lesson->lessonable;

        // Récupérer les réponses de cette tentative
        $answers = $attempt->quizAnswers()->with(['quizQuestion', 'quizChoice'])->get();

        // Récupérer les informations du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        return view('clean.eleve.lesson.quiz-results', compact(
            'team',
            'formation',
            'chapter',
            'lesson',
            'quiz',
            'attempt',
            'answers',
            'questions'
        ));
    }

    /**
     * Mettre à jour la progression globale d'une formation
     */
    private function updateFormationProgress(User $user, Formation $formation)
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

        // Mettre à jour la progression de la formation et le current_lesson_id
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
            }
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et leçons pour trouver la première non terminée
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // Vérifier si cette leçon est terminée
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (!$lessonProgress || $lessonProgress->pivot->status !== 'completed') {
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
