<?php

namespace App\Livewire;

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
        'launchAssistant' => 'onLaunchAssistant',
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
        $this->dispatch('chatbox-scroll');

        // Simuler une réponse IA via un event browser — le JS côté client appellera receiveIaReply
        $this->dispatch('chatbox-ia-reply', [
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
        $this->dispatch('chatbox-scroll');
    }

    public function onLaunchAssistant($payload = null)
    {
        $slug = null;

        if (is_array($payload) && array_key_exists('slug', $payload)) {
            $slug = $payload['slug'];
        } elseif (is_object($payload) && property_exists($payload, 'slug')) {
            $slug = $payload->slug;
        } elseif (is_string($payload)) {
            $slug = $payload;
        }

        if (! is_string($slug) || trim($slug) === '') {
            return;
        }

        // Only open if this component instance corresponds to the requested trainer
        if ($slug === $this->trainer) {
            $this->isOpen = true;
        }
    }

    public function renderMessageHtml(string $content): string
    {
        // Échapper le HTML pour éviter les attaques XSS
        $escaped = e($content);

        // Convertir le Markdown simple en HTML
        $escaped = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $escaped);
        $escaped = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $escaped);
        $escaped = preg_replace('/`(.+?)`/s', '<code class="bg-gray-200 px-1 rounded">$1</code>', $escaped);
        $escaped = preg_replace('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', '<a href="$2" target="_blank" class="text-blue-600 underline hover:text-blue-800">$1</a>', $escaped);

        // Convertir les sauts de ligne en <br>
        $escaped = nl2br($escaped);

        return $escaped;
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
