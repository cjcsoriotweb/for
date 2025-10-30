<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
        'title',
        'content',
        'is_resolved',
        'is_hidden',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(PageNoteReply::class)->with('user:id,name')->latest();
    }
}
