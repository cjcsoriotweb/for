<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB as FacadesDB;

class Formation extends Model
{
    protected $fillable = [
        'team_id',
        'title',
        'summary',
        'published',
    ];

    protected $casts = [
        'published' => 'bool',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }

    public function lessons()
    {
        // Formation (id) -> Chapters (formation_id) -> Lessons (chapter_id)
        return $this->hasManyThrough(
            Lesson::class,     // final
            Chapter::class,    // intermédiaire
            'formation_id',    // clé étrangère sur chapters -> formations.id
            'chapter_id',      // clé étrangère sur lessons -> chapters.id
            'id',              // clé locale formations.id
            'id'               // clé locale chapters.id
        );
    }

    public function learners() // utilisateurs inscrits
    {
        return $this->belongsToMany(User::class, 'formation_user')
            ->withPivot(['status', 'progress_percent', 'current_lesson_id', 'enrolled_at', 'last_seen_at', 'completed_at', 'score_total', 'max_score_total'])
            ->withTimestamps();
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'formation_teams')
            ->withTimestamps();
    }

    /* Scopes */

    public function scopeForTeam(Builder $query, int|Team $team): Builder
    {
        $teamId = $team instanceof Team ? $team->id : $team;

        return $query->whereHas('teams', function ($q) use ($teamId) {
            $q->where('teams.id', $teamId)
            ->where('formation_teams.visible', 1); // filtre pivot
            // ou: ->wherePivot('visible', true);  // fonctionne aussi sur BelongsToMany
        });
    }

    public function scopeAdminWithTeamLink(Builder $query, int|Team $team): Builder
    {
        $teamId = $team instanceof Team ? $team->id : $team;

        return $query
            ->leftJoin('formation_teams as ft', function ($join) use ($teamId) {
                $join->on('ft.formation_id', '=', 'formations.id')
                    ->where('ft.team_id', '=', $teamId);
            })
            ->select('formations.*')
            ->addSelect([
                FacadesDB::raw('CASE WHEN ft.formation_id IS NULL THEN 0 ELSE 1 END AS is_linked'),
                FacadesDB::raw('ft.id AS pivot_id'),
                FacadesDB::raw('ft.team_id AS pivot_team_id'),
                FacadesDB::raw('ft.visible AS pivot_active'),   // adapte si tu as ce champ
                // FacadesDB::raw('ft.visible_at AS pivot_visible_at'),
            ]);
    }
}
