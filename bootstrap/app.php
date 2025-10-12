<?php

use App\Http\Middleware\SetTeamContext;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // 👉 Ici, le conteneur est prêt : on peut définir les Gates
            Gate::define('access-admin', function (User $user, $team = null) {
                $team = $team ?: $user->currentTeam;
                if (! $team) return false;

                return $user->ownsTeam($team) || $user->hasTeamRole($team, 'admin');
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // si tu utilises le middleware de contexte d’équipe :
        // $middleware->appendToGroup('web', SetTeamContext::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
