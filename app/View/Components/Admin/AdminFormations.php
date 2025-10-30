<?php

namespace App\View\Components\Admin;

use App\Models\FormationUser;
use App\Services\FormationService;
use Carbon\Carbon;
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

    public $monthlyRevenue;

    public string $currentMonthLabel;

    public function __construct($team, FormationService $formations)
    {
        $this->team = $team;
        $adminService = $formations->admin();
        $this->formations = $adminService->listWithTeamFlags($team);
        $this->activeCount = $this->formations->where('is_visible', '>', 0)->count();
        $this->totalCount = $this->formations->count();
        $this->monthlyRevenue = $this->computeCurrentMonthRevenue();
        $this->currentMonthLabel = Carbon::now()->isoFormat('MMMM YYYY');
    }

    public function render(): View
    {
        return view('in-application.admin.formations.list-formations', [
            'team' => $this->team,
            'activeCount' => $this->activeCount,
            'totalCount' => $this->totalCount,
            'monthlyRevenue' => $this->monthlyRevenue,
            'currentMonthLabel' => $this->currentMonthLabel,
        ]);
    }

    protected function computeCurrentMonthRevenue(): Collection
    {
        if ($this->formations->isEmpty()) {
            return collect();
        }

        $formationIds = $this->formations->pluck('id')->all();
        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd = Carbon::now()->endOfMonth();

        $totals = FormationUser::query()
            ->selectRaw('formation_user.formation_id, SUM(COALESCE(formation_user.enrollment_cost, formations.money_amount, 0)) as total_tokens')
            ->join('formations', 'formations.id', '=', 'formation_user.formation_id')
            ->whereIn('formation_user.formation_id', $formationIds)
            ->where(function ($query) use ($periodStart, $periodEnd) {
                $query->whereBetween('formation_user.enrolled_at', [$periodStart, $periodEnd])
                    ->orWhere(function ($subQuery) use ($periodStart, $periodEnd) {
                        $subQuery->whereNull('formation_user.enrolled_at')
                            ->whereBetween('formation_user.created_at', [$periodStart, $periodEnd]);
                    });
            })
            ->groupBy('formation_user.formation_id')
            ->pluck('total_tokens', 'formation_user.formation_id');

        return collect($totals);
    }
}
