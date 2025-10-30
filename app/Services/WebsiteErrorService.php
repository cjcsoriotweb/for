<?php

namespace App\Services;

use App\Models\WebsiteError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class WebsiteErrorService
{
    /**
     * Log a website error
     */
    public function logError(int $errorCode, string $message, string $url, ?Throwable $exception = null, ?Request $request = null): WebsiteError
    {
        $userId = Auth::id();
        $ipAddress = $request ? $this->getClientIp($request) : request()->ip();
        $userAgent = $request ? $request->userAgent() : null;

        $errorData = [
            'error_code' => $errorCode,
            'message' => $message,
            'url' => $url,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'request_data' => $request ? $this->getRequestData($request) : null,
            'stack_trace' => $exception ? $exception->getTraceAsString() : null,
        ];

        return WebsiteError::create($errorData);
    }

    /**
     * Log a 404 error
     */
    public function log404(string $url, ?Request $request = null): WebsiteError
    {
        return $this->logError(404, 'Page not found', $url, null, $request);
    }

    /**
     * Log a 403 error
     */
    public function log403(string $url, ?Request $request = null): WebsiteError
    {
        return $this->logError(403, 'Access forbidden', $url, null, $request);
    }

    /**
     * Log a 500 error
     */
    public function log500(string $url, Throwable $exception, ?Request $request = null): WebsiteError
    {
        return $this->logError(500, 'Internal server error', $url, $exception, $request);
    }

    /**
     * Get error statistics
     */
    public function getErrorStatistics(?int $days = 7): array
    {
        $query = WebsiteError::query();

        if ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $errors = $query->get();

        if ($errors->isEmpty()) {
            return [
                'total_errors' => 0,
                'unresolved_errors' => 0,
                'errors_by_code' => [],
                'recent_errors' => [],
            ];
        }

        $errorsByCode = $errors->groupBy('error_code')->map(function ($codeErrors) {
            return [
                'code' => $codeErrors->first()->error_code,
                'count' => $codeErrors->count(),
                'unresolved' => $codeErrors->where('resolved_at', null)->count(),
            ];
        })->values();

        $recentErrors = $errors->take(10)->map(function ($error) {
            return [
                'id' => $error->id,
                'code' => $error->error_code,
                'message' => $error->message,
                'url' => $error->url,
                'user' => $error->user ? $error->user->name : null,
                'created_at' => $error->created_at->format('Y-m-d H:i:s'),
                'resolved' => $error->isResolved(),
            ];
        });

        return [
            'total_errors' => $errors->count(),
            'unresolved_errors' => $errors->where('resolved_at', null)->count(),
            'errors_by_code' => $errorsByCode,
            'recent_errors' => $recentErrors,
        ];
    }

    /**
     * Get unresolved errors
     */
    public function getUnresolvedErrors(?int $limit = null)
    {
        $query = WebsiteError::unresolved()
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Mark an error as resolved
     */
    public function markErrorAsResolved(int $errorId): bool
    {
        $error = WebsiteError::find($errorId);

        if ($error && ! $error->isResolved()) {
            $error->markAsResolved();

            return true;
        }

        return false;
    }

    /**
     * Delete an error permanently
     */
    public function deleteError(int $errorId): bool
    {
        $error = WebsiteError::find($errorId);

        if ($error) {
            return $error->delete();
        }

        return false;
    }

    /**
     * Clean up old resolved errors
     */
    public function cleanupOldResolvedErrors(int $daysToKeep = 30): int
    {
        return WebsiteError::whereNotNull('resolved_at')
            ->where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }

    /**
     * Get client IP address
     */
    private function getClientIp(Request $request): string
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
            'method' => $request->method(),
            'query_params' => $request->query(),
            'route_name' => $request->route() ? $request->route()->getName() : null,
            'headers' => $this->getSafeHeaders($request),
        ];
    }

    /**
     * Get safe headers (excluding sensitive ones)
     */
    private function getSafeHeaders(Request $request): array
    {
        $sensitiveHeaders = ['authorization', 'cookie', 'set-cookie', 'x-api-key', 'x-auth-token'];

        $headers = [];
        foreach ($request->headers->all() as $key => $values) {
            if (! in_array(strtolower($key), $sensitiveHeaders)) {
                $headers[$key] = $values[0] ?? null; // Take first value
            }
        }

        return $headers;
    }
}
