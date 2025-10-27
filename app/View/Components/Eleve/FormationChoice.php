<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\Formation\StudentFormationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormationChoice extends Component
{
    public $availableFormations;

    public $team;

    /**
     * Create a new component instance.
     */
    public function __construct(StudentFormationService $studentFormationService, Team $team)
    {
        $this->team = $team;
        $this->availableFormations = $studentFormationService->listAvailableFormationsForTeamExceptCurrentUseByMe($team);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.FormationChoice', [
            'team' => $this->team,
        ]);
    }
}
