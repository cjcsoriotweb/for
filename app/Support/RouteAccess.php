<?php
// app/Support/RouteAccess.php

namespace App\Support;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteAccess
{
    public static function canAccess(string $name, array $params = [], ?Authenticatable $user = null): bool
    {
        return self::inspect($name, $params, $user)['allowed'];
    }

    /**
     * Inspecte la route nommée et renvoie:
     *  - allowed: bool (tous les checks passent)
     *  - labels: ex. ["can:updateTeamMember(team, member)", "permission:users.manage"]
     *  - details: infos structurées par check
     * Ne jette jamais d'exception; utilise Gate::inspect().
     */
    public static function inspect(
        string $name,
        array $params = [],
        ?Authenticatable $user = null,
        bool $includeAuthLabel = false
    ): array {

        $user  = $user ?: auth()->user();
        $route = Route::getRoutes()->getByName($name);

        if (!$route) {
            return ['allowed' => false, 'labels' => ['route:missing'], 'details' => []];
        }

        $middlewares = app('router')->gatherRouteMiddleware($route);

        $labels  = [];
        $details = [];
        $allowed = true;

        foreach (self::normalizeMiddlewares($middlewares) as [$id, $args]) {
            // 1) can / Authorize
            if (self::isCanMiddleware($id)) {
                [$ability, $argNames] = self::parseAbilityArgs($args);
                $resolved = [];
                foreach ($argNames as $paramName) {
                    $resolved[] = Arr::get($params, $paramName);
                }

                $ok = false;
                try {
                    $resp = Gate::forUser($user)->inspect($ability, $resolved);
                    $ok = $resp->allowed();
                } catch (\Throwable $e) {
                    $ok = false;
                }

                $allowed  = $allowed && $ok;
                $labels[] = 'can:' . $ability . '(' . implode(', ', $argNames) . ')';
                $details[] = [
                    'type'      => 'can',
                    'ability'   => $ability,
                    'arg_names' => $argNames,
                    'args'      => $resolved,
                    'allowed'   => $ok,
                ];
                continue;
            }

            // 2) spatie/permission
            if (self::isPermissionMiddleware($id)) {
                $perms = count($args) ? explode('|', $args[0]) : [];
                $perms = array_map('trim', $perms);

                foreach ($perms as $perm) {
                    $ok = false;
                    try { $ok = $user?->can($perm) ?? false; } catch (\Throwable $e) { $ok = false; }
                    $allowed  = $allowed && $ok;
                    $labels[] = "permission:$perm";
                    $details[] = ['type' => 'permission', 'permission' => $perm, 'allowed' => $ok];
                }
                continue;
            }

            // 3) spatie/role
            if (self::isRoleMiddleware($id)) {
                $roles = count($args) ? explode('|', $args[0]) : [];
                $roles = array_map('trim', $roles);

                $ok = false;
                try { $ok = $user?->hasAnyRole($roles) ?? false; } catch (\Throwable $e) { $ok = false; }

                $allowed  = $allowed && $ok;
                $labels[] = 'role:' . implode('|', $roles);
                $details[] = ['type' => 'role', 'roles' => $roles, 'allowed' => $ok];
                continue;
            }
        }

        if ($includeAuthLabel && self::containsAuth($middlewares)) {
            $labels[]  = 'auth';
            $details[] = ['type' => 'auth', 'allowed' => (bool) $user];
            $allowed   = $allowed && (bool) $user;
        }

        return compact('allowed', 'labels', 'details');
    }

    /* ----------------- internes ----------------- */

    /** Normalise MW en tuples [identifiant, args[]] */
    protected static function normalizeMiddlewares(array $middlewares): array
    {
        $out = [];
        foreach ($middlewares as $mw) {
            if (is_string($mw) && str_contains($mw, ':')) {
                [$id, $argStr] = explode(':', $mw, 2);
                $args = array_map('trim', explode(',', $argStr));
            } else {
                $id = is_string($mw) ? $mw : (string)$mw;
                $args = [];
            }
            $out[] = [$id, $args];
        }
        return $out;
    }

    protected static function isCanMiddleware(string $id): bool
    {
        return $id === 'can'
            || Str::endsWith($id, '\\Authorize')
            || $id === \Illuminate\Auth\Middleware\Authorize::class;
    }

    protected static function isPermissionMiddleware(string $id): bool
    {
        return $id === 'permission'
            || Str::contains($id, 'Spatie\\Permission\\Middlewares\\PermissionMiddleware');
    }

    protected static function isRoleMiddleware(string $id): bool
    {
        return $id === 'role'
            || Str::contains($id, 'Spatie\\Permission\\Middlewares\\RoleMiddleware');
    }

    /** args → [ability, [paramNames...]] */
    protected static function parseAbilityArgs(array $args): array
    {
        $ability  = $args[0] ?? '';
        $argNames = array_slice($args, 1);
        return [$ability, $argNames];
    }

    protected static function containsAuth(array $middlewares): bool
    {
        foreach ($middlewares as $mw) {
            $m = is_string($mw) ? $mw : (string)$mw;
            if ($m === 'auth'
                || Str::endsWith($m, '\\Authenticate')
                || $m === \Illuminate\Auth\Middleware\Authenticate::class) {
                return true;
            }
        }
        return false;
    }
}
