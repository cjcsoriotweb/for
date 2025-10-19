<?php

namespace App\Livewire\Formation;

use App\Models\Formation;
use App\Services\FormationService;
use Livewire\Component;

class FormationEditInfo extends Component
{
    public Formation $formation;


    public function mount(Formation $formation): void
    {
        $this->formation = $formation->load(['chapters.lessons']);
    }

  
    public function render()
    {
        return view('livewire.formation.formation-edit-info', [
            'formation' => $this->formation, // utile si ta vue l’attend
        ]);
    }
}