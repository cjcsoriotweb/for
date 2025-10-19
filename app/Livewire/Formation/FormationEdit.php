<?php

namespace App\Livewire\Formation;

use App\Providers\FormationServiceProvider;
use Livewire\Component;

class FormationEdit extends Component
{
    public $formationService;
    public $formation;

    public $chapterEdition = false;
    
    public function mount($formation)
    {
        dd(new FormationServiceProvider());
        $this->formation = $formation;
    }

    public function editChapter($chapterId)
    {
        $this->chapterEdition = $chapterId;
    }
    
    public function addChapter()
    {
        $this->formation->superadmin()->addChapter($this->formation, [
            'title' => 'Nouveau Chapitre',
        ]);
    }
    
    public function render()
    {
        return view('livewire.formation.formation-edit');
    }
}