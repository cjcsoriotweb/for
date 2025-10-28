<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFortifyTutorial
{
    public function __construct(private readonly EnsureTutorialIsSeen $tutorialMiddleware)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route && $route->getName() === 'register') {
            return $this->tutorialMiddleware->handle($request, $next, 'inscription', 'forced=true');
        }

        return $next($request);
    }
}

