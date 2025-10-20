<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\QuizQuestionFactory> */
    use HasFactory;

    public $fillable = [
        'quiz_id',
        'question',
        'type',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function quizChoices()
    {
        return $this->hasMany(QuizChoice::class, 'question_id');
    }
}
