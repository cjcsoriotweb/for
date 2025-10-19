<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextContent extends Model
{
    /** @use HasFactory<\Database\Factories\TextContentFactory> */
    use HasFactory;

    public $fillable = [
        'lesson_id',
        'title',
        'description',
        'content',
        'estimated_read_time',
        'allow_download',
        'show_progress',
    ];

    protected function casts(): array
    {
        return [
            'lesson_id' => 'integer',
            'estimated_read_time' => 'integer',
            'allow_download' => 'boolean',
            'show_progress' => 'boolean',
        ];
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the lesson that owns the text content (polymorphic)
     */
    public function lessonable()
    {
        return $this->morphOne(Lesson::class, 'lessonable');
    }
}
