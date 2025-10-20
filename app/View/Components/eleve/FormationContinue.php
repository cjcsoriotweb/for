<?php

namespace App\View\Components\Eleve;

use App\Models\Team;
use App\Services\FormationService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class FormationContinue extends Component
{
    public $currentFormation;
    /**
     * Create a new component instance.
     */
    public function __construct(FormationService $formations, Team $team)
    {
        $this->currentFormation = $formations->student()->listFormationCurrentByStudent($team, Auth::user());
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.eleve.FormationContinue');
    }
}
