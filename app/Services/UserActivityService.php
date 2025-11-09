<?php

namespace App\Services;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserActivityService
{
    /**
     * Log user activity
     */
    public function logActivity(Request $request, ?int $userId = null): UserActivityLog
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            throw new \Exception('User ID is required for activity logging');
        }

        // Get client IP address
        $ipAddress = $this->getClientIp($request);

        // Get session ID
        $sessionId = Session::getId();

        // Prepare activity data
        $activityData = [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'referrer' => $request->header('referer'),
            'started_at' => now(),
            'request_data' => $this->getRequestData($request),
        ];

        // Before creating new activity, update the duration of the previous activity for this session
        $this->updatePreviousActivityDuration($userId, $sessionId);

        return UserActivityLog::create($activityData);
    }

    /**
     * Update the duration of the previous activity for this user/session
     */
    private function updatePreviousActivityDuration(int $userId, string $sessionId): void
    {
        // Find the most recent activity for this user and session that doesn't have an end time
        $previousActivity = UserActivityLog::forUser($userId)
            ->forSession($sessionId)
            ->whereNull('ended_at')
            ->whereNotNull('started_at')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($previousActivity) {
            $endedAt = now();
            $duration = $previousActivity->started_at->diffInSeconds($endedAt);

            // Only update if duration is reasonable (between 1 second and 1 hour)
            if ($duration >= 1 && $duration <= 3600) {
                $previousActivity->update([
                    'ended_at' => $endedAt,
                    'duration_seconds' => $duration,
                ]);
            }
        }
    }

    /**
     * Update activity end time and duration
     */
    public function endActivity(UserActivityLog $activityLog): UserActivityLog
    {
        $endedAt = now();
        $duration = $activityLog->started_at ? $endedAt->diffInSeconds($activityLog->started_at) : 0;

        $activityLog->update([
            'ended_at' => $endedAt,
            'duration_seconds' => $duration,
        ]);

        return $activityLog;
    }

    /**
     * Get activity logs for a user with search and filters.
     *
     * @param  bool  $paginate  When true, return a paginator instead of a plain collection.
     */
    public function getUserActivityLogs(int $userId, ?int $limit = null, ?string $startDate = null, ?string $endDate = null, ?string $search = null, ?string $lessonFilter = null, ?int $formationId = null, bool $paginate = false)
    {
        $query = UserActivityLog::forUser($userId)
            ->with('user')
            ->orderBy('created_at', 'desc');

        // Apply date filters even if only one bound is provided
        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('url', 'like', '%'.$search.'%')
                    ->orWhere('ip_address', 'like', '%'.$search.'%')
                    ->orWhere('user_agent', 'like', '%'.$search.'%');
            });
        }

        if ($lessonFilter) {
            // Filter by lesson-related URLs. If a numeric lesson ID is provided,
            // try matching common URL patterns (raw and URL-encoded).
            if (ctype_digit((string) $lessonFilter)) {
                $id = (string) $lessonFilter;
                $query->where(function ($q) use ($id) {
                    $q->where('url', 'like', '%/lessons/'.$id.'%')
                      ->orWhere('url', 'like', '%/lesson/'.$id.'%')
                      ->orWhere('url', 'like', '%lessons%2F'.$id.'%')
                      ->orWhere('url', 'like', '%lesson%2F'.$id.'%')
                      ->orWhere('url', 'like', '%/'.$id.'%');
                });
            } else {
                $query->where('url', 'like', '%'.$lessonFilter.'%');
            }
        }

        if ($formationId !== null) {
            $query = $this->applyFormationFilter($query, $formationId);
        }

        if ($paginate) {
            return $query->paginate($limit ?? 20);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Get activity summary for a user
     */
    public function getUserActivitySummary(int $userId, ?string $startDate = null, ?string $endDate = null, ?int $formationId = null): array
    {
        $query = UserActivityLog::forUser($userId);

        // Apply date filters even if only one bound is provided
        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        if ($formationId !== null) {
            $query = $this->applyFormationFilter($query, $formationId);
        }

        $logs = $query->get();

        if ($logs->isEmpty()) {
            return [
                'total_sessions' => 0,
                'total_time_seconds' => 0,
                'total_page_views' => 0,
                'unique_ips' => 0,
                'average_session_duration' => 0,
                'most_visited_pages' => [],
                'activity_by_hour' => [],
                'activity_by_day' => [],
            ];
        }

        // Calculate summary statistics
        $totalSessions = $logs->pluck('session_id')->unique()->count();
        $totalTimeSeconds = $logs->sum('duration_seconds');
        $totalPageViews = $logs->count();
        $uniqueIps = $logs->pluck('ip_address')->unique()->count();

        // Average session duration
        $averageSessionDuration = $totalSessions > 0 ? $totalTimeSeconds / $totalSessions : 0;

        // Most visited pages
        $mostVisitedPages = $logs->groupBy('url')
            ->map(function ($pageLogs) {
                return [
                    'url' => $pageLogs->first()->url,
                    'count' => $pageLogs->count(),
                    'total_time' => $pageLogs->sum('duration_seconds'),
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->toArray();

        // Activity by hour
        $activityByHour = $logs->groupBy(function ($log) {
            return $log->created_at->format('H');
        })->map(function ($hourLogs) {
            return $hourLogs->count();
        })->sortKeys()->toArray();

        // Activity by day
        $activityByDay = $logs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        })->map(function ($dayLogs) {
            return [
                'count' => $dayLogs->count(),
                'total_time' => $dayLogs->sum('duration_seconds'),
            ];
        })->sortKeys()->toArray();

        return [
            'total_sessions' => $totalSessions,
            'total_time_seconds' => $totalTimeSeconds,
            'total_page_views' => $totalPageViews,
            'unique_ips' => $uniqueIps,
            'average_session_duration' => round($averageSessionDuration, 0),
            'most_visited_pages' => $mostVisitedPages,
            'activity_by_hour' => $activityByHour,
            'activity_by_day' => $activityByDay,
        ];
    }

    /**
     * Get client IP address
     */
    private function getClientIp(Request $request): ?string
    {
        // Check for IP in various headers (in order of preference)
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Standard proxy header
            'HTTP_X_REAL_IP',        // Nginx proxy header
            'REMOTE_ADDR',           // Direct connection
        ];

        foreach ($ipHeaders as $header) {
            if ($request->hasHeader($header)) {
                $ip = $request->header($header);

                // Handle comma-separated IPs (take the first one)
                if (str_contains($ip, ',')) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Validate IP address
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return $request->ip();
    }

    /**
     * Get relevant request data for logging
     */
    private function getRequestData(Request $request): array
    {
        return [
            'query_params' => $request->query(),
            'route_name' => $request->route() ? $request->route()->getName() : null,
            'route_action' => $request->route() ? $request->route()->getActionName() : null,
            'content_length' => $request->header('Content-Length'),
            'content_type' => $request->header('Content-Type'),
        ];
    }

    /**
     * Clean up old activity logs
     */
    public function cleanupOldLogs(int $daysToKeep = 90): int
    {
        return UserActivityLog::where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }

    /**
     * Get unique sessions for a user
     */
    public function getUserSessions(int $userId, ?string $startDate = null, ?string $endDate = null)
    {
        $query = UserActivityLog::forUser($userId)
            ->whereNotNull('session_id')
            ->select('session_id', 'ip_address', 'user_agent')
            ->distinct();

        if ($startDate && $endDate) {
            $query->dateRange($startDate, $endDate);
        }

        return $query->get();
    }

    private function applyFormationFilter(Builder $query, int $formationId): Builder
    {
        $id = (string) $formationId;
        $patterns = [
            "%/formations/{$id}%",
            "%/formation/{$id}%",
            "%formations%2F{$id}%",
            "%formation%2F{$id}%",
        ];

        return $query->where(function (Builder $subQuery) use ($patterns) {
            foreach ($patterns as $pattern) {
                $subQuery->orWhere('url', 'like', $pattern);
            }
        });
    }
}
