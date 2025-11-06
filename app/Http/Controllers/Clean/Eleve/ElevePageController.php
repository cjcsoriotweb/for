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

    /**
     * V├®rifie si le seuil de passage du quiz d'entr├®e est valide
     * Le seuil doit ├¬tre strictement entre 0% et 100%
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

        // R├â┬®cup├â┬®rer les formations actuelles de l'├â┬®tudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // Ajouter les donn├â┬®es de progression pour chaque formation
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

            // Ajouter les informations de validation pour les formations termin├®es
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

        return view('in-application.eleve.home', compact(
            'team',
            'formationsWithProgress',
            'formationsPaginees'
        ));
    }

    /**
     * Afficher les d├â┬®tails d'une formation pour un ├â┬®tudiant
     */
    public function showFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier le quiz d'entr├â┬®e si la formation en a un
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

        // R├â┬®cup├â┬®rer la formation avec le progr├â┬¿s de l'├â┬®tudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouv├â┬®e ou non accessible.');
        }

        // R├â┬®cup├â┬®rer le progr├â┬¿s d├â┬®taill├â┬®
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
                    'attachments' => $attachments,
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
     * Afficher la page d├®di├®e aux formations termin├®es
     */
    public function showCompletedFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├¬tes pas inscrit ├á cette formation.');
        }

        // V├®rifier si la formation est termin├®e
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore termin├®e.');
        }

        // R├®cup├®rer la formation avec le progr├¿s de l'├®tudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouv├®e ou non accessible.');
        }

        // R├®cup├®rer le progr├¿s d├®taill├®
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        $formationDocuments = $formation->completionDocuments()->get();

        // R├®cup├®rer toutes les le├ºons de la formation avec leur statut
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($lessonQuery) use ($user) {
                $lessonQuery->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->orderBy('position')
            ->get();

        $lessonResources = collect();
        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonLearner = $lesson->learners->first();
                $isCompleted = optional($lessonLearner?->pivot)->status === 'completed';

                // R├®cup├®rer les pi├¿ces jointes si c'est du contenu texte
                $attachments = collect();
                if ($lesson->lessonable_type === TextContent::class && $lesson->lessonable) {
                    $attachments = $lesson->lessonable->attachments ?? collect();
                }

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

        // R├®cup├®rer les donn├®es de completion de la formation
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
     * Afficher la page de f├â┬®licitations pour une formation termin├â┬®e
     */
    public function formationCongratulation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // Verifier si l'etudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit ├â┬á cette formation.');
        }

        // Marquer la formation comme termin├â┬®e (forcer le statut completed et progression ├á 100%)
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'status' => 'completed',
                'progress_percent' => 100,
                'completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // Recuperer la formation avec le progr├â┬¿s de l'etudiant
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

    public function downloadCompletionDocument(Team $team, Formation $formation, $documentIdentifier)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous ne pouvez pas acceder a cette formation.');
        }

        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            abort(403, 'La formation doit etre terminee pour acceder au document.');
        }

        // V├®rifier si c'est un document de formation standard
        if (is_numeric($documentIdentifier) || $documentIdentifier instanceof FormationCompletionDocument) {
            $document = $documentIdentifier instanceof FormationCompletionDocument ? $documentIdentifier : FormationCompletionDocument::findOrFail($documentIdentifier);

            if ($document->formation_id !== $formation->id) {
                abort(404);
            }

            $downloadName = $document->title ?: $document->original_name;

            return \Illuminate\Support\Facades\Storage::disk('public')->download($document->file_path, $downloadName);
        }

        // V├®rifier si c'est un document joint de validation (format: completion-{index})
        if (str_starts_with($documentIdentifier, 'completion-')) {
            $index = (int) str_replace('completion-', '', $documentIdentifier);

            $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
                ->where('user_id', $user->id)
                ->where('team_id', $team->id)
                ->first();

            if (! $formationUser || ! $formationUser->completion_documents || ! isset($formationUser->completion_documents[$index])) {
                abort(404, 'Document non trouv├®.');
            }

            $document = $formationUser->completion_documents[$index];

            if (! Storage::disk('public')->exists($document['path'])) {
                abort(404, 'Fichier non trouv├® sur le serveur.');
            }

            return \Illuminate\Support\Facades\Storage::disk('public')->download($document['path'], $document['original_name']);
        }

        abort(404, 'Document non trouv├®.');
    }

    /**
     * Demander la validation de fin de formation
     */
    public function requestCompletion(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit ├á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├¬tes pas inscrit ├á cette formation.');
        }

        // V├®rifier si la formation est termin├®e
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return back()->with('error', 'Vous devez d\'abord terminer la formation avant de demander sa validation.');
        }

        // R├®cup├®rer ou cr├®er l'enregistrement FormationUser
        $formationUser = \App\Models\FormationUser::firstOrNew([
            'formation_id' => $formation->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // V├®rifier si une demande n'a pas d├®j├á ├®t├® faite
        if ($formationUser->completion_request_at && $formationUser->completion_request_status === 'pending') {
            return back()->with('warning', 'Une demande de validation est d├®j├á en cours de traitement.');
        }

        // Cr├®er la demande de validation
        $formationUser->completion_request_at = now();
        $formationUser->completion_request_status = 'pending';
        $formationUser->save();

        return back()->with('success', 'Votre demande de validation de fin de formation a ├®t├® envoy├®e avec succ├¿s. Un superadmin la traitera dans les plus brefs d├®lais.');
    }

    /**
     * T├®l├®charger la page de formation termin├®e en PDF
     */
    public function downloadCompletedFormationPdf(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├¬tes pas inscrit ├á cette formation.');
        }

        // V├®rifier si la formation est termin├®e
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore termin├®e.');
        }

        // R├®cup├®rer les donn├®es n├®cessaires pour le PDF
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationDocuments = $formation->completionDocuments()->get();

        // R├®cup├®rer toutes les le├ºons de la formation avec leur statut
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($lessonQuery) use ($user) {
                $lessonQuery->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->orderBy('position')
            ->get();

        $lessonResources = collect();
        foreach ($chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonLearner = $lesson->learners->first();
                $isCompleted = optional($lessonLearner?->pivot)->status === 'completed';

                // R├®cup├®rer les pi├¿ces jointes si c'est du contenu texte
                $attachments = collect();
                if ($lesson->lessonable_type === TextContent::class && $lesson->lessonable) {
                    $attachments = $lesson->lessonable->attachments ?? collect();
                }

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

        // R├®cup├®rer les donn├®es de completion de la formation
        $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->with(['completionValidatedBy'])
            ->first();

        // G├®n├®rer le PDF
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
     * T├®l├®charger le rapport de connexion de l'├®tudiant pour cette formation en PDF
     */
    public function downloadConnectionReportPdf(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├¬tes pas inscrit ├á cette formation.');
        }

        // V├®rifier si la formation est termin├®e
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore termin├®e.');
        }

        // R├®cup├®rer les donn├®es de la formation
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        // R├®cup├®rer les donn├®es de completion de la formation
        $formationUser = \App\Models\FormationUser::where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->first();

        // R├®cup├®rer les logs d'activit├® pour cette formation
        $startDate = $formationUser ? $formationUser->enrolled_at : now()->subMonths(6);
        $endDate = $formationUser ? ($formationUser->completed_at ?? now()) : now();

        $activityLogs = \App\Models\UserActivityLog::where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where(function ($query) use ($formation) {
                // Filtrer les activit├®s li├®es ├á cette formation
                $query->where('url', 'like', '%/eleve/%/formations/'.$formation->id.'%')
                    ->orWhere('url', 'like', '%/formation/'.$formation->id.'%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Grouper les activit├®s par jour
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
                'activities' => $logs->take(10), // Limiter ├á 10 activit├®s par jour pour le rapport
            ];
        })->sortByDesc('date')->take(30); // 30 derniers jours maximum

        // Statistiques g├®n├®rales
        $totalSessions = $activityLogs->unique('session_id')->count();
        $totalDuration = $activityLogs->sum('duration_seconds');
        $firstConnection = $activityLogs->min('created_at');
        $lastConnection = $activityLogs->max('created_at');

        // G├®n├®rer le PDF
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
     * Formater la dur├®e en heures, minutes, secondes
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
     * Inscrire un etudiant a une formation
     */
    public function enroll(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est d├â┬®j├â┬á inscrit
        if ($this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('warning', 'Vous ├â┬¬tes d├â┬®j├â┬á inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier si la formation est disponible pour cette ├â┬®quipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->pluck('formations.id');

        if (! $availableFormations->contains($formation->id)) {
            return back()->with('error', 'Cette formation n\'est pas disponible pour votre ├â┬®quipe.');
        }

        if (! $this->formationEnrollmentService->canTeamAffordFormation($team, $formation)) {
            return back()->with('error', 'Le solde de votre ├â┬®quipe est insuffisant pour cette formation.');
        }

        try {
            $enrolled = $this->formationEnrollmentService->enrollUser($formation, $team, $user->id);

            if (! $enrolled) {
                return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
            }

            // Rediriger vers le quiz d'entr├â┬®e si la formation en a un
            $entryQuiz = $formation->entryQuiz;
            if ($entryQuiz) {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('info', 'Bienvenue dans cette formation ! Veuillez d\'abord passer le quiz d\'entr├â┬®e.');
            }

            return back()->with('success', 'Vous avez ├â┬®t├â┬® inscrit ├â┬á la formation avec succ├â┬¿s !');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * R├â┬®initialiser le progr├â┬¿s d'un ├â┬®tudiant dans une formation
     */
    public function resetProgress(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est inscrit ├â┬á cette formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('error', 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        try {
            // R├â┬®cup├â┬®rer la premi├â┬¿re le├â┬ºon de la formation pour remettre current_lesson_id
            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            // R├â┬®initialiser le progr├â┬¿s ├â┬á 0
            $formation->learners()->updateExistingPivot($user->id, [
                'status' => 'enrolled',
                'enrolled_at' => now(),
                'current_lesson_id' => $firstLesson?->id,
                'last_seen_at' => now(),
            ]);

            return back()->with('success', 'Le progr├â┬¿s a ├â┬®t├â┬® r├â┬®initialis├â┬® avec succ├â┬¿s.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la r├â┬®initialisation du progr├â┬¿s.');
        }
    }

    /**
     * API endpoint pour r├â┬®cup├â┬®rer les formations d'un ├â┬®tudiant (pour AJAX)
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
     * API endpoint pour r├â┬®cup├â┬®rer la progression d'une formation
     */
    public function apiProgress(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // V├â┬®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├â┬®'], 403);
        }

        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return response()->json([
            'progress' => $progress,
            'formation' => $formationWithProgress,
        ]);
    }

    /**
     * Afficher le contenu d'une le├â┬ºon
     */
    public function showLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est inscrit ├â┬á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier que la le├â┬ºon appartient bien au chapitre et ├â┬á la formation
        if ($lesson->chapter_id !== $chapter->id || $chapter->formation_id !== $formation->id) {
            abort(404, 'Le├â┬ºon non trouv├â┬®e.');
        }

        // V├â┬®rifier le quiz d'entr├â┬®e si la formation en a un
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
                    ->with('warning', 'Vous devez d\'abord passer le quiz d\'entr├â┬®e pour acc├â┬®der ├â┬á cette formation.');
            }
        }

        // V├â┬®rifier si la le├â┬ºon est d├â┬®j├â┬á termin├â┬®e (sauf si c'est la premi├â┬¿re visite)
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
            // Rediriger vers la formation avec un message d'information
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette le├â┬ºon est d├â┬®j├â┬á termin├â┬®e. Vous pouvez passer ├â┬á la le├â┬ºon suivante.');
        }

        // R├â┬®cup├â┬®rer le contenu de la le├â┬ºon selon son type
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
            abort(404, 'Contenu de le├â┬ºon non trouv├â┬®.');
        }

        // D├â┬®marrer automatiquement la le├â┬ºon lors de la visite (seulement si pas d├â┬®j├â┬á termin├â┬®e)
        $this->startLessonAutomatically($team, $formation, $chapter, $lesson);

        // R├â┬®cup├â┬®rer la progression de l'├â┬®tudiant pour cette le├â┬ºon
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        // R├â┬®cup├â┬®rer les le├â┬ºons pr├â┬®c├â┬®dente et suivante dans le chapitre
        $previousLesson = $chapter->lessons()
            ->where('position', '<', $lesson->position)
            ->orderBy('position', 'desc')
            ->first();

        $nextLesson = $chapter->lessons()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->first();

        // R├â┬®cup├â┬®rer les autres chapitres de la formation pour la navigation
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
     * D├â┬®marrer automatiquement une le├â┬ºon lors de la visite
     */
    private function startLessonAutomatically(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Cr├â┬®er ou mettre ├â┬á jour la progression de l'├â┬®tudiant pour cette le├â┬ºon
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'started_at' => now(),
                'last_activity_at' => now(),
                'status' => 'in_progress',
            ],
        ]);
    }

    /**
     * D├â┬®marrer une le├â┬ºon (tracking du temps) - API endpoint
     */
    public function startLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├â┬®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├â┬®'], 403);
        }

        // Cr├â┬®er ou mettre ├â┬á jour la progression de l'├â┬®tudiant pour cette le├â┬ºon
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
     * Marquer une le├â┬ºon comme termin├â┬®e
     */
    public function completeLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├â┬®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├â┬®'], 403);
        }

        // Marquer la le├â┬ºon comme termin├â┬®e
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'last_activity_at' => now(),
                'status' => 'completed',
            ],
        ]);

        // Mettre ├â┬á jour la progression globale de la formation
        $this->updateFormationProgress($user, $formation);

        return response()->json(['success' => true]);
    }

    /**
     * Mettre ├â┬á jour la progression d'une le├â┬ºon (pour le contenu textuel)
     */
    public function updateProgress(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // V├â┬®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├â┬®'], 403);
        }

        $readPercent = $request->input('read_percent', 0);

        // Mettre ├â┬á jour la progression de lecture
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'read_percent' => $readPercent,
                'last_activity_at' => now(),
            ],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Afficher la page de quiz pour un ├â┬®tudiant
     */
    public function attemptQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├â┬®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier que la le├â┬ºon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouv├â┬®.');
        }

        $quiz = $lesson->lessonable;

        // R├â┬®cup├â┬®rer les questions du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        // V├â┬®rifier si l'├â┬®tudiant a d├â┬®j├â┬á atteint le nombre maximum de tentatives
        $attempts = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot?->attempts ?? 0;

        if ($attempts >= $quiz->max_attempts && $quiz->max_attempts > 0) {
            // Marquer la le├â┬ºon comme termin├â┬®e m├â┬¬me si le quiz n'est pas r├â┬®ussi
            // pour permettre ├â┬á l'├â┬®tudiant de continuer la formation
            $lesson->learners()->syncWithoutDetaching([
                $user->id => [
                    'completed_at' => now(),
                    'last_activity_at' => now(),
                    'status' => 'completed', // ├ó┼ôÔÇª Marquer comme termin├â┬® pour d├â┬®bloquer la progression
                    'attempts' => $attempts,
                ],
            ]);

            // Mettre ├â┬á jour la progression globale de la formation
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
     * Soumettre les r├â┬®ponses d'un quiz
     */
    public function submitQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├®'], 403);
        }

        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            return response()->json(['error' => 'Quiz non trouv├®'], 404);
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
                ->with('success', 'F├®licitations ! Vous avez r├®ussi le quiz avec un score de '.round($score, 1).'%.');
        }

        return response()->json([
            'success' => false,
            'passed' => false,
            'can_retry' => true,
            'message' => 'Quiz ├®chou├®. Vous pouvez r├®essayer.',
        ]);
    }

    /**
     * Afficher les r├â┬®sultats d'une tentative de quiz
     */
    public function quizResults(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // V├â┬®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier que la le├â┬ºon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouv├â┬®.');
        }

        // V├â┬®rifier que la tentative appartient ├â┬á l'utilisateur connect├â┬®
        if ($attempt->user_id !== $user->id || $attempt->lesson_id !== $lesson->id) {
            abort(403, 'Tentative non autoris├â┬®e.');
        }

        $quiz = $lesson->lessonable;

        // R├â┬®cup├â┬®rer les r├â┬®ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // R├â┬®cup├â┬®rer les informations du quiz
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
     * Mettre ├â┬á jour la progression globale d'une formation
     */
    private function updateFormationProgress(User $user, Formation $formation)
    {
        // R├â┬®cup├â┬®rer tous les chapitres avec leurs le├â┬ºons et la progression de l'utilisateur
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($query) use ($user) {
                $query->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->get();

        // Calculer la progression bas├â┬®e sur les le├â┬ºons termin├â┬®es
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

        // Mettre ├â┬á jour la progression de la formation et le current_lesson_id
        $this->updateCurrentLessonId($user, $formation);

        // Mettre ├â┬á jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Mettre ├â┬á jour le current_lesson_id pour pointer vers la prochaine le├â┬ºon non termin├â┬®e
     */
    private function updateCurrentLessonId(User $user, Formation $formation): void
    {
        // R├â┬®cup├â┬®rer la formation avec tous les chapitres et le├â┬ºons ordonn├â┬®s
        $formationWithLessons = $formation->load([
            'chapters' => function ($query) {
                $query->orderBy('position')
                    ->with(['lessons' => function ($lessonQuery) {
                        $lessonQuery->orderBy('position');
                    }]);
            },
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et le├â┬ºons pour trouver la premi├â┬¿re non termin├â┬®e
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // V├â┬®rifier si cette le├â┬ºon est termin├â┬®e
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (! $lessonProgress || $lessonProgress->pivot->status !== 'completed') {
                    // Cette le├â┬ºon n'est pas termin├â┬®e, c'est la suivante
                    $nextLessonId = $lesson->id;
                    break 2; // Sortir des deux boucles
                }
            }
        }

        // Mettre ├â┬á jour le current_lesson_id dans formation_user
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'current_lesson_id' => $nextLessonId,
                'last_seen_at' => now(),
            ],
        ]);
    }

    /**
     * Afficher le quiz d'entr├â┬®e pour un ├â┬®tudiant
     */
    public function attemptEntryQuiz(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est inscrit ├â┬á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier si la formation a un quiz d'entr├â┬®e
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette formation n\'a pas de quiz d\'entr├â┬®e.');
        }

        // V├â┬®rifier si l'├â┬®tudiant a d├â┬®j├â┬á pass├â┬® le quiz d'entr├â┬®e
        $formationProgress = $formation->learners()->where('user_id', $user->id)->first();
        if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
            $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;

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

        // R├â┬®cup├â┬®rer les questions du quiz
        $questions = $entryQuiz->quizQuestions()->with('quizChoices')->get();

        return view('in-application.eleve.formation.entry-quiz', compact(
            'team',
            'formation',
            'entryQuiz',
            'questions'
        ));
    }

    /**
     * Soumettre les r├â┬®ponses du quiz d'entr├â┬®e
     */
    public function submitEntryQuiz(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est inscrit ├â┬á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├â┬®'], 403);
        }

        // V├â┬®rifier si la formation a un quiz d'entr├â┬®e
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return response()->json(['error' => 'Quiz d\'entr├â┬®e non trouv├â┬®'], 404);
        }

        $answers = $request->input('answers', []);

        // Utiliser le QuizService pour soumettre le quiz
        $quizService = app(\App\Services\Quiz\QuizService::class);
        $result = $quizService->submitFormationQuiz($user, $entryQuiz, $answers, \App\Models\QuizAttempt::TYPE_PRE);

        // Mettre ├â┬á jour la progression de l'├â┬®tudiant dans la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'entry_quiz_attempt_id' => $result['attempt_id'],
                'entry_quiz_score' => $result['score'],
                'entry_quiz_completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // V├â┬®rifier si l'├â┬®tudiant a r├â┬®ussi le quiz
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
     * Afficher les r├â┬®sultats du quiz d'entr├â┬®e
     */
    public function entryQuizResults(Team $team, Formation $formation, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // V├â┬®rifier si l'├â┬®tudiant est inscrit ├â┬á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├â┬¬tes pas inscrit ├â┬á cette formation.');
        }

        // V├â┬®rifier que la tentative appartient ├â┬á l'utilisateur connect├â┬®
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Tentative non autoris├â┬®e.');
        }

        // V├â┬®rifier que c'est bien une tentative de quiz d'entr├â┬®e
        if ($attempt->attempt_type !== \App\Models\QuizAttempt::TYPE_PRE) {
            abort(404, 'R├â┬®sultats non trouv├â┬®s.');
        }

        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz || $attempt->quiz_id !== $entryQuiz->id) {
            abort(404, 'Quiz non trouv├â┬®.');
        }

        // R├â┬®cup├â┬®rer les r├â┬®ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // R├â┬®cup├â┬®rer les informations du quiz
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
     * Soumettre un retour sur une formation termin├®e
     */
    public function submitFeedback(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit ├á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'├¬tes pas inscrit ├á cette formation.');
        }

        // V├®rifier si la formation est termin├®e
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return back()->with('error', 'Vous devez d\'abord terminer la formation avant de donner votre retour.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // R├®cup├®rer ou cr├®er l'enregistrement FormationUser
        $formationUser = \App\Models\FormationUser::firstOrNew([
            'formation_id' => $formation->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        // V├®rifier si un retour a d├®j├á ├®t├® donn├®
        if ($formationUser->feedback_at) {
            return back()->with('warning', 'Vous avez d├®j├á donn├® votre retour pour cette formation.');
        }

        // Sauvegarder le retour
        $formationUser->feedback_rating = $request->input('rating');
        $formationUser->feedback_comment = $request->input('comment');
        $formationUser->feedback_at = now();
        $formationUser->save();

        return back()->with('success', 'Merci pour votre retour ! Votre avis nous aide ├á am├®liorer nos formations.');
    }
}
