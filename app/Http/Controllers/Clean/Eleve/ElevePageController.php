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
use App\Models\FormationCompletionDocument;
use App\Models\TextContent;
use App\Models\TextContentAttachment;
use App\Services\Clean\Account\AccountService;
use App\Services\Formation\StudentFormationService;
use App\Services\FormationEnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ElevePageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
        private readonly StudentFormationService $studentFormationService,
        private readonly FormationEnrollmentService $formationEnrollmentService,
    ) {}

    public function home(Team $team)
    {
        $user = Auth::user();

        // RÃƒÂ©cupÃƒÂ©rer les formations actuelles de l'ÃƒÂ©tudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Ajouter les donnÃƒÂ©es de progression pour chaque formation
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
     * Afficher les dÃƒÂ©tails d'une formation pour un ÃƒÂ©tudiant
     */
    public function showFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier si l'ÃƒÂ©tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'ÃƒÂªtes pas inscrit ÃƒÂ  cette formation.');
        }
        $studentFormationService = $this->studentFormationService;

        // RÃƒÂ©cupÃƒÂ©rer la formation avec le progrÃƒÂ¨s de l'ÃƒÂ©tudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvÃƒÂ©e ou non accessible.');
        }

        // RÃƒÂ©cupÃƒÂ©rer le progrÃƒÂ¨s dÃƒÂ©taillÃƒÂ©
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        $formationDocuments = $formation->completionDocuments()->get();
        $isFormationCompleted = $this->studentFormationService->isFormationCompleted($user, $formation);

        $lessonAttachments = TextContentAttachment::query()
            ->whereHas('textContent.lessonable', function ($lessonQuery) use ($formation) {
                $lessonQuery->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'textContent',
                'textContent.lessonable.chapter',
                'textContent.lessonable.learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                },
            ])
            ->get();

        $lessonResources = $lessonAttachments
            ->groupBy('text_content_id')
            ->map(function ($attachments) {
                /** @var \Illuminate\Support\Collection<int, TextContentAttachment> $attachments */
                $firstAttachment = $attachments->first();
                $textContent = $firstAttachment?->textContent;
                $lesson = $textContent?->lessonable;

                if (! $lesson) {
                    return null;
                }

                $lessonLearner = $lesson->learners->first();
                $isCompleted = optional($lessonLearner?->pivot)->status === 'completed';

                return [
                    'chapter_title' => $lesson->chapter?->title,
                    'chapter_position' => $lesson->chapter?->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'attachments' => $attachments,
                    'is_completed' => $isCompleted,
                ];
            })
            ->filter()
            ->sortBy([
                fn($item) => $item['chapter_position'],
                fn($item) => $item['lesson_position'],
            ])
            ->values();

        return view('clean.eleve.formation.show', compact(
            'team',
            'studentFormationService',
            'formationWithProgress',
            'progress',
            'formationDocuments',
            'lessonResources',
            'isFormationCompleted'
        ));
    }

    /**
     * Afficher la page de fÃƒÂ©licitations pour une formation terminÃƒÂ©e
     */
    public function formationCongratulation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Verifier si l'etudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation.');
        }

        // Verifier si la formation est terminee
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'La formation n\'est pas encore terminee.');
        }

        // Recuperer la formation avec le progrès de l'etudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvee ou non accessible.');
        }

        $formationDocuments = $formation->completionDocuments()->get();

        $lessonAttachments = TextContentAttachment::query()
            ->whereHas('textContent.lessonable', function ($lessonQuery) use ($formation) {
                $lessonQuery->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with(['textContent', 'textContent.lessonable.chapter'])
            ->get();

        $lessonResources = $lessonAttachments
            ->groupBy('text_content_id')
            ->map(function ($attachments) {
                /** @var \Illuminate\Support\Collection<int, TextContentAttachment> $attachments */
                $firstAttachment = $attachments->first();
                $textContent = $firstAttachment?->textContent;
                $lesson = $textContent?->lessonable;

                if (! $lesson) {
                    return null;
                }

                return [
                    'chapter_title' => $lesson->chapter?->title,
                    'chapter_position' => $lesson->chapter?->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'attachments' => $attachments,
                    'is_completed' => true,
                ];
            })
            ->filter()
            ->sortBy([
                fn($item) => $item['chapter_position'],
                fn($item) => $item['lesson_position'],
            ])
            ->values();

        return view('clean.eleve.formation.congratulation', compact(
            'team',
            'formationWithProgress',
            'formationDocuments',
            'lessonResources'
        ));
    }

    public function downloadCompletionDocument(Team $team, Formation $formation, FormationCompletionDocument $document)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous ne pouvez pas acceder a cette formation.');
        }

        if ($document->formation_id !== $formation->id) {
            abort(404);
        }

        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            abort(403, 'La formation doit etre terminee pour acceder au document.');
        }

        $downloadName = $document->title ?: $document->original_name;

        return Storage::disk('public')->download($document->file_path, $downloadName);
    }

    /**
     * Inscrire un etudiant a une formation
     */
    public function enroll(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier si l'ÃƒÂ©tudiant est dÃƒÂ©jÃƒÂ  inscrit
        if ($this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('warning', 'Vous ÃƒÂªtes dÃƒÂ©jÃƒÂ  inscrit ÃƒÂ  cette formation.');
        }

        // VÃƒÂ©rifier si la formation est disponible pour cette ÃƒÂ©quipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->pluck('formations.id');

        if (! $availableFormations->contains($formation->id)) {
            return back()->with('error', 'Cette formation n\'est pas disponible pour votre ÃƒÂ©quipe.');
        }

        if (! $this->formationEnrollmentService->canTeamAffordFormation($team, $formation)) {
            return back()->with('error', 'Le solde de votre ÃƒÂ©quipe est insuffisant pour cette formation.');
        }

        try {
            $enrolled = $this->formationEnrollmentService->enrollUser($formation, $team, $user->id);

            if (! $enrolled) {
                return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
            }

            return back()->with('success', 'Vous avez ÃƒÂ©tÃƒÂ© inscrit ÃƒÂ  la formation avec succÃƒÂ¨s !');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * RÃƒÂ©initialiser le progrÃƒÂ¨s d'un ÃƒÂ©tudiant dans une formation
     */
    public function resetProgress(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier si l'ÃƒÂ©tudiant est inscrit ÃƒÂ  cette formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('error', 'Vous n\'ÃƒÂªtes pas inscrit ÃƒÂ  cette formation.');
        }

        try {
            // RÃƒÂ©cupÃƒÂ©rer la premiÃƒÂ¨re leÃƒÂ§on de la formation pour remettre current_lesson_id
            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            // RÃƒÂ©initialiser le progrÃƒÂ¨s ÃƒÂ  0
            $formation->learners()->updateExistingPivot($user->id, [
                'status' => 'enrolled',
                'enrolled_at' => now(),
                'current_lesson_id' => $firstLesson?->id,
                'last_seen_at' => now(),
            ]);

            return back()->with('success', 'Le progrÃƒÂ¨s a ÃƒÂ©tÃƒÂ© rÃƒÂ©initialisÃƒÂ© avec succÃƒÂ¨s.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la rÃƒÂ©initialisation du progrÃƒÂ¨s.');
        }
    }

    /**
     * Afficher les formations disponibles pour une ÃƒÂ©quipe
     */
    public function availableFormations(Team $team)
    {
        $user = Auth::user();

        // RÃƒÂ©cupÃƒÂ©rer les formations disponibles pour cette ÃƒÂ©quipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->with(['chapters.lessons'])
            ->get();

        // VÃƒÂ©rifier l'inscription de l'utilisateur ÃƒÂ  chaque formation
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
     * API endpoint pour rÃƒÂ©cupÃƒÂ©rer les formations d'un ÃƒÂ©tudiant (pour AJAX)
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
     * API endpoint pour rÃƒÂ©cupÃƒÂ©rer la progression d'une formation
     */
    public function apiProgress(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃƒÂ©'], 403);
        }

        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return response()->json([
            'progress' => $progress,
            'formation' => $formationWithProgress,
        ]);
    }

    /**
     * Afficher le contenu d'une leÃƒÂ§on
     */
    public function showLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier si l'ÃƒÂ©tudiant est inscrit ÃƒÂ  la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'ÃƒÂªtes pas inscrit ÃƒÂ  cette formation.');
        }

        // VÃƒÂ©rifier que la leÃƒÂ§on appartient bien au chapitre et ÃƒÂ  la formation
        if ($lesson->chapter_id !== $chapter->id || $chapter->formation_id !== $formation->id) {
            abort(404, 'LeÃƒÂ§on non trouvÃƒÂ©e.');
        }

        // VÃƒÂ©rifier si la leÃƒÂ§on est dÃƒÂ©jÃƒÂ  terminÃƒÂ©e (sauf si c'est la premiÃƒÂ¨re visite)
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
            // Rediriger vers la formation avec un message d'information
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette leÃƒÂ§on est dÃƒÂ©jÃƒÂ  terminÃƒÂ©e. Vous pouvez passer ÃƒÂ  la leÃƒÂ§on suivante.');
        }

        // RÃƒÂ©cupÃƒÂ©rer le contenu de la leÃƒÂ§on selon son type
        $lessonContent = null;
        $lessonType = null;

        if ($lesson->lessonable_type === \App\Models\VideoContent::class) {
            $lessonContent = $lesson->lessonable;
            $lessonType = 'video';
        } elseif ($lesson->lessonable_type === TextContent::class) {
            $lesson->loadMissing('lessonable.attachments');
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
            abort(404, 'Contenu de leÃƒÂ§on non trouvÃƒÂ©.');
        }

        // DÃƒÂ©marrer automatiquement la leÃƒÂ§on lors de la visite (seulement si pas dÃƒÂ©jÃƒÂ  terminÃƒÂ©e)
        $this->startLessonAutomatically($team, $formation, $chapter, $lesson);

        // RÃƒÂ©cupÃƒÂ©rer la progression de l'ÃƒÂ©tudiant pour cette leÃƒÂ§on
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        // RÃƒÂ©cupÃƒÂ©rer les leÃƒÂ§ons prÃƒÂ©cÃƒÂ©dente et suivante dans le chapitre
        $previousLesson = $chapter->lessons()
            ->where('position', '<', $lesson->position)
            ->orderBy('position', 'desc')
            ->first();

        $nextLesson = $chapter->lessons()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->first();

        // RÃƒÂ©cupÃƒÂ©rer les autres chapitres de la formation pour la navigation
        $otherChapters = $formation->chapters()
            ->where('id', '!=', $chapter->id)
            ->orderBy('position')
            ->get();

        $formationDocuments = $formation->completionDocuments()->get();
        $isFormationCompleted = $this->studentFormationService->isFormationCompleted($user, $formation);

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
            'otherChapters',
            'formationDocuments',
            'isFormationCompleted'
        ));
    }

    /**
     * DÃƒÂ©marrer automatiquement une leÃƒÂ§on lors de la visite
     */
    private function startLessonAutomatically(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // CrÃƒÂ©er ou mettre ÃƒÂ  jour la progression de l'ÃƒÂ©tudiant pour cette leÃƒÂ§on
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'started_at' => now(),
                'last_activity_at' => now(),
                'status' => 'in_progress',
            ],
        ]);
    }

    /**
     * DÃƒÂ©marrer une leÃƒÂ§on (tracking du temps) - API endpoint
     */
    public function startLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃƒÂ©'], 403);
        }

        // CrÃƒÂ©er ou mettre ÃƒÂ  jour la progression de l'ÃƒÂ©tudiant pour cette leÃƒÂ§on
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
     * Marquer une leÃƒÂ§on comme terminÃƒÂ©e
     */
    public function completeLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃƒÂ©'], 403);
        }

        // Marquer la leÃƒÂ§on comme terminÃƒÂ©e
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'last_activity_at' => now(),
                'status' => 'completed',
            ],
        ]);

        // Mettre ÃƒÂ  jour la progression globale de la formation
        $this->updateFormationProgress($user, $formation);

        return response()->json(['success' => true]);
    }

    /**
     * Mettre ÃƒÂ  jour la progression d'une leÃƒÂ§on (pour le contenu textuel)
     */
    public function updateProgress(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃƒÂ©'], 403);
        }

        $readPercent = $request->input('read_percent', 0);

        // Mettre ÃƒÂ  jour la progression de lecture
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'read_percent' => $readPercent,
                'last_activity_at' => now(),
            ],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Afficher la page de quiz pour un ÃƒÂ©tudiant
     */
    public function attemptQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'ÃƒÂªtes pas inscrit ÃƒÂ  cette formation.');
        }

        // VÃƒÂ©rifier que la leÃƒÂ§on est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouvÃƒÂ©.');
        }

        $quiz = $lesson->lessonable;

        // RÃƒÂ©cupÃƒÂ©rer les questions du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        // VÃƒÂ©rifier si l'ÃƒÂ©tudiant a dÃƒÂ©jÃƒÂ  atteint le nombre maximum de tentatives
        $attempts = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot?->attempts ?? 0;

        if ($attempts >= $quiz->max_attempts && $quiz->max_attempts > 0) {
            // Marquer la leÃƒÂ§on comme terminÃƒÂ©e mÃƒÂªme si le quiz n'est pas rÃƒÂ©ussi
            // pour permettre ÃƒÂ  l'ÃƒÂ©tudiant de continuer la formation
            $lesson->learners()->syncWithoutDetaching([
                $user->id => [
                    'completed_at' => now(),
                    'last_activity_at' => now(),
                    'status' => 'completed', // Ã¢Å“â€¦ Marquer comme terminÃƒÂ© pour dÃƒÂ©bloquer la progression
                    'attempts' => $attempts,
                ],
            ]);

            // Mettre ÃƒÂ  jour la progression globale de la formation
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
     * Soumettre les rÃƒÂ©ponses d'un quiz
     */
    public function submitQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃƒÂ©'], 403);
        }

        // VÃƒÂ©rifier que la leÃƒÂ§on est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            return response()->json(['error' => 'Quiz non trouvÃƒÂ©'], 404);
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
        $passingScore = $quiz->passing_score ?? 0;
        $passed = $passingScore > 0 ? $score >= $passingScore : true;

        // CrÃƒÂ©er la tentative de quiz
        $attempt = \App\Models\QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'max_score' => $maxScore,
            'duration_seconds' => 0, // TODO: calculer la durÃƒÂ©e rÃƒÂ©elle si nÃƒÂ©cessaire
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        // Enregistrer la tentative dans la progression de la leÃƒÂ§on
        $attempts = ($lesson->learners()->where('user_id', $user->id)->first()?->pivot?->attempts ?? 0) + 1;

        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'attempts' => $attempts,
                'best_score' => max($lesson->learners()->where('user_id', $user->id)->first()?->pivot?->best_score ?? 0, $score),
                'max_score' => $maxScore,
                'last_activity_at' => now(),
                'completed_at' => now(), // Ã¢Å“â€¦ Marquer comme terminÃƒÂ© ÃƒÂ  chaque tentative
                'status' => 'completed', // Ã¢Å“â€¦ La leÃƒÂ§on est terminÃƒÂ©e (quiz fait)
            ],
        ]);

        // Enregistrer les rÃƒÂ©ponses individuelles
        foreach ($answers as $questionId => $choiceId) {
            \App\Models\QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'choice_id' => $choiceId,
                'is_correct' => $quiz->quizQuestions()->find($questionId)?->quizChoices()->find($choiceId)?->is_correct ?? false,
            ]);
        }

        // Ã¢Å“â€¦ Mettre ÃƒÂ  jour la progression globale ÃƒÂ  chaque soumission de quiz
        $this->updateFormationProgress($user, $formation);

        // Rediriger vers la formation si le quiz est rÃƒÂ©ussi
        if ($passed) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('success', 'FÃƒÂ©licitations ! Vous avez rÃƒÂ©ussi le quiz avec un score de ' . round($score, 1) . '%.');
        }

        // Retourner seulement les donnÃƒÂ©es nÃƒÂ©cessaires pour les quiz ÃƒÂ©chouÃƒÂ©s (pas de vue complÃƒÂ¨te)
        return response()->json([
            'success' => false,
            'passed' => false,
            'can_retry' => true,
            'message' => 'Quiz ÃƒÂ©chouÃƒÂ©. Vous pouvez rÃƒÂ©essayer.',
        ]);
    }

    /**
     * Afficher les rÃƒÂ©sultats d'une tentative de quiz
     */
    public function quizResults(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // VÃƒÂ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'ÃƒÂªtes pas inscrit ÃƒÂ  cette formation.');
        }

        // VÃƒÂ©rifier que la leÃƒÂ§on est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouvÃƒÂ©.');
        }

        // VÃƒÂ©rifier que la tentative appartient ÃƒÂ  l'utilisateur connectÃƒÂ©
        if ($attempt->user_id !== $user->id || $attempt->lesson_id !== $lesson->id) {
            abort(403, 'Tentative non autorisÃƒÂ©e.');
        }

        $quiz = $lesson->lessonable;

        // RÃƒÂ©cupÃƒÂ©rer les rÃƒÂ©ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // RÃƒÂ©cupÃƒÂ©rer les informations du quiz
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
     * Mettre ÃƒÂ  jour la progression globale d'une formation
     */
    private function updateFormationProgress(User $user, Formation $formation)
    {
        // RÃƒÂ©cupÃƒÂ©rer tous les chapitres avec leurs leÃƒÂ§ons et la progression de l'utilisateur
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($query) use ($user) {
                $query->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->get();

        // Calculer la progression basÃƒÂ©e sur les leÃƒÂ§ons terminÃƒÂ©es
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

        // Mettre ÃƒÂ  jour la progression de la formation et le current_lesson_id
        $this->updateCurrentLessonId($user, $formation);

        // Mettre ÃƒÂ  jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Mettre ÃƒÂ  jour le current_lesson_id pour pointer vers la prochaine leÃƒÂ§on non terminÃƒÂ©e
     */
    private function updateCurrentLessonId(User $user, Formation $formation): void
    {
        // RÃƒÂ©cupÃƒÂ©rer la formation avec tous les chapitres et leÃƒÂ§ons ordonnÃƒÂ©s
        $formationWithLessons = $formation->load([
            'chapters' => function ($query) {
                $query->orderBy('position')
                    ->with(['lessons' => function ($lessonQuery) {
                        $lessonQuery->orderBy('position');
                    }]);
            },
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et leÃƒÂ§ons pour trouver la premiÃƒÂ¨re non terminÃƒÂ©e
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // VÃƒÂ©rifier si cette leÃƒÂ§on est terminÃƒÂ©e
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (! $lessonProgress || $lessonProgress->pivot->status !== 'completed') {
                    // Cette leÃƒÂ§on n'est pas terminÃƒÂ©e, c'est la suivante
                    $nextLessonId = $lesson->id;
                    break 2; // Sortir des deux boucles
                }
            }
        }

        // Mettre ÃƒÂ  jour le current_lesson_id dans formation_user
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'current_lesson_id' => $nextLessonId,
                'last_seen_at' => now(),
            ],
        ]);
    }
}

