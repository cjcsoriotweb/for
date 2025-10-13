<?php

namespace App\Livewire;

use App\Models\FormationTeam;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Livewire\Component;

class FormationByTeam extends Component
{
    public $formations = [];
    public $team;




    // Récupère les formations associées à l'équipe de l'élève connecté
    public function formationToEleveTeam(){


        $this->formations = FormationTeam::where(['team_id' => $this->team->id])
        ->visible($this->team)
        ->with('formation')
        ->with('formation_user')
        ->get();


    }



    public function render()
    {
        $this->formationToEleveTeam();
        return view('livewire.formation-by-team');
    }
}
