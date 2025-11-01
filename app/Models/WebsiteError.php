<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteError extends Model
{
    use HasFactory;

    protected $fillable = [
        'error_code',
        'message',
        'url',
        'user_id',
        'ip_address',
        'user_agent',
        'request_data',
        'stack_trace',
        'resolved_at',
    ];

    protected $casts = [
        'request_data' => 'array',
        'resolved_at' => 'datetime',
    ];

    /**
     * User who encountered the error (if authenticated).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get unresolved errors.
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    /**
     * Scope to get errors by code.
     */
    public function scopeByCode($query, int $code)
    {
        return $query->where('error_code', $code);
    }

    /**
     * Mark this error as resolved.
     */
    public function markAsResolved(): void
    {
        $this->update(['resolved_at' => now()]);
    }

    /**
     * Check if this error is resolved.
     */
    public function isResolved(): bool
    {
        return $this->resolved_at !== null;
    }
}
