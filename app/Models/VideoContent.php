<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoContent extends Model
{

    /** @use HasFactory<\Database\Factories\VideoContentFactory> */
    use HasFactory;

    public $fillable = [
        'lesson_id',
        'title',
        'description',
        'video_url',
        'video_path',
        'duration_minutes',
    ];

    protected function casts(): array
    {
        return [
            'lesson_id' => 'integer',
            'duration_minutes' => 'integer',
        ];
    }



    /**
     * Get the lesson that owns the video content (polymorphic)
     */
    public function lessonable()
    {
        return $this->morphOne(Lesson::class, 'lessonable');
    }
}
