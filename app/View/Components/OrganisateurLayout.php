<?php

namespace App\View\Components;

use App\Models\Team;
use Illuminate\View\Component;
use Illuminate\View\View;

class OrganisateurLayout extends Component
{
    public $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function render(): View
    {
        return view('components.organisateur.layout', ['team' => $this->team]);
    }
}
