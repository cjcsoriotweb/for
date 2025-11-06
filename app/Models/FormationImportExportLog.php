<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Formation Import/Export Log Model
 *
 * Tracks all import and export operations for formations, including
 * success/failure status, statistics, and error messages.
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $formation_id
 * @property string $type (import|export)
 * @property string $format (zip|json|csv)
 * @property string $filename
 * @property string $status (success|failed|partial)
 * @property string|null $error_message
 * @property array|null $stats
 * @property int|null $file_size
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
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

    /**
     * Get the user who performed the import/export.
     *
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formation associated with this log.
     *
     * @return BelongsTo<Formation>
     */
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }

    /**
     * Scope a query to only include import logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeImports($query)
    {
        return $query->where('type', 'import');
    }

    /**
     * Scope a query to only include export logs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExports($query)
    {
        return $query->where('type', 'export');
    }

    /**
     * Scope a query to only include successful operations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope a query to only include failed operations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include recent operations within the given number of days.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days Number of days to look back
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
