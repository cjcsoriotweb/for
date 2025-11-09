<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\TextContent;
use App\Models\VideoContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\UserActivityLogFactory> */
    use HasFactory;

    private ?Lesson $resolvedLesson = null;
    private static array $formationTitlePlaceholders = [
        'titre par defaut',
        'titre par default',
        'titre par défaut',
        'nouvelle formation',
    ];

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
            return $this->duration_seconds.'s';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        if ($minutes < 60) {
            return $minutes.'min '.$seconds.'s';
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return $hours.'h '.$remainingMinutes.'min';
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
        if (! $this->user_agent) {
            return 'Unknown';
        }

        if (str_contains($this->user_agent, 'Mobile')) {
            return 'Mobile';
        } elseif (str_contains($this->user_agent, 'Tablet')) {
            return 'Tablet';
        }

        return 'Desktop';
    }

    public function getIsLessonAttribute(): bool
    {
        return (bool) $this->url && str_contains($this->url, '/lesson/');
    }

    public function getIsQuizAttribute(): bool
    {
        return (bool) $this->url && str_contains($this->url, '/quiz/');
    }

    /**
     * Get formation name from URL
     */
    public function getFormationName(): ?string
    {
        $formationId = $this->extractFormationIdFromUrl();

        if (! $formationId) {
            return null;
        }

        try {
            $formation = \App\Models\Formation::find($formationId);

            return $formation ? $formation->title : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get lesson name from URL
     */
    public function getLessonName(): ?string
    {
        try {
            if ($lesson = $this->resolveLesson()) {
                return $lesson->getName();
            }
        } catch (\Exception $e) {
            return null;
        }

        if (! $this->url) {
            return null;
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

    public function getFormationLabelAttribute(): string
    {
        $title = $this->getFormationName();

        if ($title && ! $this->isFormationTitlePlaceholder($title)) {
            return $title;
        }

        return $this->lesson_type_label;
    }

    private function isFormationTitlePlaceholder(string $title): bool
    {
        $normalized = Str::of($title)->ascii()->lower()->trim()->__toString();

        return in_array($normalized, self::$formationTitlePlaceholders, true);
    }

    public function getLessonTypeAttribute(): string
    {
        if ($lesson = $this->resolveLesson()) {
            return match ($lesson->lessonable_type) {
                VideoContent::class => 'video',
                TextContent::class => 'text',
                Quiz::class => 'quiz',
                default => 'lesson',
            };
        }

        if ($this->is_quiz) {
            return 'quiz';
        }

        if ($this->is_lesson) {
            return 'lesson';
        }

        return 'unknown';
    }

    public function getLessonTypeLabelAttribute(): string
    {
        return match ($this->lesson_type) {
            'quiz' => 'Quiz',
            'video' => 'Vidéo',
            'text' => 'Texte',
            'lesson' => 'Leçon',
            default => 'Page générale',
        };
    }

    private function resolveLesson(): ?Lesson
    {
        if ($this->resolvedLesson !== null) {
            return $this->resolvedLesson;
        }

        if (! $lessonId = $this->extractLessonIdFromUrl()) {
            return null;
        }

        $this->resolvedLesson = Lesson::find($lessonId);

        return $this->resolvedLesson;
    }

    /**
     * Get page type/category
     */
    public function getPageTypeAttribute(): string
    {
        if (! $this->url) {
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

    private function extractLessonIdFromUrl(): ?int
    {
        $segments = $this->getUrlSegments();

        if (false !== ($lessonIndex = array_search('lesson', $segments, true))) {
            $offset = $lessonIndex + 1;

            if (isset($segments[$offset]) && $segments[$offset] === 'quiz') {
                $offset++;
            }

            if (isset($segments[$offset + 3]) && ctype_digit($segments[$offset + 3])) {
                return (int) $segments[$offset + 3];
            }
        }

        $path = $this->getUrlPath();
        $pattern = '/\/eleve\/\d+\/formations\/\d+\/chapters\/\d+\/lessons\/(\d+)/';
        if (preg_match($pattern, $path, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function extractFormationIdFromUrl(): ?int
    {
        $segments = $this->getUrlSegments();

        if (false !== ($lessonIndex = array_search('lesson', $segments, true))) {
            $formationIndex = $lessonIndex + 2;

            if (isset($segments[$lessonIndex + 1]) && $segments[$lessonIndex + 1] === 'quiz') {
                $formationIndex++;
            }

            if (isset($segments[$formationIndex]) && ctype_digit($segments[$formationIndex])) {
                return (int) $segments[$formationIndex];
            }
        }

        $path = $this->getUrlPath();
        $patterns = [
            '/\/organisateur\/\d+\/formations\/(\d+)/',
            '/\/eleve\/formation\/\d+\/(\d+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $path, $matches)) {
                return (int) $matches[1];
            }
        }

        return null;
    }

    private function getUrlPath(): string
    {
        if (! $this->url) {
            return '';
        }

        return parse_url($this->url, PHP_URL_PATH) ?? '';
    }

    private function getUrlSegments(): array
    {
        $path = $this->getUrlPath();

        if ($path === '') {
            return [];
        }

        return array_values(array_filter(explode('/', $path), static fn ($segment) => $segment !== ''));
    }
}
