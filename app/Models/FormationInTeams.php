<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FormationInTeams extends Model
{
    public $fillable = [
        'formation_id',
        'team_id',
        'visible',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // --- Scopes pratiques ---
    public function scopeTeam(Builder $q, Team|int $team): Builder
    {
        $id = $team instanceof Team ? $team->id : $team;

        return $q->where('team_id', $id);
    }

    public function scopeVisible(Builder $q, bool $value = true): Builder
    {
        return $q->where('visible', $value);
    }
}
