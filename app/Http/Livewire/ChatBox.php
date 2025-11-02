<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AiTrainer;

class ChatBox extends Component
{
    public $trainer;
    public $title;
    public $isOpen = false;
    public $assistantMeta = [];
    public $messages = [];
    public $isLoading = false;
    public $isSending = false;
    public $message = '';
    public $error = null;

    protected $listeners = [
        'sendMessageFromOutside' => 'sendMessage',
    ];

    public function mount($trainer, $title = null)
    {
        $this->trainer = $trainer;
        $this->title = $title;
        $this->messages = [];
        $this->assistantMeta = [];

        // Charger les meta du trainer si disponible
        try {
            $t = AiTrainer::where('slug', $trainer)->first();
            if ($t) {
                $this->assistantMeta = [
                    'name' => $t->name,
                    'description' => $t->description ?? null,
                    'slug' => $t->slug,
                ];
                if (! $this->title) {
                    $this->title = $t->name;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function toggle()
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function sendMessage()
    {
        $text = trim($this->message);
        if ($text === '') {
            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'content' => $text,
            'at' => now()->toDateTimeString(),
        ];

        $this->message = '';
        $this->isSending = true;
        $this->isLoading = true;
        $this->error = null;

        // Demander au navigateur de scroller
        $this->dispatchBrowserEvent('chatbox-scroll');

        // Simuler une réponse IA via un event browser — le JS côté client appellera receiveIaReply
        $this->dispatchBrowserEvent('chatbox-ia-reply', [
            'trainer' => $this->trainer,
            'text' => $text,
        ]);
    }

    public function receiveIaReply($reply)
    {
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $reply,
            'at' => now()->toDateTimeString(),
        ];
        $this->isSending = false;
        $this->isLoading = false;
        $this->dispatchBrowserEvent('chatbox-scroll');
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
