<?php

use App\Http\Middleware\SetTeamContext;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Gates "team" — tolérantes : acceptent Team|int|string depuis la route (ex: {team} = "3")
            Gate::define('access-team', function (User $user, $team) {
                $team = $team instanceof Team ? $team : Team::query()->findOrFail($team);
                return $user->belongsToTeam($team);
            });

            Gate::define('access-admin', function (User $user, $team) {
                $team = $team instanceof Team ? $team : Team::query()->findOrFail($team);
                return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
            });

            // (Optionnel) Super-admin de secours pendant le dev
            // Gate::before(function (User $user, string $ability, ?array $args = null) {
            //     return $user->email === 'you@example.com' ? true : null;
            // });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Si tu utilises un contexte d’équipe partagé aux vues :
        // $middleware->appendToGroup('web', SetTeamContext::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
