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
use App\Models\LessonResource;
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

    /**
     * Vérifie si le seuil de passage du quiz d'entrée est valide
     * Le seuil doit être strictement entre 0% et 100%
     */
    private function resolveEntryQuizThresholds(Quiz $entryQuiz): array
    {
        $minScore = $entryQuiz->entry_min_score ?? 0;
        $maxScore = $entryQuiz->entry_max_score ?? ($entryQuiz->passing_score ?? 100);

        $minScore = max(0, min(100, (int) $minScore));
        $maxScore = max(0, min(100, (int) $maxScore));

        if ($minScore > $maxScore) {
            [$minScore, $maxScore] = [$maxScore, $minScore];
        }

        return [$minScore, $maxScore];
    }

    private function determineEntryQuizStatus(float $score, int $minScore, int $maxScore): string
    {
        if ($score < $minScore) {
            return 'too_low';
        }

        if ($score > $maxScore) {
            return 'too_high';
        }

        return 'passed';
    }

    public function home(Team $team)
    {
        $user = Auth::user();

        // Récupérer les formations actuelles de l'étudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Récupérer le nombre de formations disponibles pour l'équipe
        $availableFormationsCount = $this->studentFormationService->listAvailableFormationsForTeam($team)->count();

        // Ajouter les données de progression pour chaque formation
        $formationsWithProgress = $formations->map(function ($formation) use ($user, $team) {
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

            // Ajouter les informations de validation pour les formations terminées
            if ($formation->is_completed) {
                $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
                    ->where('user_id', $user->id)
                    ->where('team_id', $team->id)
                    ->first();

                $formation->validation_status = $formationUser ? $formationUser->completion_request_status : null;
                $formation->is_validated = $formationUser && $formationUser->completion_request_status === 'approved';
                $formation->is_pending_validation = $formationUser && $formationUser->completion_request_status === 'pending';
            } else {
                $formation->validation_status = null;
                $formation->is_validated = false;
                $formation->is_pending_validation = false;
            }

            return $formation;
        });

        // Paginer les formations pour l'API
        $formationsPaginees = $this->studentFormationService->paginateFormationCurrentByStudent($team, $user, 10);

        $teamManagers = $team->users()
            ->select('users.*')
            ->wherePivot('role', 'manager')
            ->orderBy('users.name')
            ->get();

        return view('in-application.eleve.home', compact(
            'team',
            'formationsWithProgress',
            'formationsPaginees',
            'availableFormationsCount',
            'teamManagers'
        ));
    }

    public function showManager(Team $team, User $manager)
    {
        $user = Auth::user();

        if (! $user || ! $user->belongsToTeam($team)) {
            abort(403, __("Vous n'avez pas acc\u00e8s \u00e0 cette \u00e9quipe."));
        }

        if (! $manager->belongsToTeam($team) || ! $manager->hasTeamRole($team, 'manager')) {
            abort(404, __("Ce manager n'est pas associ\u00e9 \u00e0 cette \u00e9quipe."));
        }

        $managerTeamPivot = $manager->teams()
            ->where('team_id', $team->id)
            ->first()?->pivot;

        return view('in-application.eleve.manager.show', [
            'team' => $team,
            'manager' => $manager,
            'managerTeamPivot' => $managerTeamPivot,
        ]);
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

        // Vérifier le quiz d'entrée si la formation en a un
        $entryQuiz = $formation->entryQuiz;
        $entryQuizStatus = null;

        if ($entryQuiz) {
            $formationProgress = $formation->learners()->where('user_id', $user->id)->first();

            if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
                $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
            [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);
                $entryQuizStatus = $this->determineEntryQuizStatus($entryScore, $minScore, $maxScore);
            } else {
                $entryQuizStatus = 'required';
            }
        }

        $studentFormationService = $this->studentFormationService;

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        // Récupérer le progrès détaillé
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        $formationDocuments = $formation->completionDocuments()->get();
        $isFormationCompleted = $this->studentFormationService->isFormationCompleted($user, $formation);

        $lessonResources = LessonResource::query()
            ->whereHas('lesson.chapter', fn ($chapterQuery) => $chapterQuery->where('formation_id', $formation->id))
            ->with([
                'lesson.chapter',
                'lesson.learners' => fn ($learnerQuery) => $learnerQuery->where('user_id', $user->id),
            ])
            ->get()
            ->groupBy('lesson_id')
            ->map(function ($resources) {
                /** @var \Illuminate\Support\Collection<int, LessonResource> $resources */
                $lesson = optional($resources->first())->lesson;

                if (! $lesson) {
                    return null;
                }

                $lessonLearner = $lesson->learners->first();
                $lessonStatus = optional($lessonLearner?->pivot)->status;
                $isCompleted = $lessonStatus === 'completed';
                $isInProgress = $lessonStatus === 'in_progress';
                $canDownloadResources = $isCompleted || $isInProgress;

                return [
                    'chapter_title' => $lesson->chapter?->title,
                    'chapter_position' => $lesson->chapter?->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'attachments' => $resources,
                    'is_completed' => $isCompleted,
                    'is_in_progress' => $isInProgress,
                    'can_download_resources' => $canDownloadResources,
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
     * Afficher la page dédiée aux formations terminées
     */
    public function showCompletedFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation est terminée
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore terminée.');
        }

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        // Récupérer le progrès détaillé
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        $formationDocuments = $formation->completionDocuments()->get();

        // Récupérer toutes les leçons de la formation avec leur statut
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($lessonQuery) use ($user) {
                $lessonQuery->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }])->with('resources');
            }])
            ->orderBy('position')
            ->get();

        $lessonResources = collect();
        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonLearner = $lesson->learners->first();
                $isCompleted = optional($lessonLearner?->pivot)->status === 'completed';
                $attachments = $lesson->resources ?? collect();

                $lessonResources->push([
                    'chapter_title' => $chapter->title,
                    'chapter_position' => $chapter->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'lesson_type' => $lesson->lessonable_type,
                    'attachments' => $attachments,
                    'is_completed' => $isCompleted,
                    'completed_at' => optional($lessonLearner?->pivot)->completed_at,
                ]);
            }
        }

        $lessonResources = $lessonResources->sortBy([
            fn ($item) => $item['chapter_position'],
            fn ($item) => $item['lesson_position'],
        ])->values();

        // Grouper par chapitre pour un meilleur affichage
        $chaptersWithLessons = $lessonResources->groupBy('chapter_title')->map(function ($lessons, $chapterTitle) {
            return [
                'title' => $chapterTitle,
                'lessons' => $lessons,
                'completed_count' => $lessons->where('is_completed', true)->count(),
                'total_count' => $lessons->count(),
            ];
        });

        $assistantTrainer = $formationWithProgress->category?->aiTrainer;
        $assistantTrainerSlug = $assistantTrainer?->slug ?: config('ai.default_trainer_slug', 'default');
        $assistantTrainerName = $assistantTrainer?->name ?: __('Assistant Formation');

        // Récupérer les données de complétion de la formation
        $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->with(['completionValidatedBy'])
            ->first();

        return view('in-application.eleve.formation.completed', [
            'team' => $team,
            'formationWithProgress' => $formationWithProgress,
            'progress' => $progress,
            'formationDocuments' => $formationDocuments,
            'chaptersWithLessons' => $chaptersWithLessons,
            'lessonResources' => $lessonResources,
            'assistantTrainer' => $assistantTrainer,
            'assistantTrainerSlug' => $assistantTrainerSlug,
            'assistantTrainerName' => $assistantTrainerName,
            'formationUser' => $formationUser,
        ]);
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

        // Marquer la formation comme terminée (forcer le statut completed et progression à 100 %)
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'status' => 'completed',
                'progress_percent' => 100,
                'completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // Récupérer la formation avec le progrès de l'étudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouvée ou non accessible.');
        }

        $formationDocuments = $formation->completionDocuments()->get();

        $lessonResources = LessonResource::query()
            ->whereHas('lesson.chapter', fn ($chapterQuery) => $chapterQuery->where('formation_id', $formation->id))
            ->with(['lesson.chapter'])
            ->get()
            ->groupBy('lesson_id')
            ->map(function ($resources) {
                /** @var \Illuminate\Support\Collection<int, LessonResource> $resources */
                $lesson = optional($resources->first())->lesson;

                if (! $lesson) {
                    return null;
                }

                return [
                    'chapter_title' => $lesson->chapter?->title,
                    'chapter_position' => $lesson->chapter?->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'attachments' => $resources,
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

    public function downloadCompletionDocument(Team $team, Formation $formation, $documentIdentifier)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous ne pouvez pas accéder à cette formation.');
        }

        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            abort(403, 'La formation doit être terminée pour accéder au document.');
        }

        // Vérifier si c'est un document de formation standard
        if (is_numeric($documentIdentifier) || $documentIdentifier instanceof FormationCompletionDocument) {
            $document = $documentIdentifier instanceof FormationCompletionDocument ? $documentIdentifier : FormationCompletionDocument::findOrFail($documentIdentifier);

            if ($document->formation_id !== $formation->id) {
                abort(404);
            }

            $downloadName = $document->title ?: $document->original_name;

            return \Illuminate\Support\Facades\Storage::disk('public')->download($document->file_path, $downloadName);
        }

        // Vérifier si c'est un document joint de validation (format: completion-{index})
        if (str_starts_with($documentIdentifier, 'completion-')) {
            $index = (int) str_replace('completion-', '', $documentIdentifier);

            $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
                ->where('user_id', $user->id)
                ->where('team_id', $team->id)
                ->first();

            if (! $formationUser || ! $formationUser->completion_documents || ! isset($formationUser->completion_documents[$index])) {
                abort(404, 'Document non trouvé.');
            }

            $document = $formationUser->completion_documents[$index];

            if (! Storage::disk('public')->exists($document['path'])) {
                abort(404, 'Fichier non trouvé sur le serveur.');
            }

            return \Illuminate\Support\Facades\Storage::disk('public')->download($document['path'], $document['original_name']);
        }

        abort(404, 'Document non trouvé.');
    }

    /**
     * Demander la validation de fin de formation
     */
    public function requestCompletion(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation est terminée
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return back()->with('error', 'Vous devez d\'abord terminer la formation avant de demander sa validation.');
        }

        // Récupérer ou créer l'enregistrement FormationUser
        $formationUser = \App\Models\FormationUser::firstOrNew([
            'formation_id' => $formation->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // Vérifier si une demande n'a pas déjà été faite
        if ($formationUser->completion_request_at && $formationUser->completion_request_status === 'pending') {
            return back()->with('warning', 'Une demande de validation est déjà en cours de traitement.');
        }

        // Créer la demande de validation
        $formationUser->completion_request_at = now();
        $formationUser->completion_request_status = 'pending';
        $formationUser->save();

        return back()->with('success', 'Votre demande de validation de fin de formation a été envoyée avec succès. Un superadmin la traitera dans les plus brefs délais.');
    }

    /**
     * Télécharger la page de formation terminée en PDF
     */
    public function downloadCompletedFormationPdf(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation est terminée
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore terminée.');
        }

        // Récupérer les données nécessaires pour le PDF
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationDocuments = $formation->completionDocuments()->get();

        // Récupérer toutes les leçons de la formation avec leur statut
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($lessonQuery) use ($user) {
                $lessonQuery->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }])->with('resources');
            }])
            ->orderBy('position')
            ->get();

        $lessonResources = collect();
        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonLearner = $lesson->learners->first();
                $isCompleted = optional($lessonLearner?->pivot)->status === 'completed';
                $attachments = $lesson->resources ?? collect();

                $lessonResources->push([
                    'chapter_title' => $chapter->title,
                    'chapter_position' => $chapter->position ?? 0,
                    'lesson_id' => $lesson->id,
                    'lesson_title' => $lesson->title,
                    'lesson_position' => $lesson->position ?? 0,
                    'lesson_type' => $lesson->lessonable_type,
                    'attachments' => $attachments,
                    'is_completed' => $isCompleted,
                    'completed_at' => optional($lessonLearner?->pivot)->completed_at,
                ]);
            }
        }

        $lessonResources = $lessonResources->sortBy([
            fn ($item) => $item['chapter_position'],
            fn ($item) => $item['lesson_position'],
        ])->values();

        // Grouper par chapitre pour un meilleur affichage
        $chaptersWithLessons = $lessonResources->groupBy('chapter_title')->map(function ($lessons, $chapterTitle) {
            return [
                'title' => $chapterTitle,
                'lessons' => $lessons,
                'completed_count' => $lessons->where('is_completed', true)->count(),
                'total_count' => $lessons->count(),
            ];
        });

        $assistantTrainer = $formationWithProgress->category?->aiTrainer;
        $assistantTrainerSlug = $assistantTrainer?->slug ?: config('ai.default_trainer_slug', 'default');
        $assistantTrainerName = $assistantTrainer?->name ?: __('Assistant Formation');

        // Récupérer les données de complétion de la formation
        $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->with(['completionValidatedBy'])
            ->first();

        // Générer le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.formation-completed', [
            'team' => $team,
            'formationWithProgress' => $formationWithProgress,
            'progress' => $progress,
            'formationDocuments' => $formationDocuments,
            'chaptersWithLessons' => $chaptersWithLessons,
            'lessonResources' => $lessonResources,
            'assistantTrainer' => $assistantTrainer,
            'assistantTrainerSlug' => $assistantTrainerSlug,
            'assistantTrainerName' => $assistantTrainerName,
            'formationUser' => $formationUser,
            'user' => $user,
        ]);

        // Configuration du PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        // Nom du fichier
        $filename = 'formation-'.$formation->id.'-certificat-'.$user->id.'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Télécharger le rapport de connexion de l'étudiant pour cette formation en PDF
     */
    public function downloadConnectionReportPdf(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation est terminée
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore terminée.');
        }

        // Récupérer les données de la formation
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        // Récupérer les données de complétion de la formation
        $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->first();

        // Récupérer les logs d'activité pour cette formation
        $startDate = $formationUser ? $formationUser->enrolled_at : now()->subMonths(6);
        $endDate = $formationUser ? ($formationUser->completed_at ?? now()) : now();

        $activityLogs = \App\Models\UserActivityLog::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function ($query) use ($formation) {
                // Filtrer les activités liées à cette formation
                $query->where('url', 'like', '%/eleve/%/formations/'.$formation->id.'%')
                    ->orWhere('url', 'like', '%/formation/'.$formation->id.'%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouper les activités par jour
        $dailyActivities = $activityLogs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        })->map(function ($logs, $date) {
            $totalDuration = $logs->sum('duration_seconds');
            $sessionCount = $logs->unique('session_id')->count();

            return [
                'date' => $date,
                'formatted_date' => \Carbon\Carbon::parse($date)->format('d/m/Y'),
                'total_duration' => $totalDuration,
                'formatted_duration' => $this->formatDuration($totalDuration),
                'session_count' => $sessionCount,
                'activities' => $logs->take(10), // Limiter à 10 activités par jour pour le rapport
            ];
        })->sortByDesc('date')->take(30); // 30 derniers jours maximum

        // Statistiques générales
        $totalSessions = $activityLogs->unique('session_id')->count();
        $totalDuration = $activityLogs->sum('duration_seconds');
        $firstConnection = $activityLogs->min('created_at');
        $lastConnection = $activityLogs->max('created_at');

        // Générer le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.connection-report', [
            'team' => $team,
            'formation' => $formationWithProgress,
            'user' => $user,
            'formationUser' => $formationUser,
            'progress' => $progress,
            'dailyActivities' => $dailyActivities,
            'totalSessions' => $totalSessions,
            'totalDuration' => $totalDuration,
            'formattedTotalDuration' => $this->formatDuration($totalDuration),
            'firstConnection' => $firstConnection,
            'lastConnection' => $lastConnection,
        ]);

        // Configuration du PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        // Nom du fichier
        $filename = 'formation-'.$formation->id.'-rapport-connexion-'.$user->id.'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Formater la durée en heures, minutes, secondes
     */
    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds.'s';
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes < 60) {
            return $minutes.'min '.$remainingSeconds.'s';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return $hours.'h '.$remainingMinutes.'min';
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

        if (! $this->formationEnrollmentService->canTeamAffordFormation($team, $formation)) {
            return back()->with('error', 'Le solde de votre équipe est insuffisant pour cette formation.');
        }

        try {
            $enrolled = $this->formationEnrollmentService->enrollUser($formation, $team, $user->id);

            if (! $enrolled) {
                return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
            }

            // Rediriger vers le quiz d'entrée si la formation en a un
            $entryQuiz = $formation->entryQuiz;
            if ($entryQuiz) {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('info', 'Bienvenue dans cette formation ! Veuillez d\'abord passer le quiz d\'entrée.');
            }

            return back()->with('success', 'Vous avez été inscrit à la formation avec succès !');
        } catch (\Throwable $e) {
            report($e);

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

        // Vérifier le quiz d'entrée si la formation en a un
        $entryQuiz = $formation->entryQuiz;
        if ($entryQuiz) {
            $formationProgress = $formation->learners()->where('user_id', $user->id)->first();

            if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
                $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
                [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);

                if ($entryScore < $minScore) {
                    return redirect()->route('eleve.formation.show', [$team, $formation])
                        ->with('error', 'Votre niveau actuel est insuffisant pour cette formation. Un formateur vous contactera pour vous orienter vers un parcours plus adapté.');
                }

                if ($entryScore > $maxScore) {
                    return redirect()->route('eleve.formation.show', [$team, $formation])
                        ->with('error', 'Votre niveau est trop élevé pour cette formation. Un superadmin vous contactera pour vous proposer une formation plus adaptée.');
                }
            } else {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('warning', 'Vous devez d\'abord passer le quiz d\'entrée pour accéder à cette formation.');
            }
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
            abort(404, 'Contenu de leçon non trouvé.');
        }

        // Démarrer automatiquement la leçon lors de la visite (seulement si pas déjà terminée)
        $this->startLessonAutomatically($team, $formation, $chapter, $lesson);

        // Récupérer la progression de l’étudiant pour cette leçon
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        // Charger les ressources associées à la leçon
        $lesson->loadMissing('resources');
        $lessonResources = $lesson->resources()
            ->orderBy('name')
            ->orderBy('id')
            ->get();
        $canDownloadLessonResources = in_array(optional($lessonProgress?->pivot)->status, ['in_progress', 'completed']);

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
            'lessonResources' => $lessonResources,
            'canDownloadLessonResources' => $canDownloadLessonResources,
            'assistantTrainerSlug' => $assistantTrainerSlug,
            'assistantTrainerName' => $assistantTrainerName,
        ]);
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
     * Télécharger une ressource liée à la leçon.
     */
    public function downloadLessonResource(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, LessonResource $resource)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit a cette formation.');
        }

        if ((int) $resource->lesson_id !== (int) $lesson->id) {
            abort(404, 'Ressource non trouvée pour cette leçon.');
        }

        $lessonStatus = $lesson->learners()
            ->where('user_id', $user->id)
            ->value('status');

        if (! in_array($lessonStatus, ['in_progress', 'completed'], true)) {
            abort(403, 'Veuillez demarrer ou completer la lecon pour acceder aux ressources.');
        }

        $disk = Storage::disk('public');
        $path = $resource->file_path;

        if (! $path || ! $disk->exists($path)) {
            abort(404, 'Fichier de ressource introuvable.');
        }

        $downloadName = $resource->name ?: basename($path);

        return $disk->download($path, $downloadName);
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

        // Préparer les ressources associées à la leçon
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        $lesson->loadMissing('resources');
        $lessonResources = $lesson->resources()
            ->orderBy('name')
            ->orderBy('id')
            ->get();

        $canDownloadLessonResources = in_array(optional($lessonProgress?->pivot)->status, ['in_progress', 'completed']);

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
                    'status' => 'completed', // Marquer comme terminée pour débloquer la progression
                    'attempts' => $attempts,
                ],
            ]);

            // Mettre à jour la progression globale de la formation
            $this->updateFormationProgress($user, $formation);

            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Vous avez atteint le nombre maximum de tentatives pour ce quiz. Vous pouvez continuer avec la formation.');
        }

        return view('in-application.eleve.lesson.quiz', [
            'team' => $team,
            'formation' => $formation,
            'chapter' => $chapter,
            'lesson' => $lesson,
            'quiz' => $quiz,
            'questions' => $questions,
            'lessonResources' => $lessonResources,
            'canDownloadLessonResources' => $canDownloadLessonResources,
        ]);
    }

    /**
     * Soumettre les réponses d'un quiz
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
                ->with('success', 'Félicitations ! Vous avez réussi le quiz avec un score de '.round($score, 1).'%.');
        }

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
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // Récupérer les informations du quiz
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

    /**
     * Afficher le quiz d'entrée pour un étudiant
     */
    public function attemptEntryQuiz(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation a un quiz d'entrée
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette formation n\'a pas de quiz d\'entrée.');
        }

        // Vérifier si l'étudiant a déjà passé le quiz d'entrée
        $formationProgress = $formation->learners()->where('user_id', $user->id)->first();
        if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
            $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
            [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);

            if ($entryScore < $minScore) {
                return redirect()->route('eleve.formation.show', [$team, $formation])
                    ->with('error', 'Votre niveau actuel est insuffisant pour cette formation. Un formateur vous contactera pour vous orienter vers un parcours plus adapté.');
            }

            if ($entryScore > $maxScore) {
                return redirect()->route('eleve.formation.show', [$team, $formation])
                    ->with('error', 'Votre niveau est trop élevé pour cette formation. Un superadmin vous contactera pour vous proposer une formation plus adaptée.');
            }

            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('success', 'Félicitations ! Votre niveau correspond parfaitement à cette formation (score: '.round($entryScore, 1).'%). Vous pouvez maintenant commencer.');
        }

        // Récupérer les questions du quiz
        $questions = $entryQuiz->quizQuestions()->with('quizChoices')->get();

        return view('in-application.eleve.formation.entry-quiz', compact(
            'team',
            'formation',
            'entryQuiz',
            'questions'
        ));
    }

    /**
     * Soumettre les réponses du quiz d'entrée
     */
    public function submitEntryQuiz(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Vérifier si la formation a un quiz d'entrée
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return response()->json(['error' => 'Quiz d\'entrée non trouvé'], 404);
        }

        $answers = $request->input('answers', []);

        // Utiliser le QuizService pour soumettre le quiz
        $quizService = app(\App\Services\Quiz\QuizService::class);
        $result = $quizService->submitFormationQuiz($user, $entryQuiz, $answers, \App\Models\QuizAttempt::TYPE_PRE);

        // Mettre à jour la progression de l'étudiant dans la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'entry_quiz_attempt_id' => $result['attempt_id'],
                'entry_quiz_score' => $result['score'],
                'entry_quiz_completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // Vérifier si l'étudiant a réussi le quiz
        [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);

        if ($result['score'] < $minScore) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('error', 'Votre niveau actuel est insuffisant pour cette formation (score: '.round($result['score'], 1).'%). Un formateur vous contactera pour vous orienter vers un parcours plus adapté.');
        }

        if ($result['score'] > $maxScore) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('error', 'Votre niveau est trop élevé pour cette formation (score: '.round($result['score'], 1).'%). Un superadmin vous contactera pour vous proposer une formation plus adaptée.');
        }

        return redirect()->route('eleve.formation.show', [$team, $formation])
            ->with('success', 'Félicitations ! Votre niveau correspond parfaitement à cette formation (score: '.round($result['score'], 1).'%). Vous pouvez maintenant commencer.');
    }

    /**
     * Afficher les résultats du quiz d'entrée
     */
    public function entryQuizResults(Team $team, Formation $formation, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier que la tentative appartient à l'utilisateur connecté
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Tentative non autorisée.');
        }

        // Vérifier que c'est bien une tentative de quiz d'entrée
        if ($attempt->attempt_type !== \App\Models\QuizAttempt::TYPE_PRE) {
            abort(404, 'Résultats non trouvés.');
        }

        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz || $attempt->quiz_id !== $entryQuiz->id) {
            abort(404, 'Quiz non trouvé.');
        }

        // Récupérer les réponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // Récupérer les informations du quiz
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

    /**
     * Soumettre un retour sur une formation terminée
     */
    public function submitFeedback(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // Vérifier si l'étudiant est inscrit à la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'êtes pas inscrit à cette formation.');
        }

        // Vérifier si la formation est terminée
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return back()->with('error', 'Vous devez d\'abord terminer la formation avant de donner votre retour.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Récupérer ou créer l'enregistrement FormationUser
        $formationUser = \App\Models\FormationUser::firstOrNew([
            'formation_id' => $formation->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // Vérifier si un retour a déjà été donné
        if ($formationUser->feedback_at) {
            return back()->with('warning', 'Vous avez déjà donné votre retour pour cette formation.');
        }

        // Sauvegarder le retour
        $formationUser->feedback_rating = $request->input('rating');
        $formationUser->feedback_comment = $request->input('comment');
        $formationUser->feedback_at = now();
        $formationUser->save();

        return back()->with('success', 'Merci pour votre retour ! Votre avis nous aide à améliorer nos formations.');
    }
}
