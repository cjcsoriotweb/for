<?php

namespace App\View\Components\admin;

use App\Services\FormationService;
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
    public function __construct($team, FormationService $formations)
    {
        $this->team = $team;
        $adminService = $formations->admin();
        $this->catalog = $adminService->listWithTeamFlags($team);
        $this->activeCount = $this->catalog->where('is_visible', '>', 0)->count();
        $this->totalCount = $this->catalog->count();
    }
    public function render(): View
    {
        return view('clean.admin.partials.menu-fast.admin-menu-fast', [
            'team' => $this->team,
            'activeCount' => $this->activeCount,
            'totalCount' => $this->totalCount,
        ]);
    }
}