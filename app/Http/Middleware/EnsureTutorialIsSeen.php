<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTutorialIsSeen
{
    public function handle(Request $request, Closure $next, string $tutorialKey): Response
    {
        $session = $request->session();

        if ($session->get("tutorial.skipped.{$tutorialKey}")) {
            return $next($request);
        }

        $session->put("tutorial.pending.{$tutorialKey}", $request->fullUrl());

        $params = [
            'tutorial' => $tutorialKey,
            'return' => $request->fullUrl(),
        ];

        if (! $session->get("tutorial.intro_shown.{$tutorialKey}")) {
            return redirect()->route('tutorial.intro', $params);
        }

        return redirect()->route('tutorial.show', $params);
    }
}
