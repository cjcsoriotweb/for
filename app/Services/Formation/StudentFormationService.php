<?php

namespace App\Services\Formation;

use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StudentFormationService extends BaseFormationService
{
    public function listVisibleForTeam(Team $team, array $options = []): Collection
    {
        return $this->list($options + ['team' => $team]);
    }

    public function paginateVisibleForTeam(
        Team $team,
        int $perPage = 15,
        ?string $search = null,
        ?string $orderBy = 'title',
        string $direction = 'asc'
    ): LengthAwarePaginator {
        $options = [
            'team' => $team,
            'per_page' => $perPage,
            'search' => $search,
            'order_by' => $orderBy,
            'direction' => $direction,
        ];

        return $this->paginate(array_filter(
            $options,
            static fn ($value) => !is_null($value)
        ));
    }

    protected function decorateQuery(Builder $query, array $options): Builder
    {
        $team = $options['team'] ?? null;

        if ($team instanceof Team) {
            $query->whereHas('teams', function ($subQuery) use ($team): void {
                $subQuery
                    ->where('teams.id', $team->id)
                    ->wherePivot('visible', true);
            });
        }

        return $query;
    }
}

