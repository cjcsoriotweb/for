<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\FormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        $forced = (bool) $request->session()->get("tutorial.forced.{$tutorialKey}", false);

        $contextData = $this->resolveContextData($tutorialKey, $request);

        return view($view, array_merge($contextData, [
            'tutorialKey' => $tutorialKey,
            'returnUrl' => $targetUrl,
            'tutorialUrl' => route('tutorial.show', $params),
            'forced' => $forced,
        ]));
    }

    public function show(Request $request, string $tutorial)
    {
        $tutorialKey = $this->sanitizeKey($tutorial);

        $view = $this->resolveView($tutorialKey);

        $returnUrl = $request->query('return');
        if ($returnUrl) {
            $request->session()->put("tutorial.return.{$tutorialKey}", $returnUrl);
        }

        $forced = (bool) $request->session()->get("tutorial.forced.{$tutorialKey}", false);

        $contextData = $this->resolveContextData($tutorialKey, $request);

        return view($view, array_merge($contextData, [
            'tutorialKey' => $tutorialKey,
            'returnUrl' => $returnUrl ?? $request->session()->get("tutorial.return.{$tutorialKey}"),
            'forced' => $forced,
        ]));
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
        $request->session()->forget("tutorial.forced.{$tutorialKey}");

        $targetUrl = $data['return'] ?? $pendingUrl ?? url()->previous() ?? url('/');

        return redirect()->to($targetUrl);
    }

    private function sanitizeKey(string $tutorial): string
    {
        if (! preg_match('/^[A-Za-z0-9._-]+$/', $tutorial)) {
            throw new NotFoundHttpException;
        }

        return $tutorial;
    }

    private function resolveView(string $tutorialKey): string
    {
        $view = 'tutorials.'.$tutorialKey;

        if (! View::exists($view)) {
            throw new NotFoundHttpException;
        }

        return $view;
    }

    private function resolveTransitionView(string $tutorialKey): string
    {
        $specificView = 'tutorials.'.$tutorialKey.'-transition';

        if (View::exists($specificView)) {
            return $specificView;
        }

        if (View::exists('tutorials.transition')) {
            return 'tutorials.transition';
        }

        throw new NotFoundHttpException;
    }

    private function resolveContextData(string $tutorialKey, Request $request): array
    {
        return match ($tutorialKey) {
            'administrateur-home' => $this->resolveAdminHomeContext($tutorialKey, $request),
            default => [],
        };
    }

    private function resolveAdminHomeContext(string $tutorialKey, Request $request): array
    {
        $team = $this->resolveTeamFromContext($tutorialKey, $request);

        if (! $team) {
            return [];
        }

        $formations = app(FormationService::class);
        $adminService = $formations->admin();
        $catalog = $adminService->listWithTeamFlags($team);

        $activeCount = $catalog->where('is_visible', '>', 0)->count();
        $totalCount = $catalog->count();

        $totalUsers = max(0, $team->allUsers()->count() - 1);
        $usersLabel = $totalUsers > 1 ? __('utilisateurs') : __('utilisateur');
        $adminName = Auth::user()?->name ?? __('Administrateur');

        $visiblePercentage = $totalCount > 0 ? (int) round(($activeCount / max(1, $totalCount)) * 100) : 0;
        $usersProgressWidth = min(100, max(12, $totalUsers * 8));
        $formationsProgressWidth = min(100, max(8, $visiblePercentage));

        $hasTeamLogo = ! empty($team->profile_photo_path);
        $teamLogoUrl = $hasTeamLogo ? $this->publicStorageUrl($team->profile_photo_path) : null;

        return [
            'team' => $team,
            'adminName' => $adminName,
            'activeCount' => $activeCount,
            'totalCount' => $totalCount,
            'totalUsers' => $totalUsers,
            'usersLabel' => $usersLabel,
            'visiblePercentage' => $visiblePercentage,
            'usersProgressWidth' => $usersProgressWidth,
            'formationsProgressWidth' => $formationsProgressWidth,
            'hasTeamLogo' => $hasTeamLogo,
            'teamLogoUrl' => $teamLogoUrl,
        ];
    }

    private function resolveTeamFromContext(string $tutorialKey, Request $request): ?Team
    {
        $returnUrl = $request->query('return')
            ?? $request->session()->get("tutorial.pending.{$tutorialKey}")
            ?? $request->session()->get("tutorial.return.{$tutorialKey}")
            ?? '';

        $path = $returnUrl ? parse_url($returnUrl, PHP_URL_PATH) : null;
        $teamId = null;

        if ($path) {
            $segments = array_values(array_filter(explode('/', $path)));
            $index = array_search('administrateur', $segments, true);

            if ($index !== false && isset($segments[$index + 1]) && is_numeric($segments[$index + 1])) {
                $teamId = (int) $segments[$index + 1];
            }
        }

        if (! $teamId) {
            $teamId = Auth::user()?->current_team_id;
        }

        if (! $teamId) {
            return null;
        }

        return Team::find($teamId);
    }

    private function publicStorageUrl(string $path): string
    {
        $relativePath = ltrim($path, '/');

        return Storage::disk('public')->url($relativePath);
    }
}
