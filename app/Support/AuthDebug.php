<?php
// app/Support/AuthDebug.php
namespace App\Support;

class AuthDebug
{
    protected array $entries = [];

    // liste d'attributs utiles à afficher par modèle (optionnel)
    protected array $modelPreview = [
        'Team' => ['id','name','slug'],
        'User' => ['id','name','email'],
    ];

// app/Support/AuthDebug.php

protected function serializeArg($arg): string
{
    if (is_object($arg)) {
        $class = class_basename($arg);
        $id = method_exists($arg, 'getKey') ? $arg->getKey() : null;

        // objet neuf non persisté
        $suffix = $id === null ? '#new' : '#'.$id;

        // mini preview
        $extra = [];
        if (method_exists($arg, 'getAttribute')) {
            foreach (($this->modelPreview[$class] ?? []) as $k) {
                $val = $arg->getAttribute($k);
                if (!is_null($val)) $extra[$k] = $val;
            }
        }
        $extraStr = $extra ? ' '.json_encode($extra, JSON_UNESCAPED_UNICODE) : '';

        return "{$class}{$suffix}{$extraStr}";
    }

    // class-string (ex: App\Models\Formation::class)
    if (is_string($arg) && class_exists($arg)) {
        return class_basename($arg).'::class';
    }

    if (is_array($arg)) {
        return 'array '.substr(json_encode($arg, JSON_UNESCAPED_UNICODE), 0, 200);
    }

    if (is_scalar($arg) || $arg === null) {
        return var_export($arg, true);
    }

    return gettype($arg);
}


    protected function makeKey(string $ability, ?bool $result, array $arguments, ?string $route, ?string $caller): string
    {
        $sig = array_map(fn($a) => $this->serializeArg($a), $arguments);
        return sha1(json_encode([$ability, $result, $sig, $route, $caller]));
    }

    public function addOrBump(string $ability, ?bool $result, array $arguments = [], ?string $caller = null): void
    {
        $route = optional(request()->route())->getName();
        $url   = request()->fullUrl();

        $key = $this->makeKey($ability, $result, $arguments, $route, $caller);

        if (isset($this->entries[$key])) {
            $this->entries[$key]['count']++;
            return;
        }

        $this->entries[$key] = [
            'time'      => microtime(true),
            'ability'   => $ability,
            'result'    => $result, // null = pending
            'arguments' => array_map(fn($a) => $this->serializeArg($a), $arguments),
            'caller'    => $caller,
            'route'     => $route,
            'url'       => $url,
            'count'     => 1,
        ];
    }

    public function resolve(string $ability, bool $result): void
    {
        // met à jour la dernière entrée "pending" pour la même ability sur cette requête
        foreach (array_reverse($this->entries, true) as $k => $entry) {
            if ($entry['ability'] === $ability && $entry['result'] === null) {
                $this->entries[$k]['result'] = $result;
                return;
            }
        }
        // sinon on ajoute une ligne finale
        $this->addOrBump($ability, $result);
    }

    public function all(): array
    {
        return array_values($this->entries); // index propre
    }

    public function hasEntries(): bool
    {
        return !empty($this->entries);
    }
}
