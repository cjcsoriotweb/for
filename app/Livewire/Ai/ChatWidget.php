<?php

namespace App\Livewire\Ai;

use Livewire\Attributes\On;
use Livewire\Component;

class ChatWidget extends Component
{
    public bool $showLauncher = false;
    public int $notifications = 0;
    public bool $enable = true;
    public bool $locked = false;
    public bool $wizz = false;
    public bool $isOpen = false;
    public string $mode = 'assistant'; // 'assistant' or 'tutor'

    public function toggle(string $mode = null): void
    {
        if (! $this->enable) {
            return;
        }
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
