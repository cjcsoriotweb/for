<?php

namespace App\View\Components\Admin;

use App\Services\FormationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminMenuFast extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public $team;

    public $activeCount;

    public $totalCount;

    public $catalog;

    public $totalUsers;

    public $usersLabel;

    public $adminName;

    public $visiblePercentage;

    public $usersProgressWidth;

    public $formationsProgressWidth;

    public $hasTeamLogo;

    public $teamLogoUrl;

    public function __construct($team, FormationService $formations)
    {
        $this->team = $team;
        $adminService = $formations->admin();
        $this->catalog = $adminService->listWithTeamFlags($team);
        $this->activeCount = $this->catalog->where('is_visible', '>', 0)->count();
        $this->totalCount = $this->catalog->count();
        $this->totalUsers = max(0, $team->allUsers()->count() - 1);
        $this->usersLabel = $this->totalUsers > 1 ? __('utilisateurs') : __('utilisateur');
        $user = Auth::user();
        $this->adminName = $user?->name ?? __('Administrateur');
        $this->visiblePercentage = $this->totalCount > 0 ? (int) round(($this->activeCount / max(1, $this->totalCount)) * 100) : 0;
        $this->usersProgressWidth = min(100, max(12, $this->totalUsers * 8));
        $this->formationsProgressWidth = min(100, max(8, $this->visiblePercentage));
        $this->hasTeamLogo = ! empty($team->profile_photo_path);
        $this->teamLogoUrl = $this->hasTeamLogo
            ? $this->publicStorageUrl($team->profile_photo_path)
            : null;
    }

    public function render(): View
    {
        return view('in-application.admin.partials.menu-fast.admin-menu-fast', [
            'team' => $this->team,
            'activeCount' => $this->activeCount,
            'totalCount' => $this->totalCount,
            'totalUsers' => $this->totalUsers,
            'usersLabel' => $this->usersLabel,
            'adminName' => $this->adminName,
            'visiblePercentage' => $this->visiblePercentage,
            'usersProgressWidth' => $this->usersProgressWidth,
            'formationsProgressWidth' => $this->formationsProgressWidth,
            'hasTeamLogo' => $this->hasTeamLogo,
            'teamLogoUrl' => $this->teamLogoUrl,
        ]);
    }

    private function publicStorageUrl(string $path): string
    {
        $relativePath = ltrim($path, '/');

        return Storage::disk('public')->url($relativePath);
    }
}
