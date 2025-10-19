<?php

namespace App\Livewire\Formateur\Formations;

use App\Models\Formation;
use Livewire\Component;

class FormationsList extends Component
{
    public $formations;
    public function mount()
    {
        $this->formations = Formation::all();
        //
    }
    public function render()
    {
        return view('livewire.formateur.formations.formations-list', [
            'formations' => $this->formations,
        ]);
    }
}