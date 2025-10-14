<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Support\AuthDebug;

class AuthDebugMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!config('app.debug')) {
            return $next($request);
        }

        $collector = app(AuthDebug::class);

        // Enregistrer le check AU DÉBUT (même si ça finit en 403)
        Gate::before(function ($user, string $ability, array $arguments = []) use ($collector) {
            $trace = collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20));
            $callerFrame = $trace->first(function ($f) {
                return in_array($f['function'] ?? '', ['authorize', 'can', 'allows']);
            });
            $caller = isset($callerFrame['file'], $callerFrame['line'])
                ? basename($callerFrame['file']).':'.$callerFrame['line'] : null;

            // result = null => pending
            $collector->add($ability, null, $arguments, $caller);

            // IMPORTANT : retourner null pour ne PAS court-circuiter l’authorization
            return null;
        });

        // Résoudre le résultat après évaluation
        Gate::after(function ($user, string $ability, ?bool $result, array $arguments = []) use ($collector) {
            // $result peut être null selon les versions; cast en bool seulement si non-null
            if ($result !== null) {
                $collector->resolve($ability, (bool) $result);
            } else {
                // si indéterminé, marque-le comme false par défaut (optionnel)
                $collector->resolve($ability, false);
            }
        });

        // Partager à la vue AVANT rendu
        View::share('authDebug', $collector);

        return $next($request);
    }
}
