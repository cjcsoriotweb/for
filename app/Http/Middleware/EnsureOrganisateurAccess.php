<?php

namespace App\Http\Middleware;

use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganisateurAccess
{
    /**
     * Ensure the authenticated user can access the organiser area of the given team.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $team = $request->route('team');

        if (! $team instanceof Team) {
            abort(404);
        }

        if (method_exists($user, 'superadmin') && $user->superadmin()) {
            return $next($request);
        }

        if (! $user->belongsToTeam($team)) {
            abort(403, 'Acces refuse. Vous ne faites pas partie de cette equipe.');
        }

        if ($user->ownsTeam($team) || $user->hasTeamPermission($team, 'organisateur:access')) {
            return $next($request);
        }

        abort(403, 'Acces refuse. Role organiseur requis.');
    }
}
