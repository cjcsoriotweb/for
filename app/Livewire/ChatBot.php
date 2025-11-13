<?php

namespace App\Livewire;

use Livewire\Component;

class ChatBot extends Component
{
    /**
     * @var array<int, array<string, string>>
     */
    public array $messages = [];

    public string $body = '';

    protected array $rules = [
        'body' => 'required|string|min:1|max:500',
    ];

    public function mount(): void
    {
        $this->messages = [
            [
                'sender' => 'bot',
                'text' => "Bonjour ! Je suis EvoBot. Decrivez-moi votre besoin et je vous reponds immediatement.",
                'time' => now()->format('H:i'),
            ],
        ];
    }

    public function sendMessage(): void
    {
        $this->validate();

        $content = trim($this->body);

        $this->pushMessage('user', $content);
        $this->body = '';

        $this->pushMessage('bot', $this->generateReply($content));

        $this->dispatch('chat-scrolled');
    }

    public function render()
    {
        return view('livewire.chat-bot');
    }

    private function pushMessage(string $sender, string $text): void
    {
        $this->messages[] = [
            'sender' => $sender,
            'text' => $text,
            'time' => now()->format('H:i'),
        ];
    }

    private function generateReply(string $message): string
    {
        $normalized = mb_strtolower($message);

        $faq = [
            'bonjour' => "Bonjour ! Souhaitez-vous parler d un produit, d un service ou d un devis ?",
            'prix' => "Nos tarifs varient selon la complexite du projet. Partagez quelques details et je vous oriente.",
            'contact' => "Vous pouvez nous joindre par telephone ou via le formulaire de contact. Souhaitez-vous les coordonnees ?",
            'rdv' => "Tres bien, indiquez un creneau et nous vous confirmons rapidement.",
            'merci' => "Avec plaisir ! Puis-je faire autre chose pour vous ?",
        ];

        foreach ($faq as $keyword => $reply) {
            if (str_contains($normalized, $keyword)) {
                return $reply;
            }
        }

        return "Merci pour votre message. Je transmets a l equipe et je reviens vers vous tres vite.";
    }
}
