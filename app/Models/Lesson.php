<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /** @use HasFactory<\Database\Factories\LessonFactory> */
    use HasFactory;
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
    public function learners()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withPivot(['status','watched_seconds','best_score','max_score','attempts','read_percent','started_at','last_activity_at','completed_at'])
            ->withTimestamps();
    }

}
