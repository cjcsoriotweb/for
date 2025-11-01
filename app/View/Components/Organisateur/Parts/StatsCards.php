<?php

namespace App\View\Components\Organisateur\Parts;

use App\Models\Formation;
use App\Models\Team;
use Illuminate\View\Component;
use Illuminate\View\View;

class StatsCards extends Component
{
    public array $stats;

    public string $type;

    public ?Team $team;

    public ?Formation $formation;

    public ?int $monthlyCost;

    public int $monthlyEnrollmentsCount;

    public function __construct(
        array $stats = [],
        string $type = 'default',
        ?Team $team = null,
        ?Formation $formation = null,
        ?int $monthlyCost = null,
        int $monthlyEnrollmentsCount = 0
    ) {
        $this->stats = $stats;
        $this->type = $type;
        $this->team = $team;
        $this->formation = $formation;
        $this->monthlyCost = $monthlyCost;
        $this->monthlyEnrollmentsCount = $monthlyEnrollmentsCount;
    }

    public function render(): View
    {
        return view('in-application.organisateur.parts.stats-cards', [
            'stats' => $this->stats,
            'type' => $this->type,
            'team' => $this->team,
            'formation' => $this->formation,
            'monthlyCost' => $this->monthlyCost,
            'monthlyEnrollmentsCount' => $this->monthlyEnrollmentsCount,
        ]);
    }
}
