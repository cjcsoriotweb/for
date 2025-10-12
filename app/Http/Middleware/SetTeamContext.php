<?php

namespace App\Http\Middleware;

use App\Models\Team;
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

        // Normalise {team} (Model ou id)
        $teamParam = $request->route('team') ?? null;
        $team = null;

        if ($teamParam instanceof Team) {
            $team = $teamParam;
        } elseif (!is_null($teamParam)) {
            $team = Team::query()->whereKey($teamParam)->first();
        }

        // Sinon: team courante
        if (!$team && $user) {
            $team = $user->currentTeam;
        }

        // Contexte partagé
        $ctx = [
            'hasUser'       => (bool) $user,
            'hasTeam'       => (bool) $team,
            'siteName'      => $team?->name ?: $fallbackName,

            'team' => $team ? [
                'id'       => $team->id,
                'uuid'     => null,
                'name'     => $team->name,
                'owner_id' => $team->user_id,
            ] : null,

            'role'          => null, // 'owner' | 'admin' | 'eleve' | null
            'isOwner'       => false,
            'permissions'   => [],
            'currentTeamId' => $user?->current_team_id,
            'allTeams'      => [],
        ];

        if ($user && $team) {
            $isOwner = (int) $team->user_id === (int) $user->id;
            $role = $isOwner ? 'owner' : optional(
                $user->teams()->where('team_id', $team->id)->first()
            )->pivot?->role;

            $permissions = method_exists($user, 'teamPermissions')
                ? (array) $user->teamPermissions($team)
                : [];

            $allTeams = $user->allTeams()->map(function ($t) use ($user) {
                return [
                    'id'       => $t->id,
                    'uuid'     => null,
                    'name'     => $t->name,
                    'is_owner' => (int) $t->user_id === (int) $user->id,
                ];
            })->values()->all();

            $ctx['isOwner']     = $isOwner;
            $ctx['role']        = $role;
            $ctx['permissions'] = array_values($permissions);
            $ctx['allTeams']    = $allTeams;
        }

        // Partage global + config app.name
        config(['app.name' => $ctx['siteName']]);
        View::share('teamCtx', $ctx);
        View::share('siteName', $ctx['siteName']);
        View::share('currentTeam', $team);

        // Accessible côté contrôleurs/Livewire
        $request->attributes->set('teamCtx', $ctx);

        return $next($request);
    }
}
