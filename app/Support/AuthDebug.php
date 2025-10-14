<?php

namespace App\Support;

class AuthDebug
{
    protected array $entries = [];

    public function add(string $ability, ?bool $result, array $arguments = [], ?string $caller = null): void
    {
        $this->entries[] = [
            'time'      => microtime(true),
            'ability'   => $ability,
            'result'    => $result,   // null = pending/unknown (ex: exception avant after)
            'arguments' => array_map(function ($arg) {
                if (is_object($arg)) {
                    return class_basename($arg) . (method_exists($arg, 'getKey') ? '#'.$arg->getKey() : '');
                }
                return (string) $arg;
            }, $arguments),
            'caller'    => $caller,
            'route'     => optional(request()->route())->getName(),
            'url'       => request()->fullUrl(),
        ];
    }

    // Optionnel : mettre à jour le dernier enregistrement pour une ability donnée
    public function resolve(string $ability, bool $result): void
    {
        for ($i = count($this->entries) - 1; $i >= 0; $i--) {
            if ($this->entries[$i]['ability'] === $ability && $this->entries[$i]['result'] === null) {
                $this->entries[$i]['result'] = $result;
                return;
            }
        }
        // fallback: si pas trouvé, on ajoute une ligne finale
        $this->add($ability, $result);
    }

    public function all(): array
    {
        return $this->entries;
    }

    public function hasEntries(): bool
    {
        return !empty($this->entries);
    }
}
