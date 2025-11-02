<?php

namespace App\Livewire;

use App\Models\AiTrainer;
use App\Services\Ai\OllamaClient;
use App\Services\Ai\ToolExecutor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

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

    public $messagesLimit = 15;

    public $canLoadMore = false;

    public $isLoadingMore = false;

    protected $listeners = [
        'sendMessageFromOutside' => 'sendMessage',
        'launchAssistant' => 'onLaunchAssistant',
    ];

    protected OllamaClient $ollamaClient;

    protected ToolExecutor $toolExecutor;

    protected ?AiTrainer $trainerModel = null;

    public function boot(OllamaClient $ollamaClient, ToolExecutor $toolExecutor): void
    {
        $this->ollamaClient = $ollamaClient;
        $this->toolExecutor = $toolExecutor;
    }

    public function mount($trainer, $title = null)
    {
        $this->trainer = $trainer;
        $this->title = $title;
        $this->messages = [];

        // Charger les meta du trainer
        $this->loadTrainerModel($this->trainer);
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

        $user = $this->user();
        if (! $user) {
            $this->error = __('Authentification requise.');

            return;
        }

        $this->isSending = true;
        $this->error = null;

        try {
            // Ajouter le message utilisateur immédiatement
            $this->messages[] = [
                'role' => 'user',
                'content' => $text,
                'at' => now()->toDateTimeString(),
            ];

            $this->message = '';

            // Demander au navigateur de scroller
            $this->dispatch('chatbox-scroll');

            // Traiter la réponse IA de manière asynchrone
            $this->dispatch('trigger-ai-response', ['text' => $text]);

        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Erreur lors de l\'envoi du message.');
            $this->isSending = false;
        }
    }

    public function processAiResponse($text)
    {
        $this->isLoading = true;

        try {
            // Préparer le message pour l'IA avec l'historique récent
            $messages = $this->prepareMessages($text);

            $result = $this->ollamaClient->chat($messages);
            $reply = $this->sanitizeUtf8String((string) ($result['text'] ?? 'Erreur: pas de réponse'));

        } catch (Throwable $exception) {
            report($exception);
            $reply = 'Réponse IA simulée à : '.$text.' (Erreur: '.$exception->getMessage().')';
        }

        // Ajouter la réponse
        $this->messages[] = [
            'role' => 'assistant',
            'content' => $reply,
            'at' => now()->toDateTimeString(),
        ];

        $this->isSending = false;
        $this->isLoading = false;
        $this->dispatch('chatbox-scroll');
    }

    public function loadMoreMessages()
    {
        $this->isLoadingMore = true;

        // Augmenter la limite de messages affichés
        $this->messagesLimit += 15;

        // Simuler un délai pour l'effet visuel
        $this->dispatch('load-more-messages', [
            'limit' => $this->messagesLimit,
            'scrollToTop' => true,
        ]);

        $this->isLoadingMore = false;
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

    protected function prepareMessages(string $userMessage): array
    {
        $messages = [];

        // Ajouter le prompt système si disponible
        if ($this->trainerModel) {
            $systemPrompt = $this->trainerModel->systemPrompt();
            if ($systemPrompt) {
                $messages[] = [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ];
            }
        }

        // Déterminer si on peut charger plus de messages
        $this->canLoadMore = count($this->messages) > $this->messagesLimit;

        // Ajouter les derniers messages de l'historique selon la limite actuelle
        $historyMessages = array_slice($this->messages, -$this->messagesLimit);
        foreach ($historyMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        // Ajouter le nouveau message utilisateur
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        return $messages;
    }

    protected function loadTrainerModel(?string $slug = null): void
    {
        $desiredSlug = $slug !== null ? trim($slug) : null;

        $baseQuery = AiTrainer::query()->active();
        $trainer = $desiredSlug ? (clone $baseQuery)->where('slug', $desiredSlug)->first() : null;

        if (! $trainer) {
            $trainer = (clone $baseQuery)->where('show_everywhere', true)->orderBy('sort_order')->orderBy('name')->first();
        }

        if (! $trainer) {
            $trainer = $baseQuery->orderBy('sort_order')->orderBy('name')->first();
        }

        if (! $trainer) {
            $this->trainerModel = null;
            $this->assistantMeta = [
                'name' => 'Assistant',
                'description' => '',
            ];

            return;
        }

        $this->trainerModel = $trainer;
        $this->trainer = $trainer->slug;
        $this->assistantMeta = [
            'name' => $trainer->name,
            'description' => $trainer->description ?? '',
        ];
    }

    protected function user(): ?Authenticatable
    {
        return Auth::user();
    }

    protected function sanitizeUtf8String(string $input): string
    {
        if (mb_check_encoding($input, 'UTF-8')) {
            $s = $input;
        } else {
            $encoding = mb_detect_encoding($input, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'CP1252'], true) ?: 'ISO-8859-1';
            $s = @mb_convert_encoding($input, 'UTF-8', $encoding);
            if ($s === false) {
                $s = @iconv('UTF-8', 'UTF-8//IGNORE', $input) ?: '';
            }
        }

        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s) ?? $s;
        $s = @iconv('UTF-8', 'UTF-8//IGNORE', $s) ?: '';

        return $s;
    }

    public function render()
    {
        return view('livewire.chat-box');
    }
}
