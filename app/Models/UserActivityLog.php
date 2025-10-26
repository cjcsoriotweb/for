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
        if (! $this->ip_address) {
            return 'N/A';
        }

        return $this->ip_address;
    }

    /**
     * Get browser information from user agent
     */
    public function getBrowserInfoAttribute(): ?string
    {
        if (! $this->user_agent) {
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

    /**
     * Get formation name from URL
     */
    public function getFormationName(): ?string
    {
        if (!$this->url) {
            return null;
        }

        // Extract formation ID from URL pattern: /organisateur/{team}/formations/{formation}/...
        $pattern = '/\/organisateur\/\d+\/formations\/(\d+)/';
        if (preg_match($pattern, $this->url, $matches)) {
            try {
                $formation = \App\Models\Formation::find($matches[1]);
                return $formation ? $formation->title : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Get lesson name from URL
     */
    public function getLessonName(): ?string
    {
        if (!$this->url) {
            return null;
        }

        // Extract lesson ID from URL pattern: /eleve/{team}/formations/{formation}/chapters/{chapter}/lessons/{lesson}
        $pattern = '/\/eleve\/\d+\/formations\/\d+\/chapters\/\d+\/lessons\/(\d+)/';
        if (preg_match($pattern, $this->url, $matches)) {
            try {
                $lesson = \App\Models\Lesson::find($matches[1]);
                return $lesson ? $lesson->title : null;
            } catch (\Exception $e) {
                return null;
            }
        }

        // Check if it's a quiz attempt page
        if (str_contains($this->url, '/quiz/attempt')) {
            return 'Quiz en cours';
        }

        // Check if it's a lesson view page
        if (str_contains($this->url, '/lesson/')) {
            return 'Consultation leçon';
        }

        return null;
    }

    /**
     * Get page type/category
     */
    public function getPageTypeAttribute(): string
    {
        if (!$this->url) {
            return 'Unknown';
        }

        if (str_contains($this->url, '/quiz/')) {
            return 'Quiz';
        } elseif (str_contains($this->url, '/lesson/')) {
            return 'Leçon';
        } elseif (str_contains($this->url, '/formation/')) {
            return 'Formation';
        } elseif (str_contains($this->url, '/organisateur/')) {
            return 'Administration';
        } elseif (str_contains($this->url, '/eleve/')) {
            return 'Apprentissage';
        }

        return 'Autre';
    }
}
