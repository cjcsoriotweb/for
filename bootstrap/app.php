<?php

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
       $middleware->appendToGroup('web', \App\Http\Middleware\AuthDebugMiddleware::class);
    })
    ->withExceptions(function (\Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        // Policies / Gate::authorize() -> AuthorizationException (403)
        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->view('errors.403', [
                'message' => $e->getMessage(),
            ], 403);
        });

        // Autres 403 (middleware can:, AccessDeniedHttpException, etc.)
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if ($e->getStatusCode() === 403) {
                return response()->view('errors.403', [
                    'message' => method_exists($e, 'getMessage') ? $e->getMessage() : null,
                ], 403);
            }
            return null;
        });
    })
    ->create();
