<?php

namespace App\Livewire\Formateur\Formations;

use App\Models\Formation;
use Livewire\Component;

class FormationsList extends Component
{
    public $formations;
    public $search = '';

    public function mount()
    {
        $this->loadFormations();
    }

    public function updatedSearch()
    {
        $this->loadFormations();
    }

    public function loadFormations()
    {
        $query = Formation::query();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        $this->formations = $query->get();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->loadFormations();
    }

    public function render()
    {
        return view('livewire.formateur.formations.formations-list', [
            'formations' => $this->formations,
        ]);
    }
}
