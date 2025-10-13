<?php

namespace App\Livewire;

use App\Models\Formation;
use Livewire\Component;

class FormationByTeam extends Component
{
    public $formations = [];
    public $team;
    public $display = 'eleve'; // eleve | admin


    public function mount($team, $display = 'eleve'){
        $this->team = $team;
        $this->display = $display;

        if($this->display == 'eleve'){
            $this->formationToEleveTeam();
        }  
        if($this->display == 'admin'){
           $this->formationToAdminTeam();
        }
        // else {
        //     $this->formationToAdminTeam();
        // }
    }

    // Récupère les formations associées à l'équipe de l'élève connecté
    public function formationToEleveTeam(){


        $this->formations = Formation::
            ForTeam($this->team->id)->get();


    }
    // Récupère les formations associées à l'équipe de l'admin connecté
    public function formationToAdminTeam(){


        $this->formations = Formation::
            AdminWithTeamLink($this->team)->get();


    }

    public function render()
    {
        
        return view('livewire.formation-by-team');
    }
}
