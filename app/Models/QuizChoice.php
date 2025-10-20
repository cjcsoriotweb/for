<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizChoice extends Model
{
    /** @use HasFactory<\Database\Factories\QuizChoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'question_id',
        'choice_text',
        'is_correct',
    ];
}
