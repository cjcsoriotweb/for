<?php

namespace App\Http\Controllers\Clean\Eleve;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\FormationCompletionDocument;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Team;
use App\Models\TextContent;
use App\Models\TextContentAttachment;
use App\Models\User;
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

        // RÃ©cupÃ©rer les formations actuelles de l'Ã©tudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Ajouter les donnÃ©es de progression pour chaque formation
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

        return view('in-application.eleve.home', compact(
            'team',
            'formationsWithProgress',
            'formationsPaginees'
        ));
    }

    /**
     * Afficher les dÃ©tails d'une formation pour un Ã©tudiant
     */
    public function showFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // VÃ©rifier si l'Ã©tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        // VÃ©rifier le quiz d'entrÃ©e si la formation en a un
        $entryQuiz = $formation->entryQuiz;
        $entryQuizStatus = null;

        if ($entryQuiz) {
            $formationProgress = $formation->learners()->where('user_id', $user->id)->first();

            if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
                $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
                $passingScore = $entryQuiz->passing_score ?? 80;

                if ($entryScore <= $passingScore) {
                    $entryQuizStatus = 'passed';
                } else {
                    $entryQuizStatus = 'failed';
                }
            } else {
                $entryQuizStatus = 'required';
            }
        }

        $studentFormationService = $this->studentFormationService;

        // RÃ©cupÃ©rer la formation avec le progrÃ¨s de l'Ã©tudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvÃ©e ou non accessible.');
        }

        // RÃ©cupÃ©rer le progrÃ¨s dÃ©taillÃ©
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
                fn ($item) => $item['chapter_position'],
                fn ($item) => $item['lesson_position'],
            ])
            ->values();

        $assistantTrainer = $formationWithProgress->category?->aiTrainer;
        $assistantTrainerSlug = $assistantTrainer?->slug ?: config('ai.default_trainer_slug', 'default');
        $assistantTrainerName = $assistantTrainer?->name ?: __('Assistant Formation');

        return view('in-application.eleve.formation.show', [
            'team' => $team,
            'studentFormationService' => $studentFormationService,
            'formationWithProgress' => $formationWithProgress,
            'progress' => $progress,
            'formationDocuments' => $formationDocuments,
            'lessonResources' => $lessonResources,
            'isFormationCompleted' => $isFormationCompleted,
            'assistantTrainerSlug' => $assistantTrainerSlug,
            'assistantTrainerName' => $assistantTrainerName,
            'entryQuizStatus' => $entryQuizStatus,
        ]);
    }

    /**
     * Afficher la page de fÃ©licitations pour une formation terminÃ©e
     */
    public function formationCongratulation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Verifier si l'etudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit Ã  cette formation.');
        }

        // Marquer la formation comme terminÃ©e (forcer le statut completed)
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'status' => 'completed',
                'completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // Recuperer la formation avec le progrÃ¨s de l'etudiant
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
                fn ($item) => $item['chapter_position'],
                fn ($item) => $item['lesson_position'],
            ])
            ->values();

        return view('in-application.eleve.formation.congratulation', compact(
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

        // VÃ©rifier si l'Ã©tudiant est dÃ©jÃ  inscrit
        if ($this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('warning', 'Vous Ãªtes dÃ©jÃ  inscrit Ã  cette formation.');
        }

        // VÃ©rifier si la formation est disponible pour cette Ã©quipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->pluck('formations.id');

        if (! $availableFormations->contains($formation->id)) {
            return back()->with('error', 'Cette formation n\'est pas disponible pour votre Ã©quipe.');
        }

        if (! $this->formationEnrollmentService->canTeamAffordFormation($team, $formation)) {
            return back()->with('error', 'Le solde de votre Ã©quipe est insuffisant pour cette formation.');
        }

        try {
            $enrolled = $this->formationEnrollmentService->enrollUser($formation, $team, $user->id);

            if (! $enrolled) {
                return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
            }

            // Rediriger vers le quiz d'entrÃ©e si la formation en a un
            $entryQuiz = $formation->entryQuiz;
            if ($entryQuiz) {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('info', 'Bienvenue dans cette formation ! Veuillez d\'abord passer le quiz d\'entrÃ©e.');
            }

            return back()->with('success', 'Vous avez Ã©tÃ© inscrit Ã  la formation avec succÃ¨s !');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * RÃ©initialiser le progrÃ¨s d'un Ã©tudiant dans une formation
     */
    public function resetProgress(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // VÃ©rifier si l'Ã©tudiant est inscrit Ã  cette formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('error', 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        try {
            // RÃ©cupÃ©rer la premiÃ¨re leÃ§on de la formation pour remettre current_lesson_id
            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            // RÃ©initialiser le progrÃ¨s Ã  0
            $formation->learners()->updateExistingPivot($user->id, [
                'status' => 'enrolled',
                'enrolled_at' => now(),
                'current_lesson_id' => $firstLesson?->id,
                'last_seen_at' => now(),
            ]);

            return back()->with('success', 'Le progrÃ¨s a Ã©tÃ© rÃ©initialisÃ© avec succÃ¨s.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la rÃ©initialisation du progrÃ¨s.');
        }
    }

    /**
     * API endpoint pour rÃ©cupÃ©rer les formations d'un Ã©tudiant (pour AJAX)
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
     * API endpoint pour rÃ©cupÃ©rer la progression d'une formation
     */
    public function apiProgress(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // VÃ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃ©'], 403);
        }

        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return response()->json([
            'progress' => $progress,
            'formation' => $formationWithProgress,
        ]);
    }

    /**
     * Afficher le contenu d'une leÃ§on
     */
    public function showLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃ©rifier si l'Ã©tudiant est inscrit Ã  la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        // VÃ©rifier que la leÃ§on appartient bien au chapitre et Ã  la formation
        if ($lesson->chapter_id !== $chapter->id || $chapter->formation_id !== $formation->id) {
            abort(404, 'LeÃ§on non trouvÃ©e.');
        }

        // VÃ©rifier le quiz d'entrÃ©e si la formation en a un
        $entryQuiz = $formation->entryQuiz;
        if ($entryQuiz) {
            $formationProgress = $formation->learners()->where('user_id', $user->id)->first();

            if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
                $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
                $passingScore = $entryQuiz->passing_score ?? 80;

                if ($entryScore > $passingScore) {
                    return redirect()->route('eleve.formation.show', [$team, $formation])
                        ->with('error', 'Votre niveau est trop Ã©levÃ© pour cette formation. Un superadmin vous contactera pour vous proposer une formation plus adaptÃ©e.');
                }
            } else {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('warning', 'Vous devez d\'abord passer le quiz d\'entrÃ©e pour accÃ©der Ã  cette formation.');
            }
        }

        // VÃ©rifier si la leÃ§on est dÃ©jÃ  terminÃ©e (sauf si c'est la premiÃ¨re visite)
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
            // Rediriger vers la formation avec un message d'information
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette leÃ§on est dÃ©jÃ  terminÃ©e. Vous pouvez passer Ã  la leÃ§on suivante.');
        }

        // RÃ©cupÃ©rer le contenu de la leÃ§on selon son type
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
            abort(404, 'Contenu de leÃ§on non trouvÃ©.');
        }

        // DÃ©marrer automatiquement la leÃ§on lors de la visite (seulement si pas dÃ©jÃ  terminÃ©e)
        $this->startLessonAutomatically($team, $formation, $chapter, $lesson);

        // RÃ©cupÃ©rer la progression de l'Ã©tudiant pour cette leÃ§on
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        // RÃ©cupÃ©rer les leÃ§ons prÃ©cÃ©dente et suivante dans le chapitre
        $previousLesson = $chapter->lessons()
            ->where('position', '<', $lesson->position)
            ->orderBy('position', 'desc')
            ->first();

        $nextLesson = $chapter->lessons()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->first();

        // RÃ©cupÃ©rer les autres chapitres de la formation pour la navigation
        $otherChapters = $formation->chapters()
            ->where('id', '!=', $chapter->id)
            ->orderBy('position')
            ->get();

        $formationDocuments = $formation->completionDocuments()->get();
        $isFormationCompleted = $this->studentFormationService->isFormationCompleted($user, $formation);

        $formation->loadMissing('category.aiTrainer');
        $assistantTrainer = $formation->category?->aiTrainer;
        $assistantTrainerSlug = $assistantTrainer?->slug ?: config('ai.default_trainer_slug', 'default');
        $assistantTrainerName = $assistantTrainer?->name ?: __('Assistant Formation');

        return view('in-application.eleve.lesson.show', [
            'team' => $team,
            'formation' => $formation,
            'chapter' => $chapter,
            'lesson' => $lesson,
            'lessonContent' => $lessonContent,
            'lessonType' => $lessonType,
            'lessonProgress' => $lessonProgress,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'otherChapters' => $otherChapters,
            'formationDocuments' => $formationDocuments,
            'isFormationCompleted' => $isFormationCompleted,
            'assistantTrainerSlug' => $assistantTrainerSlug,
            'assistantTrainerName' => $assistantTrainerName,
        ]);
    }

    /**
     * DÃ©marrer automatiquement une leÃ§on lors de la visite
     */
    private function startLessonAutomatically(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // CrÃ©er ou mettre Ã  jour la progression de l'Ã©tudiant pour cette leÃ§on
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'started_at' => now(),
                'last_activity_at' => now(),
                'status' => 'in_progress',
            ],
        ]);
    }

    /**
     * DÃ©marrer une leÃ§on (tracking du temps) - API endpoint
     */
    public function startLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃ©'], 403);
        }

        // CrÃ©er ou mettre Ã  jour la progression de l'Ã©tudiant pour cette leÃ§on
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
     * Marquer une leÃ§on comme terminÃ©e
     */
    public function completeLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃ©'], 403);
        }

        // Marquer la leÃ§on comme terminÃ©e
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'last_activity_at' => now(),
                'status' => 'completed',
            ],
        ]);

        // Mettre Ã  jour la progression globale de la formation
        $this->updateFormationProgress($user, $formation);

        return response()->json(['success' => true]);
    }

    /**
     * Mettre Ã  jour la progression d'une leÃ§on (pour le contenu textuel)
     */
    public function updateProgress(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // VÃ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃ©'], 403);
        }

        $readPercent = $request->input('read_percent', 0);

        // Mettre Ã  jour la progression de lecture
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'read_percent' => $readPercent,
                'last_activity_at' => now(),
            ],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Afficher la page de quiz pour un Ã©tudiant
     */
    public function attemptQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // VÃ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        // VÃ©rifier que la leÃ§on est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouvÃ©.');
        }

        $quiz = $lesson->lessonable;

        // RÃ©cupÃ©rer les questions du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        // VÃ©rifier si l'Ã©tudiant a dÃ©jÃ  atteint le nombre maximum de tentatives
        $attempts = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot?->attempts ?? 0;

        if ($attempts >= $quiz->max_attempts && $quiz->max_attempts > 0) {
            // Marquer la leÃ§on comme terminÃ©e mÃªme si le quiz n'est pas rÃ©ussi
            // pour permettre Ã  l'Ã©tudiant de continuer la formation
            $lesson->learners()->syncWithoutDetaching([
                $user->id => [
                    'completed_at' => now(),
                    'last_activity_at' => now(),
                    'status' => 'completed', // âœ… Marquer comme terminÃ© pour dÃ©bloquer la progression
                    'attempts' => $attempts,
                ],
            ]);

            // Mettre Ã  jour la progression globale de la formation
            $this->updateFormationProgress($user, $formation);

            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz. Vous pouvez continuer avec la formation.');
        }

        return view('in-application.eleve.lesson.quiz', compact(
            'team',
            'formation',
            'chapter',
            'lesson',
            'quiz',
            'questions'
        ));
    }

    /**
     * Soumettre les rÃ©ponses d'un quiz
     */

    public function submitQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            return response()->json(['error' => 'Quiz non trouvé'], 404);
        }

        $quiz = $lesson->lessonable;
        $answers = $request->input('answers', []);

        $questions = $quiz->quizQuestions()
            ->with(['quizChoices' => function ($query) {
                $query->select('id', 'quiz_question_id', 'is_correct');
            }])
            ->get()
            ->keyBy('id');

        $totalQuestions = $questions->count();
        $correctAnswers = 0;
        $earnedPoints = 0;
        $maxScore = max(0, (int) $questions->sum('points'));

        foreach ($questions as $questionId => $question) {
            if (! isset($answers[$questionId])) {
                continue;
            }

            $userChoiceId = (int) $answers[$questionId];
            $selectedChoice = $question->quizChoices->firstWhere('id', $userChoiceId);
            $correctChoice = $question->quizChoices->firstWhere('is_correct', true);

            if ($selectedChoice && $correctChoice && $selectedChoice->id === $correctChoice->id) {
                $correctAnswers++;
                $earnedPoints += (int) $question->points;
            }
        }

        $score = $maxScore > 0
            ? ($earnedPoints / $maxScore) * 100
            : ($totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0);
        $passingScore = $quiz->passing_score ?? 0;
        $passed = $passingScore > 0 ? $score >= $passingScore : true;

        $attempt = \App\Models\QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'max_score' => $maxScore,
            'duration_seconds' => 0,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        $existingPivot = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot;

        $attempts = ($existingPivot?->attempts ?? 0) + 1;
        $bestScore = max($existingPivot?->best_score ?? 0, $score);

        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'attempts' => $attempts,
                'best_score' => $bestScore,
                'max_score' => $maxScore,
                'last_activity_at' => now(),
                'completed_at' => now(),
                'status' => 'completed',
            ],
        ]);

        $now = now();
        $rows = [];

        foreach ($answers as $questionId => $choiceId) {
            $question = $questions->get((int) $questionId);
            if (! $question) {
                continue;
            }

            $selectedChoice = $question->quizChoices->firstWhere('id', (int) $choiceId);

            $rows[] = [
                'quiz_attempt_id' => $attempt->id,
                'question_id' => (int) $questionId,
                'choice_id' => (int) $choiceId,
                'is_correct' => (bool) ($selectedChoice?->is_correct),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($rows)) {
            \App\Models\QuizAnswer::query()->insert($rows);
        }

        $this->updateFormationProgress($user, $formation);

        if ($passed) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('success', 'Félicitations ! Vous avez réussi le quiz avec un score de '.round($score, 1).'%.' );
        }

        return response()->json([
            'success' => false,
            'passed' => false,
            'can_retry' => true,
            'message' => 'Quiz échoué. Vous pouvez réessayer.',
        ]);
    }

    /**
     * Afficher les rÃ©sultats d'une tentative de quiz
     */
    public function quizResults(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // VÃ©rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        // VÃ©rifier que la leÃ§on est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouvÃ©.');
        }

        // VÃ©rifier que la tentative appartient Ã  l'utilisateur connectÃ©
        if ($attempt->user_id !== $user->id || $attempt->lesson_id !== $lesson->id) {
            abort(403, 'Tentative non autorisÃ©e.');
        }

        $quiz = $lesson->lessonable;

        // RÃ©cupÃ©rer les rÃ©ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // RÃ©cupÃ©rer les informations du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        return view('in-application.eleve.lesson.quiz-results', compact(
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
     * Mettre Ã  jour la progression globale d'une formation
     */
    private function updateFormationProgress(User $user, Formation $formation)
    {
        // RÃ©cupÃ©rer tous les chapitres avec leurs leÃ§ons et la progression de l'utilisateur
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($query) use ($user) {
                $query->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->get();

        // Calculer la progression basÃ©e sur les leÃ§ons terminÃ©es
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

        // Mettre Ã  jour la progression de la formation et le current_lesson_id
        $this->updateCurrentLessonId($user, $formation);

        // Mettre Ã  jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Mettre Ã  jour le current_lesson_id pour pointer vers la prochaine leÃ§on non terminÃ©e
     */
    private function updateCurrentLessonId(User $user, Formation $formation): void
    {
        // RÃ©cupÃ©rer la formation avec tous les chapitres et leÃ§ons ordonnÃ©s
        $formationWithLessons = $formation->load([
            'chapters' => function ($query) {
                $query->orderBy('position')
                    ->with(['lessons' => function ($lessonQuery) {
                        $lessonQuery->orderBy('position');
                    }]);
            },
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et leÃ§ons pour trouver la premiÃ¨re non terminÃ©e
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // VÃ©rifier si cette leÃ§on est terminÃ©e
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (! $lessonProgress || $lessonProgress->pivot->status !== 'completed') {
                    // Cette leÃ§on n'est pas terminÃ©e, c'est la suivante
                    $nextLessonId = $lesson->id;
                    break 2; // Sortir des deux boucles
                }
            }
        }

        // Mettre Ã  jour le current_lesson_id dans formation_user
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'current_lesson_id' => $nextLessonId,
                'last_seen_at' => now(),
            ],
        ]);
    }

    /**
     * Afficher le quiz d'entrÃ©e pour un Ã©tudiant
     */
    public function attemptEntryQuiz(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // VÃ©rifier si l'Ã©tudiant est inscrit Ã  la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        // VÃ©rifier si la formation a un quiz d'entrÃ©e
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette formation n\'a pas de quiz d\'entrÃ©e.');
        }

        // VÃ©rifier si l'Ã©tudiant a dÃ©jÃ  passÃ© le quiz d'entrÃ©e
        $formationProgress = $formation->learners()->where('user_id', $user->id)->first();
        if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
            $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
            $passingScore = $entryQuiz->passing_score ?? 80;

            if ($entryScore <= $passingScore) {
                return redirect()->route('eleve.formation.show', [$team, $formation])
                    ->with('success', 'Vous avez dÃ©jÃ  passÃ© le quiz d\'entrÃ©e avec succÃ¨s. Vous pouvez commencer la formation.');
            } else {
                return redirect()->route('eleve.formation.show', [$team, $formation])
                    ->with('error', 'Votre niveau est trop Ã©levÃ© pour cette formation. Un superadmin vous contactera pour vous proposer une formation plus adaptÃ©e.');
            }
        }

        // RÃ©cupÃ©rer les questions du quiz
        $questions = $entryQuiz->quizQuestions()->with('quizChoices')->get();

        return view('in-application.eleve.formation.entry-quiz', compact(
            'team',
            'formation',
            'entryQuiz',
            'questions'
        ));
    }

    /**
     * Soumettre les rÃ©ponses du quiz d'entrÃ©e
     */
    public function submitEntryQuiz(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // VÃ©rifier si l'Ã©tudiant est inscrit Ã  la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisÃ©'], 403);
        }

        // VÃ©rifier si la formation a un quiz d'entrÃ©e
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return response()->json(['error' => 'Quiz d\'entrÃ©e non trouvÃ©'], 404);
        }

        $answers = $request->input('answers', []);

        // Utiliser le QuizService pour soumettre le quiz
        $quizService = app(\App\Services\Quiz\QuizService::class);
        $result = $quizService->submitFormationQuiz($user, $entryQuiz, $answers, \App\Models\QuizAttempt::TYPE_PRE);

        // Mettre Ã  jour la progression de l'Ã©tudiant dans la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'entry_quiz_attempt_id' => $result['attempt_id'],
                'entry_quiz_score' => $result['score'],
                'entry_quiz_completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // VÃ©rifier si l'Ã©tudiant a rÃ©ussi le quiz
        $passingScore = $entryQuiz->passing_score ?? 80;
        if ($result['score'] <= $passingScore) {
            // L'Ã©tudiant peut continuer la formation - niveau adaptÃ©
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('success', 'FÃ©licitations ! Votre niveau correspond parfaitement Ã  cette formation (score: '.round($result['score'], 1).'%). Vous pouvez maintenant commencer.');
        } else {
            // L'Ã©tudiant ne peut pas continuer - niveau trop Ã©levÃ©
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('error', 'Votre niveau est trop Ã©levÃ© pour cette formation (score: '.round($result['score'], 1).'%). Un superadmin vous contactera pour vous proposer une formation plus adaptÃ©e.');
        }
    }

    /**
     * Afficher les rÃ©sultats du quiz d'entrÃ©e
     */
    public function entryQuizResults(Team $team, Formation $formation, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // VÃ©rifier si l'Ã©tudiant est inscrit Ã  la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'Ãªtes pas inscrit Ã  cette formation.');
        }

        // VÃ©rifier que la tentative appartient Ã  l'utilisateur connectÃ©
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Tentative non autorisÃ©e.');
        }

        // VÃ©rifier que c'est bien une tentative de quiz d'entrÃ©e
        if ($attempt->attempt_type !== \App\Models\QuizAttempt::TYPE_PRE) {
            abort(404, 'RÃ©sultats non trouvÃ©s.');
        }

        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz || $attempt->quiz_id !== $entryQuiz->id) {
            abort(404, 'Quiz non trouvÃ©.');
        }

        // RÃ©cupÃ©rer les rÃ©ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // RÃ©cupÃ©rer les informations du quiz
        $questions = $entryQuiz->quizQuestions()->with('quizChoices')->get();

        return view('in-application.eleve.formation.entry-quiz-results', compact(
            'team',
            'formation',
            'entryQuiz',
            'attempt',
            'answers',
            'questions'
        ));
    }
}
