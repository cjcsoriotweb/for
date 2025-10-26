<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\UserActivityLogFactory> */
    use HasFactory;

    public $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'referrer',
        'started_at',
        'ended_at',
        'duration_seconds',
        'request_data',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'duration_seconds' => 'integer',
            'request_data' => 'array',
        ];
    }

    /**
     * Get the user that owns the activity log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by session
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_seconds < 60) {
            return $this->duration_seconds . 's';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        if ($minutes < 60) {
            return $minutes . 'min ' . $seconds . 's';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return $hours . 'h ' . $remainingMinutes . 'min';
    }

    /**
     * Get formatted IP address with location info if available
     */
    public function getFormattedIpAttribute(): string
    {
        if (!$this->ip_address) {
            return 'N/A';
        }

        return $this->ip_address;
    }

    /**
     * Get browser information from user agent
     */
    public function getBrowserInfoAttribute(): ?string
    {
        if (!$this->user_agent) {
            return null;
        }

        // Simple browser detection (could be enhanced with a proper library)
        if (str_contains($this->user_agent, 'Chrome')) {
            return 'Chrome';
        } elseif (str_contains($this->user_agent, 'Firefox')) {
            return 'Firefox';
        } elseif (str_contains($this->user_agent, 'Safari')) {
            return 'Safari';
        } elseif (str_contains($this->user_agent, 'Edge')) {
            return 'Edge';
        }

        return 'Other';
    }

    /**
     * Get device type from user agent
     */
    public function getDeviceTypeAttribute(): string
    {
        if (!$this->user_agent) {
            return 'Unknown';
        }

        if (str_contains($this->user_agent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($this->user_agent, 'Tablet')) {
            return 'Tablet';
        }

        return 'Desktop';
    }
}
