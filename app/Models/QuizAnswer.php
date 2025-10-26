<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    /** @use HasFactory<\Database\Factories\QuizAnswerFactory> */
    use HasFactory;

    public $fillable = [
        'quiz_attempt_id',
        'question_id',
        'choice_id',
        'text_answer',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'quiz_attempt_id' => 'integer',
            'question_id' => 'integer',
            'choice_id' => 'integer',
            'is_correct' => 'boolean',
        ];
    }

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    public function choice()
    {
        return $this->belongsTo(QuizChoice::class, 'choice_id');
    }
}
