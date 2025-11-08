<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantMessage extends Model
{
    public $fillable = [
        'ai_trainer_id',
        'text',
        'user_id',
        'is_ia'
    ];
    //
}
