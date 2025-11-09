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
use App\Models\FormationUser;
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

    private function fetchFormationUser(Team $team, Formation $formation, User $user): ?FormationUser
    {
        return FormationUser::with('completionValidatedBy')
            ->where('formation_id', $formation->id)
            ->where('user_id', $user->id)
            ->where('team_id', $team->id)
            ->first();
    }

    private function needsPostEntryQuizRetake(?FormationUser $formationUser, bool $hasEntryQuiz, bool $isFormationCompleted): bool
    {
        if (! $hasEntryQuiz || ! $formationUser) {
            return false;
        }

        return (bool) $formationUser->entry_quiz_attempt_id
            && ! $formationUser->post_quiz_attempt_id
            && $isFormationCompleted;
    }

    public function home(Team $team)
    {
        $user = Auth::user();

        // R├®cup├®rer les formations actuelles de l'├®tudiant
        $formations = $this->studentFormationService->listFormationCurrentByStudent($team, $user);

        // R├®cup├®rer le nombre de formations disponibles pour l'├®quipe
        $availableFormationsCount = $this->studentFormationService->listAvailableFormationsForTeam($team)->count();

        // Ajouter les donn├®es de progression pour chaque formation
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
     * Afficher les d├®tails d'une formation pour un ├®tudiant
     */
    public function showFormation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // Préparer les données et le quiz d'entrée
        $entryQuiz = $formation->entryQuiz;
        $formationUser = $this->fetchFormationUser($team, $formation, $user);
        $isFormationCompleted = $this->studentFormationService->isFormationCompleted($user, $formation);

        $entryQuizStatus = null;
        $entryQuizThresholds = null;
        $entryQuizScore = null;
        $postQuizScore = $formationUser?->post_quiz_score !== null ? (float) $formationUser->post_quiz_score : null;
        $quizProgressDelta = $formationUser?->quiz_progress_delta !== null ? (float) $formationUser->quiz_progress_delta : null;

        if ($entryQuiz) {
            [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);
            $entryQuizThresholds = ['min' => $minScore, 'max' => $maxScore];

            if ($formationUser?->entry_quiz_attempt_id) {
                $entryQuizScore = (float) ($formationUser->entry_quiz_score ?? 0);
                $entryQuizStatus = $this->determineEntryQuizStatus($entryQuizScore, $minScore, $maxScore);
            } else {
                $entryQuizStatus = 'required';
            }
        }

        $needsEntryQuizRetake = $this->needsPostEntryQuizRetake(
            $formationUser,
            (bool) $entryQuiz,
            $isFormationCompleted
        );

        $validationStatus = $formationUser?->completion_request_status;
        $isValidated = $validationStatus === 'approved';
        $isPendingValidation = $validationStatus === 'pending';

        $studentFormationService = $this->studentFormationService;

        // R├®cup├®rer la formation avec le progr├¿s de l'├®tudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouv├®e ou non accessible.');
        }

        // R├®cup├®rer le progr├¿s d├®taill├®
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        $formationDocuments = $formation->completionDocuments()->get();

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
                    'lesson_title' => $lesson->getName(),
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
            'entryQuiz' => $entryQuiz,
            'entryQuizThresholds' => $entryQuizThresholds,
            'entryQuizScore' => $entryQuizScore,
            'postQuizScore' => $postQuizScore,
            'quizProgressDelta' => $quizProgressDelta,
            'needsEntryQuizRetake' => $needsEntryQuizRetake,
            'entryQuizStatus' => $entryQuizStatus,
            'validationStatus' => $validationStatus,
            'isValidated' => $isValidated,
            'isPendingValidation' => $isPendingValidation,
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
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
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
                    'lesson_title' => $lesson->getName(),
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

        // R├®cup├®rer les donn├®es de compl├®tion de la formation
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
     * Afficher la page de f├®licitations pour une formation termin├®e
     */
    public function formationCongratulation(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // Marquer la formation comme termin├®e (forcer le statut completed et progression ├á 100 %)
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'status' => 'completed',
                'progress_percent' => 100,
                'completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // R├®cup├®rer la formation avec le progr├¿s de l'├®tudiant
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            abort(404, 'Formation non trouv├®e ou non accessible.');
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
                    'lesson_title' => $lesson->getName(),
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
            abort(403, 'Vous ne pouvez pas acc├®der ├á cette formation.');
        }

        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            abort(403, 'La formation doit ├¬tre termin├®e pour acc├®der au document.');
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
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
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
        // Flag for manual validation by admin
        $formationUser->need_verif = true;
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
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
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
                    'lesson_title' => $lesson->getName(),
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

        // R├®cup├®rer les donn├®es de compl├®tion de la formation
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
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // V├®rifier si la formation est termin├®e
        if (! $this->studentFormationService->isFormationCompleted($user, $formation)) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('warning', 'Cette formation n\'est pas encore termin├®e.');
        }

        // R├®cup├®rer les donn├®es de la formation
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);
        $progress = $this->studentFormationService->getStudentProgress($user, $formation);

        // R├®cup├®rer les donn├®es de compl├®tion de la formation
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
     * Inscrire un ├®tudiant ├á une formation
     */
    public function enroll(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est d├®j├á inscrit
        if ($this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('warning', 'Vous ├¬tes d├®j├á inscrit ├á cette formation.');
        }

        // V├®rifier si la formation est disponible pour cette ├®quipe
        $availableFormations = $team->formationsByTeam()
            ->where('formation_in_teams.visible', true)
            ->pluck('formations.id');

        if (! $availableFormations->contains($formation->id)) {
            return back()->with('error', 'Cette formation n\'est pas disponible pour votre ├®quipe.');
        }

        if (! $this->formationEnrollmentService->canTeamAffordFormation($team, $formation)) {
            return back()->with('error', 'Le solde de votre ├®quipe est insuffisant pour cette formation.');
        }

        try {
            $enrolled = $this->formationEnrollmentService->enrollUser($formation, $team, $user->id);

            if (! $enrolled) {
                return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
            }

            // Rediriger vers le quiz d'entr├®e si la formation en a un
            $entryQuiz = $formation->entryQuiz;
            if ($entryQuiz) {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('info', 'Bienvenue dans cette formation ! Veuillez d\'abord passer le quiz d\'entr├®e.');
            }

            return back()->with('success', 'La formation est disponible dès maintenant sur cette page !');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * R├®initialiser le progr├¿s d'un ├®tudiant dans une formation
     */
    public function resetProgress(Team $team, Formation $formation)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit ├á cette formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return back()->with('error', 'Vous n\'etes pas inscrit à cette formation');
        }

        try {
            // R├®cup├®rer la premi├¿re le├ºon de la formation pour remettre current_lesson_id
            $firstLesson = $formation->chapters()
                ->orderBy('position')
                ->first()
                ?->lessons()
                ->orderBy('position')
                ->first();

            // R├®initialiser le progr├¿s ├á 0
            $formation->learners()->updateExistingPivot($user->id, [
                'status' => 'enrolled',
                'enrolled_at' => now(),
                'current_lesson_id' => $firstLesson?->id,
                'last_seen_at' => now(),
            ]);

            return back()->with('success', 'Le progr├¿s a ├®t├® r├®initialis├® avec succ├¿s.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la r├®initialisation du progr├¿s.');
        }
    }

    /**
     * API endpoint pour r├®cup├®rer les formations d'un ├®tudiant (pour AJAX)
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
     * API endpoint pour r├®cup├®rer la progression d'une formation
     */
    public function apiProgress(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // V├®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├®'], 403);
        }

        $progress = $this->studentFormationService->getStudentProgress($user, $formation);
        $formationWithProgress = $this->studentFormationService->getFormationWithProgress($formation, $user);

        return response()->json([
            'progress' => $progress,
            'formation' => $formationWithProgress,
        ]);
    }

    /**
     * Afficher le contenu d'une le├ºon
     */
    public function showLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit ├á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // V├®rifier que la le├ºon appartient bien au chapitre et ├á la formation
        if ($lesson->chapter_id !== $chapter->id || $chapter->formation_id !== $formation->id) {
            abort(404, 'Le├ºon non trouv├®e.');
        }

        // V├®rifier le quiz d'entr├®e si la formation en a un
        $entryQuiz = $formation->entryQuiz;
        if ($entryQuiz) {
            $formationProgress = $formation->learners()->where('user_id', $user->id)->first();

            if ($formationProgress && $formationProgress->pivot->entry_quiz_attempt_id) {
                $entryScore = $formationProgress->pivot->entry_quiz_score ?? 0;
                [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);

                if ($entryScore < $minScore) {
                    return redirect()->route('eleve.formation.show', [$team, $formation])
                        ->with('error', 'Votre niveau actuel est insuffisant pour cette formation. Un formateur vous contactera pour vous orienter vers un parcours plus adapt├®.');
                }

                if ($entryScore > $maxScore) {
                    return redirect()->route('eleve.formation.show', [$team, $formation])
                        ->with('error', 'Votre niveau est trop ├®lev├® pour cette formation. Un superadmin vous contactera pour vous proposer une formation plus adapt├®e.');
                }
            } else {
                return redirect()->route('eleve.formation.entry-quiz.attempt', [$team, $formation])
                    ->with('warning', 'Vous devez d\'abord passer le quiz d\'entr├®e pour acc├®der ├á cette formation.');
            }
        }

        // V├®rifier si la le├ºon est d├®j├á termin├®e (sauf si c'est la premi├¿re visite)
        $lessonProgress = $lesson->learners()->where('user_id', $user->id)->first();
        if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
            // Rediriger vers la formation avec un message d'information
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette le├ºon est d├®j├á termin├®e. Vous pouvez passer ├á la le├ºon suivante.');
        }

        // R├®cup├®rer le contenu de la le├ºon selon son type
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
            abort(404, 'Contenu de le├ºon non trouv├®.');
        }

        // D├®marrer automatiquement la le├ºon lors de la visite (seulement si pas d├®j├á termin├®e)
        $this->startLessonAutomatically($team, $formation, $chapter, $lesson);

        // R├®cup├®rer la progression de lÔÇÖ├®tudiant pour cette le├ºon
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        // Charger les ressources associ├®es ├á la le├ºon
        $lesson->loadMissing('resources');
        $lessonResources = $lesson->resources()
            ->orderBy('name')
            ->orderBy('id')
            ->get();
        $canDownloadLessonResources = in_array(optional($lessonProgress?->pivot)->status, ['in_progress', 'completed']);

        // R├®cup├®rer les le├ºons pr├®c├®dente et suivante dans le chapitre
        $previousLesson = $chapter->lessons()
            ->where('position', '<', $lesson->position)
            ->orderBy('position', 'desc')
            ->first();

        $nextLesson = $chapter->lessons()
            ->where('position', '>', $lesson->position)
            ->orderBy('position', 'asc')
            ->first();

        // R├®cup├®rer les autres chapitres de la formation pour la navigation
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
     * D├®marrer automatiquement une le├ºon lors de la visite
     */
    private function startLessonAutomatically(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // Cr├®er ou mettre ├á jour la progression de l'├®tudiant pour cette le├ºon
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'started_at' => now(),
                'last_activity_at' => now(),
                'status' => 'in_progress',
            ],
        ]);
    }

    /**
     * D├®marrer une le├ºon (tracking du temps) - API endpoint
     */
    public function startLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├®'], 403);
        }

        // Cr├®er ou mettre ├á jour la progression de l'├®tudiant pour cette le├ºon
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
     * Marquer une le├ºon comme termin├®e
     */
    public function completeLesson(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├®'], 403);
        }

        // Marquer la le├ºon comme termin├®e
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'completed_at' => now(),
                'last_activity_at' => now(),
                'status' => 'completed',
            ],
        ]);

        // Mettre ├á jour la progression globale de la formation
        $this->updateFormationProgress($user, $formation);

        return response()->json(['success' => true]);
    }

    /**
     * Mettre ├á jour la progression d'une le├ºon (pour le contenu textuel)
     */
    public function updateProgress(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, Request $request)
    {
        $user = Auth::user();

        // V├®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├®'], 403);
        }

        $readPercent = $request->input('read_percent', 0);

        // Mettre ├á jour la progression de lecture
        $lesson->learners()->syncWithoutDetaching([
            $user->id => [
                'read_percent' => $readPercent,
                'last_activity_at' => now(),
            ],
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * T├®l├®charger une ressource li├®e ├á la le├ºon.
     */
    public function downloadLessonResource(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, LessonResource $resource)
    {
        $user = Auth::user();

        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit a cette formation.');
        }

        if ((int) $resource->lesson_id !== (int) $lesson->id) {
            abort(404, 'Ressource non trouv├®e pour cette le├ºon.');
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
     * Afficher la page de quiz pour un ├®tudiant
     */
    public function attemptQuiz(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson)
    {
        $user = Auth::user();

        // V├®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // V├®rifier que la le├ºon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouv├®.');
        }

        $quiz = $lesson->lessonable;

        // R├®cup├®rer les questions du quiz
        $questions = $quiz->quizQuestions()->with('quizChoices')->get();

        // Pr├®parer les ressources associ├®es ├á la le├ºon
        $lessonProgress = $lesson->learners()
            ->where('user_id', $user->id)
            ->first();

        $lesson->loadMissing('resources');
        $lessonResources = $lesson->resources()
            ->orderBy('name')
            ->orderBy('id')
            ->get();

        $canDownloadLessonResources = in_array(optional($lessonProgress?->pivot)->status, ['in_progress', 'completed']);

        // V├®rifier si l'├®tudiant a d├®j├á atteint le nombre maximum de tentatives
        $attempts = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot?->attempts ?? 0;

        if ($attempts >= $quiz->max_attempts && $quiz->max_attempts > 0) {
            // Marquer la le├ºon comme termin├®e m├¬me si le quiz n'est pas r├®ussi
            // pour permettre ├á l'├®tudiant de continuer la formation
            $lesson->learners()->syncWithoutDetaching([
                $user->id => [
                    'completed_at' => now(),
                    'last_activity_at' => now(),
                    'status' => 'completed', // Marquer comme termin├®e pour d├®bloquer la progression
                    'attempts' => $attempts,
                ],
            ]);

            // Mettre ├á jour la progression globale de la formation
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
     * Soumettre les r├®ponses d'un quiz
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
     * Afficher les r├®sultats d'une tentative de quiz
     */
    public function quizResults(Team $team, Formation $formation, Chapter $chapter, Lesson $lesson, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // V├®rifier les permissions
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // V├®rifier que la le├ºon est bien un quiz
        if ($lesson->lessonable_type !== \App\Models\Quiz::class) {
            abort(404, 'Quiz non trouv├®.');
        }

        // V├®rifier que la tentative appartient ├á l'utilisateur connect├®
        if ($attempt->user_id !== $user->id || $attempt->lesson_id !== $lesson->id) {
            abort(403, 'Tentative non autoris├®e.');
        }

        $quiz = $lesson->lessonable;

        // R├®cup├®rer les r├®ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // R├®cup├®rer les informations du quiz
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
     * Mettre ├á jour la progression globale d'une formation
     */
    private function updateFormationProgress(User $user, Formation $formation)
    {
        // R├®cup├®rer tous les chapitres avec leurs le├ºons et la progression de l'utilisateur
        $chapters = $formation->chapters()
            ->with(['lessons' => function ($query) use ($user) {
                $query->with(['learners' => function ($learnerQuery) use ($user) {
                    $learnerQuery->where('user_id', $user->id);
                }]);
            }])
            ->get();

        // Calculer la progression bas├®e sur les le├ºons termin├®es
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

        // Mettre ├á jour la progression de la formation et le current_lesson_id
        $this->updateCurrentLessonId($user, $formation);

        // Mettre ├á jour la progression de la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'last_seen_at' => now(),
                'status' => $progressPercent >= 100 ? 'completed' : 'in_progress',
            ],
        ]);
    }

    /**
     * Mettre ├á jour le current_lesson_id pour pointer vers la prochaine le├ºon non termin├®e
     */
    private function updateCurrentLessonId(User $user, Formation $formation): void
    {
        // R├®cup├®rer la formation avec tous les chapitres et le├ºons ordonn├®s
        $formationWithLessons = $formation->load([
            'chapters' => function ($query) {
                $query->orderBy('position')
                    ->with(['lessons' => function ($lessonQuery) {
                        $lessonQuery->orderBy('position');
                    }]);
            },
        ]);

        $nextLessonId = null;

        // Parcourir tous les chapitres et le├ºons pour trouver la premi├¿re non termin├®e
        foreach ($formationWithLessons->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                // V├®rifier si cette le├ºon est termin├®e
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if (! $lessonProgress || $lessonProgress->pivot->status !== 'completed') {
                    // Cette le├ºon n'est pas termin├®e, c'est la suivante
                    $nextLessonId = $lesson->id;
                    break 2; // Sortir des deux boucles
                }
            }
        }

        // Mettre ├á jour le current_lesson_id dans formation_user
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

        $isEnrolled = $this->studentFormationService->isEnrolledInFormation($user, $formation, $team);
        if (! $isEnrolled) {
            if (! $this->formationEnrollmentService->canTeamAffordFormation($team, $formation)) {
                abort(403, 'Votre équipe ne dispose pas de crédit pour cette formation.');
            }

            $enrolled = $this->formationEnrollmentService->enrollUser($formation, $team, $user->id);
            if (! $enrolled) {
                abort(403, 'Impossible de vous inscrire pour le moment. Contactez un administrateur.');
            }
        }

        // Vérifier si la formation a un quiz d'entrée
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('info', 'Cette formation n\'a pas de quiz d\'entrée.');
        }

        $formationUser = $this->fetchFormationUser($team, $formation, $user);
        $isFormationCompleted = $this->studentFormationService->isFormationCompleted($user, $formation);
        $isPostAttempt = $this->needsPostEntryQuizRetake($formationUser, true, $isFormationCompleted);

        if (! $isPostAttempt && $formationUser?->entry_quiz_attempt_id) {
            $entryScore = (float) ($formationUser->entry_quiz_score ?? 0);
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
            'questions',
            'isPostAttempt'
        ));
    }

    /**
     * Soumettre les r├®ponses du quiz d'entr├®e
     */
    public function submitEntryQuiz(Team $team, Formation $formation, Request $request)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit ├á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            return response()->json(['error' => 'Non autoris├®'], 403);
        }

        // V├®rifier si la formation a un quiz d'entr├®e
        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz) {
            return response()->json(['error' => 'Quiz d\'entr├®e non trouv├®'], 404);
        }

        $answers = $request->input('answers', []);

        // Utiliser le QuizService pour soumettre le quiz
        $quizService = app(\App\Services\Quiz\QuizService::class);
        $result = $quizService->submitFormationQuiz($user, $entryQuiz, $answers, \App\Models\QuizAttempt::TYPE_PRE);

        // Mettre ├á jour la progression de l'├®tudiant dans la formation
        $formation->learners()->syncWithoutDetaching([
            $user->id => [
                'entry_quiz_attempt_id' => $result['attempt_id'],
                'entry_quiz_score' => $result['score'],
                'entry_quiz_completed_at' => now(),
                'last_seen_at' => now(),
            ],
        ]);

        // V├®rifier si l'├®tudiant a r├®ussi le quiz
        [$minScore, $maxScore] = $this->resolveEntryQuizThresholds($entryQuiz);

        if ($result['score'] < $minScore) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('error', 'Votre niveau actuel est insuffisant pour cette formation (score: '.round($result['score'], 1).'%). Un formateur vous contactera pour vous orienter vers un parcours plus adapt├®.');
        }

        if ($result['score'] > $maxScore) {
            return redirect()->route('eleve.formation.show', [$team, $formation])
                ->with('error', 'Votre niveau est trop ├®lev├® pour cette formation (score: '.round($result['score'], 1).'%). Un superadmin vous contactera pour vous proposer une formation plus adapt├®e.');
        }

        return redirect()->route('eleve.formation.show', [$team, $formation])
            ->with('success', 'F├®licitations ! Votre niveau correspond parfaitement ├á cette formation (score: '.round($result['score'], 1).'%). Vous pouvez maintenant commencer.');
    }

    /**
     * Afficher les r├®sultats du quiz d'entr├®e
     */
    public function entryQuizResults(Team $team, Formation $formation, QuizAttempt $attempt)
    {
        $user = Auth::user();

        // V├®rifier si l'├®tudiant est inscrit ├á la formation
        if (! $this->studentFormationService->isEnrolledInFormation($user, $formation, $team)) {
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
        }

        // V├®rifier que la tentative appartient ├á l'utilisateur connect├®
        if ($attempt->user_id !== $user->id) {
            abort(403, 'Tentative non autoris├®e.');
        }

        // V├®rifier que c'est bien une tentative de quiz d'entr├®e
        if ($attempt->attempt_type !== \App\Models\QuizAttempt::TYPE_PRE) {
            abort(404, 'R├®sultats non trouv├®s.');
        }

        $entryQuiz = $formation->entryQuiz;
        if (! $entryQuiz || $attempt->quiz_id !== $entryQuiz->id) {
            abort(404, 'Quiz non trouv├®.');
        }

        // R├®cup├®rer les r├®ponses de cette tentative
        $answers = $attempt->answers()->with(['question', 'choice'])->get();

        // R├®cup├®rer les informations du quiz
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
            abort(403, 'Vous n\'etes pas inscrit à cette formation');
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
