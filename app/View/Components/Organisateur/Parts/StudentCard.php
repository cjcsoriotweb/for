<?php

namespace App\View\Components\Organisateur\Parts;

use App\Models\Formation;
use App\Models\Team;
use Illuminate\View\Component;
use Illuminate\View\View;

class StudentCard extends Component
{
    public object $summary;
    public Formation $formation;
    public Team $team;

    public function __construct(
        object $summary,
        Formation $formation,
        Team $team
    ) {
        $this->summary = $summary;
        $this->formation = $formation;
        $this->team = $team;
    }

    public function render(): View
    {
        return view('clean.organisateur.parts.student-card', [
            'summary' => $this->summary,
            'formation' => $this->formation,
            'team' => $this->team,
        ]);
    }
}
