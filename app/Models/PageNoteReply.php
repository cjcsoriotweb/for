<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageNoteReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_note_id',
        'user_id',
        'content',
    ];

    public function pageNote()
    {
        return $this->belongsTo(PageNote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
