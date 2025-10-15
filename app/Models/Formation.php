<?php

namespace App\Models;

use Illuminate\Container\Attributes\DB;
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



   



}
