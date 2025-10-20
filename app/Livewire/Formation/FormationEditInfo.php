<?php

namespace App\Livewire\Formation;

use App\Models\Formation;
use Livewire\Component;

class FormationEditInfo extends Component
{
    public Formation $formation;

    public $data = [
        'title' => '',
        'description' => '',
    ];

    public $editing = false;

    public function mount(Formation $formation): void
    {
        $this->data['title'] = $formation->title;
        $this->data['description'] = $formation->description;
    }

    public function save()
    {
        // Validation des données
        $validatedData = $this->validate([
            'data.title' => 'required|string|max:255',
            'data.description' => 'nullable|string',
        ]);

        // Mise à jour de la formation
        $this->formation->update([
            'title' => $validatedData['data']['title'],
            'description' => $validatedData['data']['description'],
        ]);

        // Désactiver le mode édition après la sauvegarde
        $this->editing = false;
    }

    public function render()
    {
        return view('livewire.formation.formation-edit-info', [
            'formation' => $this->formation, // utile si ta vue l’attend
        ]);
    }
}
