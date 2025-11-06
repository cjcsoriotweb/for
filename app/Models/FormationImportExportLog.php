<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationImportExportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'formation_id',
        'type',
        'format',
        'filename',
        'status',
        'error_message',
        'stats',
        'file_size',
    ];

    protected $casts = [
        'stats' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    public function scopeImports($query)
    {
        return $query->where('type', 'import');
    }

    public function scopeExports($query)
    {
        return $query->where('type', 'export');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
