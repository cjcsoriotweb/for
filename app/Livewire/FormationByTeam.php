<?php

namespace App\Livewire;

use App\Models\Formation;
use App\Models\FormationTeam;
use Livewire\Component;

class FormationByTeam extends Component
{
    public $formations = [];
    public $team;

    public function searchLikeEleve(){
        $this->formations = FormationTeam::where('team_id', $this->team->id)->with('formation')->with('formation_user')->get();
    }

    public function render()
    {
        $this->searchLikeEleve();
        return view('livewire.formation-by-team');
    }
}
