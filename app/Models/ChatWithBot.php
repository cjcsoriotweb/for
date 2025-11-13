<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatWithBot extends Model
{
    public $fillable = [
        'text',
        'user_id',
        'reply',
        'see',
        'conversation',
    ];
}
