<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\Formation\StudentFormationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FormationContinue extends Component
{
    public $currentFormation;

    public $team;

    public $formationsWithProgress;

    /**
     * Create a new component instance.
     */
    public function __construct(
        StudentFormationService $studentFormationService,
        Team $team,
        $formations = null
    ) {
        $this->team = $team;

        if ($formations) {
            // Use formations passed from controller with progress data
            $this->formationsWithProgress = $formations;
        } else {
            // Fallback to service call if no formations provided
            $this->formationsWithProgress = $studentFormationService->listFormationCurrentByStudent($team, Auth::user());
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.FormationContinue', [
            'team' => $this->team,
            'formationsWithProgress' => $this->formationsWithProgress,
        ]);
    }
}
