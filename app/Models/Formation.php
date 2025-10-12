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
}
