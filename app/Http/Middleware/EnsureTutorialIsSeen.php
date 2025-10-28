<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTutorialIsSeen
{
    public function handle(Request $request, Closure $next, string $tutorialKey, string ...$rawOptions): Response
    {
        $session = $request->session();

        $options = $this->parseOptions($rawOptions);
        $forced = $this->optionIsTrue($options, 'forced') || $this->optionIsTrue($options, 'force') || $this->optionIsTrue($options, 'required');

        if ($forced) {
            $session->put("tutorial.forced.{$tutorialKey}", true);
        } else {
            $session->forget("tutorial.forced.{$tutorialKey}");
        }

        if ($session->get("tutorial.skipped.{$tutorialKey}")) {
            return $next($request);
        }

        $session->put("tutorial.pending.{$tutorialKey}", $request->fullUrl());

        $params = [
            'tutorial' => $tutorialKey,
            'return' => $request->fullUrl(),
        ];

        if (! $session->get("tutorial.intro_shown.{$tutorialKey}")) {
            return redirect()->route('tutorial.intro', $params);
        }

        return redirect()->route('tutorial.show', $params);
    }

    /**
     * @param  array<int, string>  $options
     * @return array<string, string|bool>
     */
    private function parseOptions(array $options): array
    {
        $parsed = [];

        foreach ($options as $option) {
            if (str_contains($option, '=')) {
                [$key, $value] = array_pad(explode('=', $option, 2), 2, null);
                $key = strtolower(trim((string) $key));
                $value = strtolower(trim((string) $value));

                if ($key !== '') {
                    $parsed[$key] = $value;
                }
            } else {
                $option = strtolower(trim($option));

                if ($option !== '') {
                    $parsed[$option] = true;
                }
            }
        }

        return $parsed;
    }

    /**
     * @param  array<string, string|bool>  $options
     */
    private function optionIsTrue(array $options, string $key): bool
    {
        if (! array_key_exists($key, $options)) {
            return false;
        }

        $value = $options[$key];

        if (is_bool($value)) {
            return $value;
        }

        return in_array($value, ['1', 'true', 'yes', 'on'], true);
    }
}
