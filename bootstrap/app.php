<?php

use App\Models\Team;
use App\Models\User;
use App\Policies\TeamPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // On peut passer un ARRAY de fichiers pour "web"
        web: [
            __DIR__ . '/../routes/clean/GuestRoute.php',
            __DIR__ . '/../routes/clean/UserRoute.php',
            __DIR__ . '/../routes/clean/AdminRoute.php',
            __DIR__ . '/../routes/clean/EleveRoute.php',
            __DIR__ . '/../routes/clean/FormateurRoute.php',
            /*
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/account.php',
            __DIR__.'/../routes/application.php',
            __DIR__.'/../routes/eleve.php',
            __DIR__.'/../routes/administration.php',
            __DIR__.'/../routes/superadmin.php',
            */
        ],
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            /*
            Gate::policy(Team::class, TeamPolicy::class);

            // Gates
            Gate::define('access-team', function (User $user, $team) {
                $team = $team instanceof Team ? $team : Team::query()->findOrFail($team);

                return $user->belongsToTeam($team);
            });

            Gate::define('isSuperAdmin', function (User $user) {
                return (bool) $user->superadmin;
            });
            */
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
