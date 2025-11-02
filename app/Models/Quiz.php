<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    /** @use HasFactory<\Database\Factories\QuizFactory> */
    use HasFactory;

    public const TYPE_LESSON = 'lesson';

    public const TYPE_ENTRY = 'entry';

    public $fillable = [
        'lesson_id',
        'formation_id',
        'title',
        'description',
        'type',
        'passing_score',
        'max_attempts',
    ];

    protected function casts(): array
    {
        return [
            'lesson_id' => 'integer',
            'formation_id' => 'integer',
            'passing_score' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get the lesson that owns the quiz
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function isEntryQuiz(): bool
    {
        return $this->type === self::TYPE_ENTRY;
    }
}
