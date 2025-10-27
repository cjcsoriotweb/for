<?php

namespace App\Services\Formation;

use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Lesson;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class StudentFormationService extends BaseFormationService
{
    public function isFormationCompleted(User $user, Formation $formation): bool
    {
        // First check if student is enrolled in the formation
        if (! $this->isEnrolledInFormation($user, $formation)) {
            return false;
        }

        // Get formation with progress data
        $formationWithProgress = $this->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            return false;
        }

        // Check if all chapters are completed
        foreach ($formationWithProgress->chapters as $chapter) {
            if (! $this->isChapterCompleted($chapter, $user)) {
                return false;
            }
        }

        return true;
    }

    /**
     * List current formations for a specific student in a team.
     * Only returns formations where the student is enrolled and visible to their team.
     */
    public function listFormationCurrentByStudent(Team $team, User $user): Collection
    {
        return $this->list([
            'team' => $team,
            'user' => $user,
        ]);
    }

    /**
     * List all formations available for a team that a student can enroll in.
     * Returns formations visible to the team, regardless of student enrollment status.
     */
    public function listAvailableFormationsForTeam(Team $team): Collection
    {
        return Formation::withCount(['learners', 'lessons'])
            ->with(['lessons' => function ($query) {
                $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
            }])
            ->whereHas('teams', function (Builder $query) use ($team): void {
                $query->where('teams.id', $team->id)
                    ->where('formation_in_teams.visible', true);
            })
            ->get();
    }

    public function listAvailableFormationsForTeamExceptCurrentUseByMe(Team $team): Collection
    {
        return Formation::withCount(['learners', 'lessons'])
            ->with(['lessons' => function ($query) {
                $query->select('lessons.id', 'lessons.chapter_id', 'lessons.title', 'lessons.lessonable_type', 'lessons.lessonable_id');
            }])
            ->whereHas('teams', function (Builder $query) use ($team): void {
                $query->where('teams.id', $team->id)
                    ->where('formation_in_teams.visible', true);
            })
            ->whereDoesntHave('learners', function (Builder $q) {
                $q->where('users.id', Auth::user()->id);
                // Si tu n’as pas team_id sur ce pivot, supprime cette ligne.
            })
            ->get();
    }

    /**
     * Paginate current formations for a specific student in a team.
     */
    public function paginateFormationCurrentByStudent(Team $team, User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->paginate([
            'team' => $team,
            'user' => $user,
            'per_page' => $perPage,
        ]);
    }

    /**
     * Get a specific formation with student progress information.
     */
    public function getFormationWithProgress(Formation $formation, User $user): ?Formation
    {
        $formationData = Formation::where('id', $formation->id)
            ->whereHas('learners', function (Builder $query) use ($user): void {
                $query->where('user_id', $user->id);
            })
            ->with([
                'learners' => function ($query) use ($user): void {
                    $query->where('user_id', $user->id)
                        ->select(['formation_id', 'user_id', 'status', 'current_lesson_id', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total']);
                },
                'chapters' => function ($query): void {
                    $query->orderBy('position')
                        ->with(['lessons' => function ($lessonQuery): void {
                            $lessonQuery->orderBy('position');
                        }]);
                },
                'completionDocuments' => function ($query): void {
                    $query->orderBy('created_at');
                },
            ])
            ->first();

        if ($formationData) {
            $this->addProgressionData($formationData, $user);
        }

        return $formationData;
    }

    /**
     * Add progression data to chapters and lessons
     */
    private function addProgressionData(Formation $formation, User $user): void
    {
        $previousChapterCompleted = true;
        $currentChapter = null;
        $currentLesson = null;

        foreach ($formation->chapters as $index => $chapter) {
            $chapterCompleted = $this->isChapterCompleted($chapter, $user);

            // Determine chapter accessibility
            $chapter->is_accessible = $previousChapterCompleted;
            $chapter->is_completed = $chapterCompleted;
            $chapter->is_current = false;

            if (! $chapterCompleted && $previousChapterCompleted && ! $currentChapter) {
                $currentChapter = $chapter;
                $chapter->is_current = true;
            }

            // Process lessons within the chapter
            if ($chapter->is_accessible) {
                $this->processChapterLessons($chapter, $user, $currentChapter === $chapter);
            }

            $previousChapterCompleted = $chapterCompleted;
        }
    }

    /**
     * Process lessons within a chapter to determine their state
     */
    private function processChapterLessons(Chapter $chapter, User $user, bool $isCurrentChapter): void
    {
        $previousLessonCompleted = true;

        foreach ($chapter->lessons as $lessonIndex => $lesson) {
            $lessonCompleted = $this->isLessonCompleted($lesson, $user);

            // Determine lesson state
            $lesson->is_accessible = $previousLessonCompleted;
            $lesson->is_completed = $lessonCompleted;
            $lesson->is_current = false;

            // If this is the current chapter and lesson is not completed and previous is completed
            if ($isCurrentChapter && ! $lessonCompleted && $previousLessonCompleted) {
                $lesson->is_current = true;
            }

            $previousLessonCompleted = $lessonCompleted;
        }
    }

    /**
     * Check if a chapter is completed by the user
     */
    private function isChapterCompleted(Chapter $chapter, User $user): bool
    {
        $totalLessons = $chapter->lessons->count();
        if ($totalLessons === 0) {
            return false;
        }

        $completedLessons = 0;
        foreach ($chapter->lessons as $lesson) {
            if ($this->isLessonCompleted($lesson, $user)) {
                $completedLessons++;
            }
        }

        return $completedLessons === $totalLessons;
    }

    /**
     * Check if a lesson is completed by the user
     */
    private function isLessonCompleted(Lesson $lesson, User $user): bool
    {
        $pivot = $lesson->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot;

        return $pivot && $pivot->status === 'completed';
    }

    /**
     * Get the current chapter the user should be working on
     */
    public function getCurrentChapter(Formation $formation, User $user): ?Chapter
    {
        $formationWithProgress = $this->getFormationWithProgress($formation, $user);

        if (! $formationWithProgress) {
            return null;
        }

        foreach ($formationWithProgress->chapters as $chapter) {
            if ($chapter->is_current ?? false) {
                return $chapter;
            }
        }

        return null;
    }

    /**
     * Get the current lesson the user should be working on
     */
    public function getCurrentLesson(Formation $formation, User $user): ?Lesson
    {
        $currentChapter = $this->getCurrentChapter($formation, $user);

        if (! $currentChapter) {
            return null;
        }

        foreach ($currentChapter->lessons as $lesson) {
            if ($lesson->is_current ?? false) {
                return $lesson;
            }
        }

        return null;
    }

    /**
     * Check if a student is enrolled in a specific formation.
     */
    public function isEnrolledInFormation(User $user, Formation $formation, ?Team $team = null): bool
    {
        $query = $formation->learners()
            ->where('user_id', $user->id);

        if ($team) {
            $query->where('team_id', $team->id);
        }

        return $query->exists();
    }

    /**
     * Get student's progress in a specific formation.
     */
    public function getStudentProgress(User $user, Formation $formation): ?array
    {
        $pivot = $formation->learners()
            ->where('user_id', $user->id)
            ->first()?->pivot;

        if (! $pivot) {
            return null;
        }

        // Calculate real progress based on completed lessons
        $calculatedProgressPercent = $this->calculateFormationProgress($user, $formation);

        return [
            'status' => $pivot->status,
            'progress_percent' => $calculatedProgressPercent,
            'current_lesson_id' => $pivot->current_lesson_id,
            'enrolled_at' => $pivot->enrolled_at,
            'last_seen_at' => $pivot->last_seen_at,
            'completed_at' => $pivot->completed_at,
            'score_total' => $pivot->score_total,
            'max_score_total' => $pivot->max_score_total,
        ];
    }

    /**
     * Calculate formation progress based on completed lessons
     */
    private function calculateFormationProgress(User $user, Formation $formation): float
    {
        $totalLessons = $formation->chapters()
            ->with('lessons')
            ->get()
            ->pluck('lessons')
            ->flatten()
            ->count();

        if ($totalLessons === 0) {
            return 0.0;
        }

        $completedLessons = 0;

        foreach ($formation->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                $lessonProgress = $lesson->learners()
                    ->where('user_id', $user->id)
                    ->first();

                if ($lessonProgress && $lessonProgress->pivot->status === 'completed') {
                    $completedLessons++;
                }
            }
        }

        return $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0.0;
    }

    /**
     * Decorate the query to filter formations based on student enrollment and team visibility.
     */
    protected function decorateQuery(Builder $query, array $options): Builder
    {
        // Apply team visibility filter if team is provided
        if (! empty($options['team']) && $options['team'] instanceof Team) {
            $query->whereHas('teams', function (Builder $teamQuery) use ($options): void {
                $teamQuery->where('teams.id', $options['team']->id)
                    ->where('formation_in_teams.visible', true);
            });
        }

        // Apply student enrollment filter if user is provided
        if (! empty($options['user']) && $options['user'] instanceof User) {
            $query->whereHas('learners', function (Builder $learnerQuery) use ($options): void {
                $learnerQuery->where('user_id', $options['user']->id);
            });
        }

        return $query;
    }
}
