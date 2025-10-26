<?php

namespace App\Services\Clean\Organisateur;

use App\Models\Formation;
use App\Models\QuizAttempt;
use App\Models\Team;
use App\Models\User;
use App\Services\Formation\StudentFormationService;
use App\Services\UserActivityService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrganisateurService
{
    public function __construct(
        private readonly StudentFormationService $studentFormationService,
        private readonly UserActivityService $userActivityService,
    ) {}

    public function listVisibleFormations(Team $team): Collection
    {
        return $this->studentFormationService->listAvailableFormationsForTeam($team);
    }

    public function formationIsVisibleToTeam(Team $team, Formation $formation): bool
    {
        return $formation->teams()
            ->where('teams.id', $team->id)
            ->where('formation_in_teams.visible', true)
            ->exists();
    }

    public function studentIsEnrolledInFormation(Formation $formation, User $student): bool
    {
        return $formation->learners()
            ->where('user_id', $student->id)
            ->exists();
    }

    /**
     * Build the overview needed for the organiser students page.
     *
     * @return array{
     *     students: \Illuminate\Support\Collection,
     *     studentSummaries: \Illuminate\Support\Collection,
     *     stats: array{total:int,completed:int,in_progress:int,time_hours:int,time_minutes:int},
     *     monthlyCost: int,
     *     monthlyEnrollmentsCount: int
     * }
     */
    public function getStudentsOverview(Formation $formation, Team $team, array $filters = []): array
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $statusFilter = $filters['status'] ?? null;

        $students = $formation->learners()
            ->withPivot([
                'status',
                'enrolled_at',
                'last_seen_at',
                'completed_at',
                'score_total',
                'max_score_total',
                'enrollment_cost',
            ])
            ->wherePivot('team_id', $team->id)
            ->orderByDesc('formation_user.enrolled_at')
            ->get();
        $studentIds = $students->pluck('id');

        $lessons = $formation->lessons()
            ->with([
                'chapter' => fn($query) => $query->orderBy('position'),
                'learners' => fn($query) => $query->whereIn('user_id', $studentIds),
            ])
            ->orderBy('position')
            ->get();

        foreach ($students as $student) {
            $studentLessons = $lessons->map(function ($lesson) use ($student) {
                $lessonClone = clone $lesson;
                $progress = $lesson->learners->firstWhere('pivot.user_id', $student->id);

                $lessonClone->setRelation('learners', collect());
                $lessonClone->pivot = $progress?->pivot ?? $this->emptyLessonPivot();

                return $lessonClone;
            });

            $student->setRelation('lessons', $studentLessons);
        }

        $statusPriority = [
            'completed' => 3,
            'in_progress' => 2,
            'enrolled' => 1,
        ];

        $sortedStudents = $students->sortByDesc(function ($student) use ($statusPriority) {
            return $statusPriority[$student->pivot->status] ?? 0;
        })->values();

        $filteredStudents = $sortedStudents
            ->when($search !== '', function (Collection $collection) use ($search) {
                $needle = mb_strtolower($search);

                return $collection->filter(function ($student) use ($needle) {
                    return str_contains(mb_strtolower($student->name), $needle)
                        || str_contains(mb_strtolower($student->email), $needle);
                });
            })
            ->when($statusFilter, function (Collection $collection) use ($statusFilter) {
                return $collection->filter(fn($student) => $student->pivot->status === $statusFilter);
            })
            ->values();

        $totalFormationLessons = max($lessons->count(), 1);

        $studentSummaries = $filteredStudents->map(function ($student) use ($totalFormationLessons) {
            $totalTime = 0;
            $lessonCount = 0;
            $completedLessons = 0;

            foreach ($student->lessons as $lesson) {
                $watchedSeconds = (int) ($lesson->pivot->watched_seconds ?? 0);

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
                'completed' => [
                    'label' => 'Terminee',
                    'classes' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                ],
                'in_progress' => [
                    'label' => 'En cours',
                    'classes' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                ],
                'enrolled' => [
                    'label' => 'Inscrit',
                    'classes' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                ],
            ];

            $status = $statusMap[$student->pivot->status] ?? $statusMap['enrolled'];

            $scorePercent = null;
            if (! empty($student->pivot->score_total) && ! empty($student->pivot->max_score_total)) {
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
            return $carry + $student->lessons->sum(
                fn($lesson) => (int) ($lesson->pivot->watched_seconds ?? 0)
            );
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

        return [
            'students' => $students,
            'studentSummaries' => $studentSummaries,
            'stats' => $stats,
            'monthlyCost' => $monthlyCost,
            'monthlyEnrollmentsCount' => $monthlyEnrollments->count(),
        ];
    }

    /**
     * Build the data needed for the monthly cost page.
     *
     * @return array{
     *     enrollments: \Illuminate\Support\Collection,
     *     selectedMonth: string,
     *     periodStart: \Carbon\Carbon,
     *     periodEnd: \Carbon\Carbon,
     *     monthlyCost: int,
     *     availableMonths: \Illuminate\Support\Collection
     * }
     */
    public function getStudentsCostSummary(Formation $formation, ?string $selectedMonth = null): array
    {
        $enrollments = $formation->learners()
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

        $availableMonths = $enrollments
            ->pluck('pivot.enrolled_at')
            ->filter()
            ->map(fn($date) => Carbon::make($date)?->format('Y-m'))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $selectedMonth = $selectedMonth ?? Carbon::now()->format('Y-m');

        try {
            $periodStart = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        } catch (\Throwable $exception) {
            $periodStart = Carbon::now()->startOfMonth();
            $selectedMonth = $periodStart->format('Y-m');
        }

        $periodEnd = $periodStart->copy()->endOfMonth();

        $monthlyEnrollments = $enrollments->filter(function ($student) use ($periodStart, $periodEnd) {
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

        return [
            'enrollments' => $monthlyEnrollments,
            'selectedMonth' => $selectedMonth,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'monthlyCost' => $monthlyCost,
            'availableMonths' => $availableMonths,
        ];
    }

    /**
     * Build all data required for the student report (HTML or PDF).
     *
     * @return array{
     *     studentData: ?\App\Models\User,
     *     lessons: \Illuminate\Support\Collection,
     *     quizAttempts: \Illuminate\Support\Collection,
     *     totalLessons: int,
     *     completedLessons: int,
     *     inProgressLessons: int,
     *     notStartedLessons: int,
     *     totalTimeSeconds: int,
     *     totalHours: int,
     *     totalMinutes: int,
     *     totalSeconds: int,
     *     averageQuizScore: float|int,
     *     activityLogs: \Illuminate\Support\Collection|null,
     *     activitySummary: array|null
     * }
     */
    public function getStudentReportData(Formation $formation, User $student, array $activityFilters = [], bool $includeActivity = true): array
    {
        $studentData = $formation->learners()
            ->where('user_id', $student->id)
            ->withPivot([
                'status',
                'enrolled_at',
                'last_seen_at',
                'completed_at',
                'score_total',
                'max_score_total',
                'enrollment_cost',
            ])
            ->first();

        if ($studentData) {
            foreach (['enrolled_at', 'last_seen_at', 'completed_at'] as $field) {
                $studentData->pivot->{$field} = Carbon::make($studentData->pivot->{$field});
            }
        }

        $lessons = $formation->lessons()
            ->with([
                'chapter' => fn($query) => $query->orderBy('position'),
                'learners' => fn($query) => $query->where('user_id', $student->id),
            ])
            ->orderBy('position')
            ->get()
            ->map(function ($lesson) use ($student) {
                $lessonClone = clone $lesson;
                $progress = $lesson->learners->first();

                $lessonClone->setRelation('learners', collect());
                $lessonClone->pivot = $progress?->pivot ?? $this->emptyLessonPivot();

                return $lessonClone;
            });

        $quizAttempts = QuizAttempt::where('user_id', $student->id)
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
            ->orderByDesc('created_at')
            ->get();

        $totalLessons = $lessons->count();
        $completedLessons = $lessons->where('pivot.status', 'completed')->count();
        $inProgressLessons = $lessons->where('pivot.status', 'in_progress')->count();
        $notStartedLessons = $lessons->where('pivot.status', 'enrolled')->count();

        $totalTimeSeconds = $lessons->sum(fn($lesson) => (int) ($lesson->pivot->watched_seconds ?? 0));
        $totalHours = (int) floor($totalTimeSeconds / 3600);
        $totalMinutes = (int) floor(($totalTimeSeconds % 3600) / 60);
        $totalSeconds = $totalTimeSeconds % 60;

        $averageQuizScore = 0;
        if ($quizAttempts->count() > 0) {
            $totalQuizScore = $quizAttempts->sum(fn($attempt) => $attempt->score ?? 0);
            $averageQuizScore = round($totalQuizScore / $quizAttempts->count(), 1);
        }

        $activityLogs = null;
        $activitySummary = null;

        if ($includeActivity) {
            $activityLogs = $this->userActivityService->getUserActivityLogs(
                $student->id,
                100,
                $activityFilters['start_date'] ?? null,
                $activityFilters['end_date'] ?? null,
                $activityFilters['activity_search'] ?? null,
                $activityFilters['lesson_filter'] ?? null
            );

            $activitySummary = $this->userActivityService->getUserActivitySummary(
                $student->id,
                $activityFilters['start_date'] ?? null,
                $activityFilters['end_date'] ?? null
            );
        }

        return [
            'studentData' => $studentData,
            'lessons' => $lessons,
            'quizAttempts' => $quizAttempts,
            'totalLessons' => $totalLessons,
            'completedLessons' => $completedLessons,
            'inProgressLessons' => $inProgressLessons,
            'notStartedLessons' => $notStartedLessons,
            'totalTimeSeconds' => $totalTimeSeconds,
            'totalHours' => $totalHours,
            'totalMinutes' => $totalMinutes,
            'totalSeconds' => $totalSeconds,
            'averageQuizScore' => $averageQuizScore,
            'activityLogs' => $activityLogs,
            'activitySummary' => $activitySummary,
        ];
    }

    private function emptyLessonPivot(): object
    {
        return (object) [
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
