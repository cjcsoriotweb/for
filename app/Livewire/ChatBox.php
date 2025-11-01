<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Composant Chat unique pour toutes les interactions IA.
 * Utilise le streaming NDJSON côté JavaScript.
 */
class ChatBox extends Component
{
    public string $trainer = 'default';
    public ?int $conversationId = null;
    public bool $isOpen = false;
    public string $title = 'Assistant IA';

    public function mount(?string $trainer = null, ?int $conversationId = null, string $title = 'Assistant IA'): void
    {
        $this->trainer = $trainer ?? config('ai.default_trainer_slug', 'default');
        $this->conversationId = $conversationId;
        $this->title = $title;

        // Vérifier que le trainer existe
        $trainerConfig = config("ai.trainers.{$this->trainer}");
        if (!$trainerConfig) {
            $this->trainer = config('ai.default_trainer_slug', 'default');
        }
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    public function render()
    {
        $trainerConfig = config("ai.trainers.{$this->trainer}");

        return view('livewire.chat-box', [
            'trainerName' => $trainerConfig['name'] ?? 'Assistant',
            'trainerDescription' => $trainerConfig['description'] ?? '',
        ]);
    }
}
