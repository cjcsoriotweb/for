<?php

namespace App\Http\Controllers\Clean\Organisateur;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Clean\Account\AccountService;
use App\Services\Formation\StudentFormationService;
use App\Services\UserActivityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrganisateurPageController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
        private readonly StudentFormationService $studentFormationService,
        private readonly UserActivityService $userActivityService,
    ) {}

    public function home(Team $team)
    {
        $user = Auth::user();

        // R├®cup├®rer toutes les formations visibles pour cette ├®quipe
        $formations = $this->studentFormationService->listAvailableFormationsForTeam($team);

        return view('clean.organisateur.home', compact(
            'team',
            'formations'
        ));
    }

    public function students(Request $request, Team $team, \App\Models\Formation $formation)
    {
        // V├®rifier que la formation est bien accessible ├á cette ├®quipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // R├®cup├®rer les ├®tudiants inscrits ├á cette formation avec leurs donn├®es de pivot
        $students = $formation->learners()
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total', 'enrollment_cost'])
            ->orderBy('formation_user.enrolled_at', 'desc')
            ->get();

        // Ajouter les donn├®es de le├ºons pour chaque ├®tudiant
        foreach ($students as $student) {
            $student->lessons = $formation->lessons()
                ->with(['chapter' => function ($query) {
                    $query->orderBy('position');
                }])
                ->orderBy('position')
                ->get();

            // Ajouter les donn├®es de progression pour chaque le├ºon
            foreach ($student->lessons as $lesson) {
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $student->id)
                    ->first();

                if ($lessonProgress) {
                    $lesson->pivot = $lessonProgress->pivot;
                } else {
                    $lesson->pivot = (object) [
                        'status' => 'enrolled',
                        'started_at' => null,
                        'last_activity_at' => null,
                        'completed_at' => null,
                        'watched_seconds' => 0,
                        'read_percent' => 0,
                        'attempts' => 0,
                        'best_score' => 0,
                        'max_score' => 0,
                    ];
                }
            }
        }

        $search = trim($request->query('search', ''));
        $statusFilter = $request->query('status');

        $statusPriority = [
            'completed' => 3,
            'in_progress' => 2,
            'enrolled' => 1,
        ];

        $sortedStudents = $students->sortByDesc(function ($student) use ($statusPriority) {
            return $statusPriority[$student->pivot->status] ?? 0;
        })->values();

        $filteredStudents = $sortedStudents
            ->when($search, function ($collection) use ($search) {
                $needle = mb_strtolower($search);

                return $collection->filter(function ($student) use ($needle) {
                    return str_contains(mb_strtolower($student->name), $needle)
                        || str_contains(mb_strtolower($student->email), $needle);
                });
            })
            ->when($statusFilter, function ($collection) use ($statusFilter) {
                return $collection->filter(fn ($student) => $student->pivot->status === $statusFilter);
            })
            ->values();

        $totalFormationLessons = max($formation->lessons->count(), 1);

        $studentSummaries = $filteredStudents->map(function ($student) use ($totalFormationLessons) {
            $totalTime = 0;
            $lessonCount = 0;
            $completedLessons = 0;

            foreach ($student->lessons as $lesson) {
                $watchedSeconds = $lesson->pivot->watched_seconds ?? 0;

                if ($watchedSeconds > 0) {
                    $totalTime += $watchedSeconds;
                    $lessonCount++;
                }

                if (($lesson->pivot->status ?? '') === 'completed') {
                    $completedLessons++;
                }
            }

            $totalHours = (int) floor($totalTime / 3600);
            $totalMinutes = (int) floor(($totalTime % 3600) / 60);
            $progressBase = max($totalFormationLessons, $student->lessons->count(), 1);
            $progressPercent = min(100, (int) round(($completedLessons / $progressBase) * 100));

            $statusMap = [
                'completed' => ['label' => 'Terminee', 'classes' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'],
                'in_progress' => ['label' => 'En cours', 'classes' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'],
                'enrolled' => ['label' => 'Inscrit', 'classes' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'],
            ];

            $status = $statusMap[$student->pivot->status] ?? $statusMap['enrolled'];

            $scorePercent = null;
            if ($student->pivot->score_total && $student->pivot->max_score_total) {
                $scorePercent = round(($student->pivot->score_total / $student->pivot->max_score_total) * 100, 1);
            }

            return (object) [
                'student' => $student,
                'initials' => Str::upper(Str::substr($student->name, 0, 2)),
                'status_label' => $status['label'],
                'status_classes' => $status['classes'],
                'score_percent' => $scorePercent,
                'progress_percent' => $progressPercent,
                'completed_lessons' => $completedLessons,
                'progress_base' => $progressBase,
                'enrolled_at' => Carbon::make($student->pivot->enrolled_at),
                'last_seen_at' => Carbon::make($student->pivot->last_seen_at),
                'completed_at' => Carbon::make($student->pivot->completed_at),
                'has_time' => $totalTime > 0,
                'total_hours' => $totalHours,
                'total_minutes' => $totalMinutes,
                'lesson_count' => $lessonCount,
            ];
        });

        $totalSeconds = $students->reduce(function ($carry, $student) {
            return $carry + $student->lessons->sum(fn ($lesson) => $lesson->pivot->watched_seconds ?? 0);
        }, 0);

        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $monthlyEnrollments = $students->filter(function ($student) use ($currentMonthStart, $currentMonthEnd) {
            $enrolledAt = Carbon::make($student->pivot->enrolled_at);

            return $enrolledAt && $enrolledAt->between($currentMonthStart, $currentMonthEnd);
        });

        $monthlyCost = $monthlyEnrollments->reduce(function ($carry, $student) use ($formation) {
            $enrollmentCost = $student->pivot->enrollment_cost ?? $formation->money_amount ?? 0;

            return $carry + (int) $enrollmentCost;
        }, 0);

        $stats = [
            'total' => $students->count(),
            'completed' => $students->where('pivot.status', 'completed')->count(),
            'in_progress' => $students->where('pivot.status', 'in_progress')->count(),
            'time_hours' => (int) floor($totalSeconds / 3600),
            'time_minutes' => (int) floor(($totalSeconds % 3600) / 60),
        ];

        return view('clean.organisateur.students', [
            'team' => $team,
            'formation' => $formation,
            'students' => $students,
            'studentSummaries' => $studentSummaries,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'stats' => $stats,
            'monthlyCost' => $monthlyCost,
            'monthlyEnrollmentsCount' => $monthlyEnrollments->count(),
        ]);
    }


    public function studentsCost(Request $request, Team $team, \App\Models\Formation $formation)
    {
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        $availableEnrollments = $formation->learners()
            ->withPivot([
                'status',
                'enrolled_at',
                'last_seen_at',
                'completed_at',
                'score_total',
                'max_score_total',
                'enrollment_cost',
            ])
            ->orderByDesc('formation_user.enrolled_at')
            ->get();

        $availableMonths = $availableEnrollments
            ->pluck('pivot.enrolled_at')
            ->filter()
            ->map(fn ($date) => Carbon::make($date)?->format('Y-m'))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $selectedMonth = $request->query('month', Carbon::now()->format('Y-m'));

        try {
            $periodStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Exception $exception) {
            $periodStart = Carbon::now()->startOfMonth();
            $selectedMonth = $periodStart->format('Y-m');
        }

        $periodEnd = $periodStart->copy()->endOfMonth();

        $monthlyEnrollments = $availableEnrollments->filter(function ($student) use ($periodStart, $periodEnd) {
            $enrolledAt = Carbon::make($student->pivot->enrolled_at);

            return $enrolledAt && $enrolledAt->between($periodStart, $periodEnd);
        });

        $monthlyCost = $monthlyEnrollments->reduce(function ($carry, $student) use ($formation) {
            $enrollmentCost = $student->pivot->enrollment_cost ?? $formation->money_amount ?? 0;

            return $carry + (int) $enrollmentCost;
        }, 0);

        if ($availableMonths->doesntContain($selectedMonth)) {
            $availableMonths = $availableMonths->prepend($selectedMonth)->unique()->sortDesc()->values();
        }

        return view('clean.organisateur.students-cost', [
            'team' => $team,
            'formation' => $formation,
            'enrollments' => $monthlyEnrollments,
            'selectedMonth' => $selectedMonth,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'monthlyCost' => $monthlyCost,
            'availableMonths' => $availableMonths,
        ]);
    }

    public function studentReport(Request $request, Team $team, \App\Models\Formation $formation, \App\Models\User $student)
    {
        // V├®rifier que la formation est bien accessible ├á cette ├®quipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // V├®rifier que l'├®tudiant est bien inscrit ├á cette formation
        $isEnrolled = $formation->learners()
            ->where('user_id', $student->id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', '├ëtudiant non inscrit ├á cette formation.');
        }

        // R├®cup├®rer les donn├®es d├®taill├®es de l'├®tudiant
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total', 'enrollment_cost'])
            ->first();

        if ($studentData) {
            foreach (['enrolled_at', 'last_seen_at', 'completed_at'] as $field) {
                $studentData->pivot->{$field} = Carbon::make($studentData->pivot->{$field});
            }
        }

        if ($studentData) {
            foreach (['enrolled_at', 'last_seen_at', 'completed_at'] as $field) {
                $studentData->pivot->{$field} = Carbon::make($studentData->pivot->{$field});
            }
        }

        if ($studentData) {
            foreach (['enrolled_at', 'last_seen_at', 'completed_at'] as $field) {
                $studentData->pivot->{$field} = Carbon::make($studentData->pivot->{$field});
            }
        }

        // R├®cup├®rer toutes les le├ºons de la formation
        $lessons = $formation->lessons()
            ->with(['chapter' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        // Ajouter les donn├®es de progression pour l'├®tudiant sp├®cifique
        foreach ($lessons as $lesson) {
            $lessonProgress = $lesson->learners()
                ->where('user_id', $student->id)
                ->first();

            if ($lessonProgress) {
                $lesson->pivot = $lessonProgress->pivot;
            } else {
                // Cr├®er un objet pivot vide pour ├®viter les erreurs
                $lesson->pivot = (object) [
                    'status' => 'enrolled',
                    'started_at' => null,
                    'last_activity_at' => null,
                    'completed_at' => null,
                    'watched_seconds' => 0,
                    'read_percent' => 0,
                    'attempts' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                ];
            }
        }

        // R├®cup├®rer les tentatives de quiz
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $student->id)
            ->whereHas('lesson', function ($query) use ($formation) {
                $query->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'lesson.lessonable.quizQuestions.quizChoices',
                'answers.question.quizChoices',
                'answers.choice',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        // Calculer le temps total pass├®
        $totalTimeSeconds = $lessons->sum('pivot.watched_seconds') ?? 0;
        $totalHours = floor($totalTimeSeconds / 3600);
        $totalMinutes = floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        // Calculer le score moyen des quiz
        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(function ($attempt) {
                return $attempt->score ?? 0;
            });
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        // R├®cup├®rer les donn├®es d'activit├® de l'├®tudiant avec filtres
        $search = $request->get('activity_search');
        $lessonFilter = $request->get('lesson_filter');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $activityLogs = $this->userActivityService->getUserActivityLogs(
            $student->id,
            100,
            $startDate,
            $endDate,
            $search,
            $lessonFilter
        );
        $activitySummary = $this->userActivityService->getUserActivitySummary($student->id, $startDate, $endDate);

        return view('clean.organisateur.student-report', compact(
            'team',
            'formation',
            'student',
            'studentData',
            'lessons',
            'quizAttempts',
            'totalLessons',
            'completedLessons',
            'inProgressLessons',
            'notStartedLessons',
            'totalTimeSeconds',
            'totalHours',
            'totalMinutes',
            'totalSeconds',
            'averageQuizScore',
            'activityLogs',
            'activitySummary'
        ));
    }

    public function studentReportPdf(Team $team, \App\Models\Formation $formation, \App\Models\User $student)
    {
        // V├®rifier que la formation est bien accessible ├á cette ├®quipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // V├®rifier que l'├®tudiant est bien inscrit ├á cette formation
        $isEnrolled = $formation->learners()
            ->where('user_id', $student->id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', '├ëtudiant non inscrit ├á cette formation.');
        }

        // R├®cup├®rer les donn├®es d├®taill├®es de l'├®tudiant
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total', 'enrollment_cost'])
            ->first();

        // R├®cup├®rer toutes les le├ºons de la formation
        $lessons = $formation->lessons()
            ->with(['chapter' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        // Ajouter les donn├®es de progression pour l'├®tudiant sp├®cifique
        foreach ($lessons as $lesson) {
            $lessonProgress = $lesson->learners()
                ->where('user_id', $student->id)
                ->first();

            if ($lessonProgress) {
                $lesson->pivot = $lessonProgress->pivot;
            } else {
                // Cr├®er un objet pivot vide pour ├®viter les erreurs
                $lesson->pivot = (object) [
                    'status' => 'enrolled',
                    'started_at' => null,
                    'last_activity_at' => null,
                    'completed_at' => null,
                    'watched_seconds' => 0,
                    'read_percent' => 0,
                    'attempts' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                ];
            }
        }

        // R├®cup├®rer les tentatives de quiz
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $student->id)
            ->whereHas('lesson', function ($query) use ($formation) {
                $query->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'lesson.lessonable.quizQuestions.quizChoices',
                'answers.question.quizChoices',
                'answers.choice',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        // Calculer le temps total pass├®
        $totalTimeSeconds = $lessons->sum('pivot.watched_seconds') ?? 0;
        $totalHours = floor($totalTimeSeconds / 3600);
        $totalMinutes = floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        // Calculer le score moyen des quiz
        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(function ($attempt) {
                return $attempt->score ?? 0;
            });
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        // G├®n├®rer le PDF
        $pdf = Pdf::loadView('clean.organisateur.student-report-pdf', compact(
            'team',
            'formation',
            'student',
            'studentData',
            'lessons',
            'quizAttempts',
            'totalLessons',
            'completedLessons',
            'inProgressLessons',
            'notStartedLessons',
            'totalTimeSeconds',
            'totalHours',
            'totalMinutes',
            'totalSeconds',
            'averageQuizScore'
        ));

        // Retourner le PDF pour affichage dans iframe
        return $pdf->stream('rapport-'.$student->name.'.pdf');
    }

    public function studentReportPdfDownload(Team $team, \App\Models\Formation $formation, \App\Models\User $student)
    {
        // V├®rifier que la formation est bien accessible ├á cette ├®quipe
        $isFormationVisible = $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();

        if (! $isFormationVisible) {
            return redirect()->route('organisateur.index', $team)
                ->with('error', 'Formation non accessible.');
        }

        // V├®rifier que l'├®tudiant est bien inscrit ├á cette formation
        $isEnrolled = $formation->learners()
            ->where('user_id', $student->id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('organisateur.formations.students', [$team, $formation])
                ->with('error', '├ëtudiant non inscrit ├á cette formation.');
        }

        // R├®cup├®rer les donn├®es d├®taill├®es de l'├®tudiant
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot(['status', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total', 'enrollment_cost'])
            ->first();

        // R├®cup├®rer toutes les le├ºons de la formation
        $lessons = $formation->lessons()
            ->with(['chapter' => function ($query) {
                $query->orderBy('position');
            }])
            ->orderBy('position')
            ->get();

        // Ajouter les donn├®es de progression pour l'├®tudiant sp├®cifique
        foreach ($lessons as $lesson) {
            $lessonProgress = $lesson->learners()
                ->where('user_id', $student->id)
                ->first();

            if ($lessonProgress) {
                $lesson->pivot = $lessonProgress->pivot;
            } else {
                // Cr├®er un objet pivot vide pour ├®viter les erreurs
                $lesson->pivot = (object) [
                    'status' => 'enrolled',
                    'started_at' => null,
                    'last_activity_at' => null,
                    'completed_at' => null,
                    'watched_seconds' => 0,
                    'read_percent' => 0,
                    'attempts' => 0,
                    'best_score' => 0,
                    'max_score' => 0,
                ];
            }
        }

        // R├®cup├®rer les tentatives de quiz
        $quizAttempts = \App\Models\QuizAttempt::where('user_id', $student->id)
            ->whereHas('lesson', function ($query) use ($formation) {
                $query->whereHas('chapter', function ($chapterQuery) use ($formation) {
                    $chapterQuery->where('formation_id', $formation->id);
                });
            })
            ->with([
                'lesson.lessonable.quizQuestions.quizChoices',
                'answers.question.quizChoices',
                'answers.choice',
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        // Calculer le temps total pass├®
        $totalTimeSeconds = $lessons->sum('pivot.watched_seconds') ?? 0;
        $totalHours = floor($totalTimeSeconds / 3600);
        $totalMinutes = floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        // Calculer le score moyen des quiz
        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(function ($attempt) {
                return $attempt->score ?? 0;
            });
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        // G├®n├®rer le PDF
        $pdf = Pdf::loadView('clean.organisateur.student-report-pdf', compact(
            'team',
            'formation',
            'student',
            'studentData',
            'lessons',
            'quizAttempts',
            'totalLessons',
            'completedLessons',
            'inProgressLessons',
            'notStartedLessons',
            'totalTimeSeconds',
            'totalHours',
            'totalMinutes',
            'totalSeconds',
            'averageQuizScore'
        ));

        // T├®l├®charger le PDF
        return $pdf->download('rapport-'.$student->name.'-'.now()->format('Y-m-d').'.pdf');
    }
}
