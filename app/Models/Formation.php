<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

/**
 * Modèle Formation
 *
 * Représente une formation dans le système d'apprentissage.
 * Une formation appartient à une équipe propriétaire et peut être visible pour d'autres équipes.
 */
class Formation extends Model
{
    protected $fillable = [
        'team_id',
        'title',
        'summary',
        'description',
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
                DB::raw('CASE WHEN ft.formation_id IS NULL THEN 0 ELSE 1 END AS is_linked'),
                DB::raw('ft.id AS pivot_id'),
                DB::raw('ft.team_id AS pivot_team_id'),
                DB::raw('ft.visible AS pivot_active'),   // adapte si tu as ce champ
                // DB::raw('ft.visible_at AS pivot_visible_at'),
            ]);
    }
}
