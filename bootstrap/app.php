<?php

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // On peut passer un ARRAY de fichiers pour "web"
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/account.php',
            __DIR__ . '/../routes/application.php',
            __DIR__ . '/../routes/application_admin.php',
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // Policies
            Gate::policy(Team::class, TeamPolicy::class);

            // Gates
            Gate::define('access-team', function (User $user, $team) {
                $team = $team instanceof Team ? $team : Team::query()->findOrFail($team);
                return $user->belongsToTeam($team);
            });

            Gate::define('access-admin', function (User $user, $team) {
                $team = $team instanceof Team ? $team : Team::query()->findOrFail($team);
                return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ex: $middleware->appendToGroup('web', \App\Http\Middleware\SetTeamContext::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
