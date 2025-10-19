<?php

namespace App\Livewire\Formation;

use App\Models\Formation;
use App\Services\FormationService;
use Livewire\Component;

class FormationEdit extends Component
{
    /** La formation éditée (via route model binding idéalement) */
    public Formation $formation;

    public function mount(Formation $formation): void
    {
        $this->formation = $formation->load(['chapters.lessons']);
    }


    public function render()
    {
        return view('livewire.formation.formation-edit', [
            'formation' => $this->formation, // utile si ta vue l’attend
        ]);
    }
}