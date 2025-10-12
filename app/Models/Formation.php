<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'team_id', 'title', 'summary', 'published',
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
    
    public function teams()
    {
        return $this->belongsToMany(\App\Models\Team::class, 'formation_team')
            ->withPivot(['visible','approved_at','approved_by','starts_at','ends_at'])
            ->withTimestamps();
    }

    // Scope pratique : formations visibles pour une team donnée (maintenant)
    public function scopeVisibleForTeam($q, int $teamId)
    {
        $now = now();
        return $q->whereHas('teams', function ($qq) use ($teamId, $now) {
            $qq->where('team_id', $teamId)
            ->where('visible', true)
            ->whereNotNull('approved_at')
            ->where(function ($w) use ($now) {
                $w->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($w) use ($now) {
                $w->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
        });
    }

    public function scopeAvailableInTeam($query, $team): \Illuminate\Database\Eloquent\Builder
{
    $teamId = $team instanceof \App\Models\Team ? $team->id : (int) $team;
    $now = now();

    if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'team_id')) {
        // Ownership : "dans la team" = appartient à cette team
        return $query->where('team_id', $teamId);
    }

    // Pivot : disponible et visible pour cette team
    return $query->whereHas('teams', function ($q) use ($teamId, $now) {
        $q->where('team_id', $teamId)
          ->where('visible', true)
          ->whereNotNull('approved_at')
          ->where(function ($w) use ($now) {
              $w->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
          })
          ->where(function ($w) use ($now) {
              $w->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
          });
    });
}


    // Formation dans team
    public function scopeInTeam($query, $team): \Illuminate\Database\Eloquent\Builder
    {
        $teamId = $team instanceof \App\Models\Team ? $team->id : (int) $team;

        // Cas 1 : ownership direct (colonne team_id)
        if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'team_id')) {
            return $query->where('team_id', $teamId);
        }

        // Cas 2 : via pivot formation_team (disponible pour la team)
        return $query->whereHas('teams', function ($q) use ($teamId) {
            $q->where('team_id', $teamId);
        });
    }

    public function learners() // utilisateurs inscrits
    {
        return $this->belongsToMany(User::class, 'formation_user')
            ->withPivot(['status','progress_percent','current_lesson_id','enrolled_at','last_seen_at','completed_at','score_total','max_score_total'])
            ->withTimestamps();
    }

}
