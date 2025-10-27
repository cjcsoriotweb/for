<?php

namespace App\View\Components\Organisateur\Parts;

use App\Models\Formation;
use App\Models\Team;
use Illuminate\View\Component;
use Illuminate\View\View;

class FormationCard extends Component
{
    public Formation $formation;

    public Team $team;

    public function __construct(Formation $formation, Team $team)
    {
        $this->formation = $formation;
        $this->team = $team;
    }

    public function render(): View
    {
        return view('clean.organisateur.parts.formation-card', [
            'formation' => $this->formation,
            'team' => $this->team,
        ]);
    }
}
