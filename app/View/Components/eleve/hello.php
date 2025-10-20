<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\Formation\StudentFormationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Hello extends Component
{
    public $currentFormation;

    public $team;

    /**
     * Create a new component instance.
     */
    public function __construct(StudentFormationService $studentFormationService, Team $team)
    {
        $this->team = $team;
        $this->currentFormation = $studentFormationService->listFormationCurrentByStudent($team, Auth::user());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.hello', [
            'team' => $this->team,
        ]);
    }
}
