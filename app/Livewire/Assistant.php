<?php

namespace App\Livewire;

use Livewire\Component;

class Assistant extends Component
{
    public bool $isOpen = false;

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        return view('livewire.assistant');
    }
}
