<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSignatureExists
{
    /**
     * Ensure the authenticated user has a signature.
     * If not, redirect to the signature page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Allow superadmin to bypass signature requirement
        if (method_exists($user, 'superadmin') && $user->superadmin()) {
            return $next($request);
        }

        // Check if user has a signature
        if (! $user->latestSignature) {
            // Store the intended URL in session for redirect after signature
            session(['url.intended' => $request->fullUrl()]);
            return redirect()->route('user.signature');
        }

        return $next($request);
    }
}
