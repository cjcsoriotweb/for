<?php

namespace App\Http\Middleware;

use App\Support\AuthDebug;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class AuthDebugMiddleware
{
    public function handle($request, Closure $next)
    {
        if (! config('app.debug')) {
            return $next($request);
        }

        $collector = app(AuthDebug::class);

        // Enregistrer le check AU DÉBUT (même si ça finit en 403)
        Gate::before(function ($user, string $ability, array $arguments = []) use ($collector) {
            $trace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20));
            $callerFrame = $trace->first(fn ($f) => in_array($f['function'] ?? '', ['authorize', 'can', 'allows']));
            $caller = isset($callerFrame['file'], $callerFrame['line'])
                ? basename($callerFrame['file']).':'.$callerFrame['line'] : null;

            $collector->addOrBump($ability, null, $arguments, $caller); // pending
            return null;
        });

        
        Gate::after(function ($user, string $ability, ?bool $result, array $arguments = []) use ($collector) {
            $collector->resolve($ability, $result ?? false);
        });

        // Partager à la vue AVANT rendu
        View::share('authDebug', $collector);

        
        return $next($request);
    }
}
