<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TutorialController extends Controller
{
    public function intro(Request $request, string $tutorial)
    {
        $tutorialKey = $this->sanitizeKey($tutorial);

        $view = $this->resolveTransitionView($tutorialKey);

        $returnUrl = $request->query('return');
        if ($returnUrl) {
            $request->session()->put("tutorial.return.{$tutorialKey}", $returnUrl);
        }

        $request->session()->put("tutorial.intro_shown.{$tutorialKey}", true);

        $params = ['tutorial' => $tutorialKey];
        $targetUrl = $returnUrl ?? $request->session()->get("tutorial.return.{$tutorialKey}");
        if ($targetUrl) {
            $params['return'] = $targetUrl;
        }

        return view($view, [
            'tutorialKey' => $tutorialKey,
            'returnUrl' => $targetUrl,
            'tutorialUrl' => route('tutorial.show', $params),
        ]);
    }

    public function show(Request $request, string $tutorial)
    {
        $tutorialKey = $this->sanitizeKey($tutorial);

        $view = $this->resolveView($tutorialKey);

        $returnUrl = $request->query('return');
        if ($returnUrl) {
            $request->session()->put("tutorial.return.{$tutorialKey}", $returnUrl);
        }

        return view($view, [
            'tutorialKey' => $tutorialKey,
            'returnUrl' => $returnUrl ?? $request->session()->get("tutorial.return.{$tutorialKey}"),
        ]);
    }

    public function skip(Request $request, string $tutorial)
    {
        $tutorialKey = $this->sanitizeKey($tutorial);

        $data = $request->validate([
            'return' => ['nullable', 'url'],
        ]);

        $request->session()->put("tutorial.skipped.{$tutorialKey}", true);
        $pendingUrl = $request->session()->pull("tutorial.pending.{$tutorialKey}");
        $request->session()->forget("tutorial.return.{$tutorialKey}");
        $request->session()->forget("tutorial.intro_shown.{$tutorialKey}");

        $targetUrl = $data['return'] ?? $pendingUrl ?? url()->previous() ?? url('/');

        return redirect()->to($targetUrl);
    }

    private function sanitizeKey(string $tutorial): string
    {
        if (!preg_match('/^[A-Za-z0-9._-]+$/', $tutorial)) {
            throw new NotFoundHttpException();
        }

        return $tutorial;
    }

    private function resolveView(string $tutorialKey): string
    {
        $view = 'tutorials.' . $tutorialKey;

        if (!View::exists($view)) {
            throw new NotFoundHttpException();
        }

        return $view;
    }

    private function resolveTransitionView(string $tutorialKey): string
    {
        $specificView = 'tutorials.' . $tutorialKey . '-transition';

        if (View::exists($specificView)) {
            return $specificView;
        }

        if (View::exists('tutorials.transition')) {
            return 'tutorials.transition';
        }

        throw new NotFoundHttpException();
    }
}
