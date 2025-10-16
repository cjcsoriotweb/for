<?php

namespace App\Services;

use App\Models\Formation;
use App\Models\FormationInTeams;
use App\Models\FormationTeam;
use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FormationService
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
    public function listWithTeamFlags(Team $team): Collection
    {
        return Formation::query()
            ->withCount([
                'teams as is_attached' => fn($q) => $q->where('teams.id', $team->id),
                'teams as is_visible' => fn($q) => $q->where('teams.id', $team->id)->where('formation_in_teams.visible', true),
            ])
            ->get();
    }
    public function paginateWithTeamFlags(Team $team, int $perPage = 15, ?string $search = null, ?string $orderBy = 'title', string $direction = 'asc'): LengthAwarePaginator
    {
        $q = Formation::query()->withCount([
            'teams as is_attached' => fn($q) => $q->where('teams.id', $team->id),
            'teams as is_visible' => fn($q) => $q->where('teams.id', $team->id)->where('formation_in_teams.visible', true),
        ]);

        if ($search) {
            $q->where(fn($w) => $w->where('title', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%"));
        }

        if ($orderBy) {
            $q->orderBy($orderBy, $direction);
        }

        return $q->paginate($perPage)->withQueryString();
    }

    public function createFormation($title = 'Titre par défaut', $description = 'Description par défaut', $level = 'debutant', $money_amount = 0)
    {
        return Formation::create([
            'title' => $title,
            'description' => $description,
            'level' => $level,
            'money_amount' => $money_amount,
        ]);
    }
}
