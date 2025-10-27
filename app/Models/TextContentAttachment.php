<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextContentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'text_content_id',
        'name',
        'file_path',
        'mime_type',
        'display_mode',
    ];

    /**
     * Attachment belongs to a text content.
     */
    public function textContent()
    {
        return $this->belongsTo(TextContent::class);
    }
}
