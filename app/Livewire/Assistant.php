<?php

namespace App\Livewire;

use App\Models\AssistantMessage;
use Livewire\Component;

class Assistant extends Component
{
    public bool $isOpen = false;
    public $messages = [];

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        return view('livewire.assistant');
    }
}
