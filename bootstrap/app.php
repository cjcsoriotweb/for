<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // On peut passer un ARRAY de fichiers pour "web"
        web: [
            __DIR__.'/../routes/GuestRoute.php',
            __DIR__.'/../routes/UserRoute.php',
            __DIR__.'/../routes/AdminRoute.php',
            __DIR__.'/../routes/SuperadminRoute.php',
            __DIR__.'/../routes/EleveRoute.php',
            __DIR__.'/../routes/OrganisateurRoute.php',
            __DIR__.'/../routes/FormateurRoute.php',
        ],
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {}
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(\App\Http\Middleware\TrackUserActivity::class);
        $middleware->alias([
            'organisateur' => \App\Http\Middleware\EnsureOrganisateurAccess::class,
            'eleve' => \App\Http\Middleware\EnsureEleveAccess::class,

        ]);
    })
    ->withExceptions(function (\Illuminate\Foundation\Configuration\Exceptions $exceptions) {
        // Policies / Gate::authorize() -> AuthorizationException (403)
        $exceptions->render(function (AuthorizationException $e, $request) {
            // Log 403 error
            app(\App\Services\WebsiteErrorService::class)->logError(
                403,
                $e->getMessage(),
                $request->fullUrl(),
                $e,
                $request
            );

            return response()->view('errors.403', [
                'message' => $e->getMessage(),
            ], 403);
        });

        // Autres 403 (middleware can:, AccessDeniedHttpException, etc.)
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if ($e->getStatusCode() === 403) {
                // Log 403 error
                app(\App\Services\WebsiteErrorService::class)->logError(
                    403,
                    $e->getMessage(),
                    $request->fullUrl(),
                    $e,
                    $request
                );

                return response()->view('errors.403', [
                    'message' => method_exists($e, 'getMessage') ? $e->getMessage() : null,
                ], 403);
            }

            return null;
        });

        // Capture automatique des erreurs générales
        $exceptions->render(function (Throwable $e, $request) {
            // Ne pas logger les erreurs de debug/404 (déjà gérées par Laravel)
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return null;
            }

            // Ne pas logger les erreurs Http déjà gérées (403)
            if ($e instanceof HttpExceptionInterface) {
                return null;
            }

            // Log toutes les autres erreurs non gérées (500, InvalidArgumentException, etc.)
            app(\App\Services\WebsiteErrorService::class)->logError(
                500,
                $e->getMessage(),
                $request->fullUrl(),
                $e,
                $request
            );

            return null; // Continuer avec le rendu normal de Laravel
        });
    })
    ->create();
