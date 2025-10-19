<?php

namespace App\Livewire\Formation;

use App\Services\Formation\SuperAdminFormationService;
use App\Services\FormationService;
use Livewire\Component;

class FormationEdit extends Component
{
    public $formationService;
    public $formation;

    public $chapterEdition = false;
    
    public function mount($formation)
    {
        $this->formation = $formation;
    }

    public function editChapter($chapterId)
    {
        $this->chapterEdition = $chapterId;
    }
    
    public function addChapter()
    {
        app(FormationService::class)->superAdmin()->createChapter($this->formation);
    }
    
    public function render()
    {
        return view('livewire.formation.formation-edit');
    }
}