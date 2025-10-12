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

}
