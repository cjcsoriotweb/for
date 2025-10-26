<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    /** @use HasFactory<\Database\Factories\QuizAttemptFactory> */
    use HasFactory;

    public $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'max_score',
        'duration_seconds',
        'started_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'quiz_id' => 'integer',
            'score' => 'integer',
            'max_score' => 'integer',
            'duration_seconds' => 'integer',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->hasOneThrough(
            Lesson::class,
            Quiz::class,
            'id',        // Foreign key on quiz_attempts table (quiz_id)
            'id',        // Foreign key on lessons table (lesson_id)
            'quiz_id',   // Local key on quiz_attempts table
            'lesson_id'  // Local key on quizzes table
        );
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_attempt_id');
    }

    public function quizAnswers()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_attempt_id');
    }
}
