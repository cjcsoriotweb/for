<?php

namespace App\Livewire\Ai;

use Livewire\Attributes\On;
use Livewire\Component;

class ChatWidget extends Component
{
    public bool $showLauncher = false;
    public bool $isOpen = false;
    public string $mode = 'assistant'; // 'assistant' or 'tutor'

    public function toggle(string $mode = null): void
    {
        if ($mode) {
            $this->mode = $mode;
        }
        $this->isOpen = ! $this->isOpen;
    }

    #[On('assistant-toggle')]
    public function onAssistantToggle(): void
    {
        $this->toggle('assistant');
    }

    #[On('tutor-toggle')]
    public function onTutorToggle(): void
    {
        $this->toggle('tutor');
    }

    public function render()
    {
        return view('livewire.ai.chat-widget');
    }
}

