<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /** @use HasFactory<\Database\Factories\LessonFactory> */
    use HasFactory;

    public $fillable = [
        'chapter_id',
        'title',
        'position',
        'lessonable_type',
        'lessonable_id',
    ];

    protected function casts(): array
    {
        return [
            'chapter_id' => 'integer',
            'position' => 'integer',
            'lessonable_id' => 'integer',
        ];
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function learners()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withPivot(['status', 'watched_seconds', 'best_score', 'max_score', 'attempts', 'read_percent', 'started_at', 'last_activity_at', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Get the lessonable content (Quiz, VideoContent, or TextContent)
     */
    public function lessonable()
    {
        return $this->morphTo();
    }

    /**
     * Get the quizzes associated with this lesson
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the display name for the lesson, falling back to the base title.
     */
    public function getName(): string
    {
        $lessonableTitle = data_get($this->lessonable, 'title');

        if (is_string($lessonableTitle) && $lessonableTitle !== '') {
            return $lessonableTitle;
        }

        if (is_string($this->title) && $this->title !== '') {
            return $this->title;
        }

        return 'Lesson #' . ($this->id ?? '?');
    }
}
