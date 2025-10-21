<?php

namespace App\Livewire\Formateur\Formation;

use Livewire\Component;

class TiptapEditor extends Component
{
    public $content = '';
    public $name = 'content';

    public function mount($content = '', $name = 'content')
    {
        $this->content = $content;
        $this->name = $name;
    }

    public function updatedContent()
    {
        $this->dispatch('content-updated', $this->content);
    }

    public function render()
    {
        return view('livewire.formateur.formation.tiptap-editor');
    }
}
