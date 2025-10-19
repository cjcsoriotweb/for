<?php

namespace App\Livewire\Formation;

use Livewire\Component;

class FormationEdit extends Component
{
    public $formation;
    public function mount($formation)
    {
        $this->formation = $formation;
    }

    public function addChapter()
    {
        $this->formation->chapters()->create([
            'title' => 'Nouveau Chapitre',
            'position' => $this->formation->chapters()->count() + 1,
        ]);
        $this->formation->load('chapters');
    }
    
    public function render()
    {
        return view('livewire.formation.formation-edit');
    }
}