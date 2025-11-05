<?php

namespace App\View\Components\Admin;

use App\Models\FormationInTeams;
use App\Services\FormationService;
use Illuminate\Support\Collection;
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

    public Collection $formationUsages;

    public function __construct($team, FormationService $formations)
    {
        $this->team = $team;
        $adminService = $formations->admin();
        $this->formations = $adminService->listWithTeamFlags($team);
        $this->activeCount = $this->formations->where('is_visible', '>', 0)->count();
        $this->totalCount = $this->formations->count();
        $this->formationUsages = $this->buildFormationUsages();
    }

    public function render(): View
    {
        return view('in-application.admin.formations.list-formations', [
            'team' => $this->team,
            'activeCount' => $this->activeCount,
            'totalCount' => $this->totalCount,
            'formationUsages' => $this->formationUsages,
        ]);
    }

    protected function buildFormationUsages(): Collection
    {
        if ($this->formations->isEmpty()) {
            return collect();
        }

        $formationIds = $this->formations->pluck('id')->all();

        return FormationInTeams::query()
            ->where('team_id', $this->team->id)
            ->whereIn('formation_id', $formationIds)
            ->get()
            ->keyBy('formation_id');
    }
}
