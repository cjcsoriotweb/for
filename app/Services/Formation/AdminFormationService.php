<?php

namespace App\Services\Formation;

use App\Models\Formation;
use App\Models\FormationInTeams;
use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AdminFormationService extends BaseFormationService
{
    public function makeFormationVisibleForTeam(Formation $formation, Team $team)
    {
        return FormationInTeams::updateOrCreate(
            [
                'formation_id' => $formation->id,
                'team_id' => $team->id,
            ],
            [
                'visible' => true,
            ],
        );
    }

    public function makeFormationInvisibleForTeam(Formation $formation, Team $team)
    {
        return FormationInTeams::where([
            'formation_id' => $formation->id,
            'team_id' => $team->id,
        ])->delete();
    }



    public function listWithTeamFlags(Team $team, array $options = []): Collection
    {
        return $this->list($options + ['team' => $team]);
    }

    public function listActiveForTeam(Team $team, array $options = []): Collection
    {
        return $this->list($options + [
            'team' => $team,
            'only_visible' => true,
        ]);
    }

    public function paginateWithTeamFlags(
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
        $onlyVisible = (bool) ($options['only_visible'] ?? false);

        if (!$team instanceof Team) {
            return $query;
        }

        $query->withCount([
            'teams as is_attached' => fn ($subQuery) => $subQuery->where('teams.id', $team->id),
            'teams as is_visible' => fn ($subQuery) => $subQuery
                ->where('teams.id', $team->id)
                ->where('formation_in_teams.visible', true),
        ]);

        if ($onlyVisible) {
            $query->whereHas('teams', function ($subQuery) use ($team): void {
                $subQuery
                    ->where('teams.id', $team->id)
                    ->wherePivot('visible', true);
            });
        }

        return $query;
    }
}
