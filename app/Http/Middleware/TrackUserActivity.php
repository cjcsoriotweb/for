<?php

namespace App\Http\Middleware;

use App\Services\UserActivityService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function __construct(
        private readonly UserActivityService $userActivityService,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        // Skip tracking for API routes, AJAX requests, and certain file types
        if ($this->shouldSkipTracking($request)) {
            return $next($request);
        }

        try {
            // Log the activity
            $this->userActivityService->logActivity($request);
        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::error('Failed to track user activity: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
            ]);
        }

        $response = $next($request);

        // For GET requests, we could track the response time
        // but for now, we'll just log the initial request
        // The duration can be calculated when the session ends or page unloads

        return $response;
    }

    /**
     * Determine if activity tracking should be skipped for this request
     */
    private function shouldSkipTracking(Request $request): bool
    {
        // Skip API routes
        if ($request->is('api/*')) {
            return true;
        }

        // Skip AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return true;
        }

        // Skip asset files
        if ($request->is('js/*') || $request->is('css/*') || $request->is('images/*') || $request->is('fonts/*')) {
            return true;
        }

        // Skip favicon and other small assets
        if (
            str_contains($request->getPathInfo(), 'favicon') ||
            str_contains($request->getPathInfo(), 'robots.txt') ||
            str_contains($request->getPathInfo(), 'sitemap.xml')
        ) {
            return true;
        }

        // Skip Livewire requests (they handle their own tracking if needed)
        if ($request->hasHeader('X-Livewire')) {
            return true;
        }

        return false;
    }
}
