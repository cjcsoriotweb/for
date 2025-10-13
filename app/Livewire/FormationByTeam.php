<?php

namespace App\Livewire;

use Livewire\Component;

class FormationByTeam extends Component
{
    public $formations = [];
    public function render()
    {
        return view('livewire.formation-by-team');
    }
}
