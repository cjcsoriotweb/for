<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    /** @use HasFactory<\Database\Factories\ChapterFactory> */
    use HasFactory;
    
    protected function casts(): array
    {
        return [
            'formation_id' => 'integer',
            'position' => 'integer',
        ];
    }
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

}
