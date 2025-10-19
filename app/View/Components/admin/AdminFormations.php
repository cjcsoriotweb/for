<?php

namespace App\View\Components\admin;

use App\Services\FormationService;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminFormations extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public $team;
    public $activeCount;
    public $totalCount;
    public $formations;
    public function __construct($team, FormationService $formations)
    {
        $this->team = $team;
        $adminService = $formations->admin();
        $this->formations = $adminService->listWithTeamFlags($team);
        $this->activeCount = $this->formations->where('is_visible', '>', 0)->count();
        $this->totalCount = $this->formations->count();
    }
    public function render(): View
    {
        return view('clean.admin.formations.listFormations', [
            'team' => $this->team,
            'activeCount' => $this->activeCount,
            'totalCount' => $this->totalCount,
        ]);
    }
}