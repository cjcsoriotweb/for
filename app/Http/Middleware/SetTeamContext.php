<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class SetTeamContext
{
    public function handle(Request $request, Closure $next)
    {
        $fallbackName = config('app.name');
        $user = Auth::user();

        // 1) Team prioritaire : paramètre de route {team} si présent (Jetstream route-model binding)
        $team = $request->route('team') ?? null;

        // 2) Sinon team courante de l'utilisateur
        if (! $team && $user) {
            $team = $user->currentTeam; // nullable
        }

        // 3) Construire le contexte
        $ctx = [
            'hasUser'        => (bool) $user,
            'hasTeam'        => (bool) $team,
            'siteName'       => $team?->name ?? $fallbackName,

            'team' => $team ? [
                'id'        => $team->id,
                'uuid'      => $team->uuid ?? null, // Jetstream a souvent uuid
                'name'      => $team->name,
                'owner_id'  => $team->user_id,
            ] : null,

            'role'           => null,
            'isOwner'        => false,
            'permissions'    => [],
            'currentTeamId'  => $user?->current_team_id,
            'allTeams'       => [],
        ];

        if ($user && $team) {
            // Rôle : 'owner' si propriétaire, sinon rôle du pivot (ex: 'admin','eleve',...)
            $isOwner = (int) $team->user_id === (int) $user->id;
            $role = null;

            if ($isOwner) {
                $role = 'owner';
            } else {
                // Cherche l'entrée de membership pour récupérer le pivot->role
                $membership = $user->teams()
                    ->where('team_id', $team->id)
                    ->first(); // Team model avec pivot
                $role = $membership?->pivot?->role; // ex. 'admin', 'eleve', ...
            }

            // Permissions (Jetstream::permissions()) pour cette team
            // -> renvoie un array de permissions ex. ['read','create','update','delete']
            $permissions = $user->teamPermissions($team);

            // Liste *légère* des teams de l'utilisateur (pour switcher en UI si besoin)
            $allTeams = $user->allTeams()->map(function ($t) {
                return [
                    'id'   => $t->id,
                    'uuid' => $t->uuid ?? null,
                    'name' => $t->name,
                    'is_owner' => (int) $t->user_id === (int) auth()->id(),
                ];
            })->values()->all();

            $ctx['isOwner']     = $isOwner;
            $ctx['role']        = $role;                 // 'owner' | 'admin' | 'eleve' | null
            $ctx['permissions'] = array_values($permissions ?? []);
            $ctx['allTeams']    = $allTeams;
        }

        // 4) Partage global (vues) + config app.name mise au nom d’équipe pour la requête
        View::share('teamCtx', $ctx);
        View::share('siteName', $ctx['siteName']);   // rétro-compat
        View::share('currentTeam', $team);           // rétro-compat

        // 5) Accessible aussi côté contrôleur via $request->attributes
        $request->attributes->set('teamCtx', $ctx);

        return $next($request);
    }
}
