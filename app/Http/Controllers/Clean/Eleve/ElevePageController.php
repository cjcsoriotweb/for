<?php

namespace App\Http\Controllers\Clean\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Quiz;
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
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Exemple 1: Lister les formations actuelles de l'étudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Exemple 2: Paginer les formations (15 par page)
        $formationsPaginees = $this->studentFormationService->paginateFormationCurrentByStudent($team, $user, 10);

        // Exemple 3: Vérifier si l'étudiant est inscrit à une formation spécifique
        $formation = Formation::find(1); // Récupérer une formation spécifique
        $isEnrolled = $this->studentFormationService->isEnrolledInFormation($user, $formation, $team);

        // Exemple 4: Récupérer le progrès d'un étudiant dans une formation
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        // Exemple 5: Récupérer une formation avec les données de progrès
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return view('clean.eleve.home', compact(
            'team',
            'formations',
            'formationsPaginees',
            'isEnrolled',
            'progress',
            'formationWithProgress'
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

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        // Récupérer le progrès détaillé
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        return view('clean.eleve.formation.show', compact(
            'team',
            'formationWithProgress',
            'progress'
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
            $formation->learners()->attach($user->id, [
                'team_id' => $team->id,
                'status' => 'enrolled',
                'progress_percent' => 0,
                'enrolled_at' => now(),
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
            // Réinitialiser le progrès à 0
            $formation->learners()->updateExistingPivot($user->id, [
                'progress_percent' => 0,
                'status' => 'enrolled',
                'enrolled_at' => now(),
            ]);

            return back()->with('success', 'Le progrès a été réinitialisé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la réinitialisation du progrès.');
        }
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
            $lessonContent = $lesson->lessonable;
            $lessonType = 'quiz';
        }

        if (! $lessonContent) {
            abort(404, 'Contenu de leçon non trouvé.');
        }

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
     * Démarrer une leçon (tracking du temps)
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
            return redirect()->route('eleve.lesson.show', [$team, $formation, $chapter, $lesson])
                ->with('error', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz.');
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
                'completed_at' => $passed ? now() : null,
                'status' => $passed ? 'completed' : 'in_progress',
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

        // Si le quiz est réussi, mettre à jour la progression globale
        if ($passed) {
            $this->updateFormationProgress($user, $formation);
        }

        return response()->json([
            'success' => true,
            'score' => $score,
            'passed' => $passed,
            'correct_answers' => $correctAnswers,
            'total_questions' => $totalQuestions,
        ]);
    }

    /**
     * Mettre à jour la progression globale d'une formation
     */
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
}
