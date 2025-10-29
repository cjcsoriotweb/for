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
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
