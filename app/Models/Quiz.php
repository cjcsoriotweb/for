<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    /** @use HasFactory<\Database\Factories\QuizFactory> */
    use HasFactory;

    public $fillable = [
        'lesson_id',
        'title',
        'description',
        'passing_score',
        'max_attempts',
    ];

    protected function casts(): array
    {
        return [
            'lesson_id' => 'integer',
            'passing_score' => 'integer',
            'max_attempts' => 'integer',
        ];
    }

    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get the lesson that owns the quiz (polymorphic)
     */
    public function lessonable()
    {
        return $this->morphOne(Lesson::class, 'lessonable');
    }
}
