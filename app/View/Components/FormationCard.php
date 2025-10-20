<?php

namespace App\View\Components;

use App\Models\Formation;
use App\Models\Team;
use Illuminate\View\Component;

class FormationCard extends Component
{
    public Formation $formation;

    public bool $isEnrolled;

    public $formationUser;

    public bool $isAdminMode;

    public ?Team $team;

    /**
     * Create a new component instance.
     */
    public function __construct(
        Formation $formation,
        bool $isEnrolled = false,
        $formationUser = null,
        bool $isAdminMode = false,
        ?Team $team = null
    ) {
        $this->formation = $formation;
        $this->isEnrolled = $isEnrolled;
        $this->formationUser = $formationUser;
        $this->isAdminMode = $isAdminMode;
        $this->team = $team;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.formation-card');
    }
}
