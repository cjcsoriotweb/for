<?php

namespace App\View\Components\Organisateur\Parts;

use App\Models\Formation;
use App\Models\Team;
use Illuminate\View\Component;
use Illuminate\View\View;

class FormationCatalogueCard extends Component
{
    public Formation $formation;
    public Team $team;
    public bool $isVisible;

    public function __construct(Formation $formation, Team $team, bool $isVisible = false)
    {
        $this->formation = $formation;
        $this->team = $team;
        $this->isVisible = $isVisible;
    }

    public function render(): View
    {
        return view('clean.organisateur.parts.formation-catalogue-card', [
            'formation' => $this->formation,
            'team' => $this->team,
        ]);
    }
}
