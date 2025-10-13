<?php

namespace App\Livewire;

use App\Models\Formation;
use Livewire\Component;

class teamNeedFormationList extends Component
{
    public $formations = [];
    public $team;
    public $display = 'eleve'; // eleve | admin

    public function mount($team, $display = 'eleve'){


        $this->team = $team;

        // Sécurise la valeur
        $this->display = in_array($display, ['eleve','admin'], true) ? $display : 'eleve';

        // Si mode admin → exige le droit, sinon 403
        if ($this->display === 'admin') {
            abort_unless(auth()->user()?->can('access-admin', $team), 403);
        }

        // Si tu veux précharger/initialiser, OK, mais évite les requêtes lourdes ici
        if ($this->display === 'eleve') {
            $this->formationToEleveTeam();   // idéalement prépare l’état (filtres), pas un fetch massif
        } else {
            $this->formationToAdminTeam();
        }
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
        
        return view('livewire.team.teamNeedFormationList');
    }
}
