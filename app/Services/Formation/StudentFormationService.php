<?php

namespace App\Services\Formation;

use App\Models\Formation;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StudentFormationService extends BaseFormationService
{
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
        return Formation::where('id', $formation->id)
            ->whereHas('learners', function (Builder $query) use ($user): void {
                $query->where('user_id', $user->id);
            })
            ->with(['learners' => function ($query) use ($user): void {
                $query->where('user_id', $user->id)
                    ->select(['formation_id', 'user_id', 'status', 'progress_percent', 'current_lesson_id', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total']);
            }])
            ->first();
    }

    /**
     * Check if a student is enrolled in a specific formation.
     */
    public function isEnrolledInFormation(User $user, Formation $formation): bool
    {
        return $formation->learners()
            ->where('user_id', $user->id)
            ->exists();
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

        return [
            'status' => $pivot->status,
            'progress_percent' => $pivot->progress_percent,
            'current_lesson_id' => $pivot->current_lesson_id,
            'enrolled_at' => $pivot->enrolled_at,
            'last_seen_at' => $pivot->last_seen_at,
            'completed_at' => $pivot->completed_at,
            'score_total' => $pivot->score_total,
            'max_score_total' => $pivot->max_score_total,
        ];
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
