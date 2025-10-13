<?php

namespace App\Livewire;

use Livewire\Component;

class FormationByTeam extends Component
{
    public $formations = [];
    public $team;
    public function render()
    {
        return view('livewire.formation-by-team');
    }
}
