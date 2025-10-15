<?php

namespace App\Livewire;

use App\Models\Formation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Services\FormationEnrollmentService;

class FormationList extends Component
{
    public $formations = [];
    public $team;
    public $display = 'eleve'; // eleve | admin
    protected FormationEnrollmentService $formationService;

    public function __construct()
    {
        $this->formationService = app(FormationEnrollmentService::class);
    }

    public function mount($team, $display = 'eleve'){
        $this->team = $team;

        // Sécurise la valeur
        $this->display = in_array($display, ['eleve','admin'], true) ? $display : 'eleve';

        // Si mode admin → exige le droit, sinon 403
        if ($this->display === 'admin') {
            abort_unless(Auth::user()->can('admin', $team), 403);
        }

        $this->loadFormations();
    }

    protected function loadFormations()
    {
        if ($this->display === 'eleve') {
            $this->formations = 
        } else {
            $this->formations = Formation::AdminWithTeamLink($this->team)->get();
        }
    }

    public function render()
    {
        return view('livewire.team.formation-list');
    }
}
