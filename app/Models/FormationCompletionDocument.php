<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationCompletionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'formation_id',
        'title',
        'file_path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }
}
