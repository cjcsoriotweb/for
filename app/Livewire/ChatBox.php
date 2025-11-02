<?php

namespace App\Livewire;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Models\AiTrainer;
use App\Services\Ai\OllamaClient;
use App\Services\Ai\ToolExecutor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public ?int $conversationId = null;

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

        // Ajouter le message utilisateur immédiatement
        $this->messages[] = [
            'role' => 'user',
            'content' => $text,
            'at' => now()->toDateTimeString(),
        ];

        $this->message = '';
        $this->isSending = true;
        $this->error = null;

        // Demander au navigateur de scroller
        $this->dispatch('chatbox-scroll');

        // Dispatch un événement pour traiter la réponse IA de manière asynchrone
        $this->dispatch('process-ai-response', ['text' => $text]);
    }

    public function processAiResponse($text)
    {
        $this->isLoading = true;

        try {
            // Essayer d'appeler Ollama pour une vraie réponse
            $messages = [
                [
                    'role' => 'user',
                    'content' => $text,
                ],
            ];

            $result = $this->ollamaClient->chat($messages);
            $reply = $this->sanitizeUtf8String((string) ($result['text'] ?? 'Erreur: pas de réponse'));

        } catch (Throwable $exception) {
            // En cas d'erreur, utiliser une réponse simulée
            report($exception);
            $reply = 'Réponse IA simulée à : '.$text.' (Erreur Ollama: '.$exception->getMessage().')';
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

    protected function callDelayedReply($text)
    {
        // Dispatch un événement pour que le JavaScript gère le délai
        $this->dispatch('simulate-reply', ['text' => $text]);
    }

    public function simulateAiReply($text)
    {
        $reply = 'Réponse IA simulée à : '.$text;

        $this->messages[] = [
            'role' => 'assistant',
            'content' => $reply,
            'at' => now()->toDateTimeString(),
        ];

        $this->isSending = false;
        $this->isLoading = false;
        $this->dispatch('chatbox-scroll');
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

    protected function ensureConversation(): void
    {
        if ($this->conversationId) {
            $this->loadConversation($this->conversationId);

            return;
        }

        $this->loadTrainerModel($this->trainer);
        if (! $this->trainerModel) {
            return;
        }

        $user = $this->user();
        if (! $user) {
            $this->error = __('Authentification requise.');

            return;
        }

        $trainerConfig = $this->trainerConfig();

        $this->isLoading = true;
        $this->error = null;

        try {
            DB::transaction(function () use ($user, $trainerConfig): void {
                $conversation = AiConversation::create([
                    'user_id' => $user->getAuthIdentifier(),
                    'team_id' => $user->currentTeam?->id,
                    'status' => AiConversation::STATUS_ACTIVE,
                    'metadata' => [
                        'trainer' => $this->trainer,
                        'model' => $trainerConfig['model'] ?? null,
                    ],
                    'last_message_at' => now(),
                ]);

                $this->conversationId = $conversation->id;
                $this->loadConversationData($conversation->fresh('messages'));
            });
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Impossible de creer la conversation.');
        } finally {
            $this->isLoading = false;
        }
    }

    protected function loadConversation(int $conversationId): void
    {
        $user = $this->user();
        if (! $user) {
            $this->error = __('Authentification requise.');

            return;
        }

        $this->isLoading = true;
        $this->error = null;

        try {
            $conversation = AiConversation::query()
                ->whereKey($conversationId)
                ->where('user_id', $user->getAuthIdentifier())
                ->with('messages')
                ->first();

            if (! $conversation) {
                $this->error = __('Conversation introuvable.');
                $this->conversationId = null;
                $this->messages = [];

                return;
            }

            $this->loadConversationData($conversation);
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Erreur lors du chargement.');
        } finally {
            $this->isLoading = false;
        }
    }

    protected function loadConversationData(AiConversation $conversation): void
    {
        $metadataTrainer = (string) ($conversation->metadata['trainer'] ?? $this->trainer);

        $this->loadTrainerModel($metadataTrainer ?: $this->trainer);

        $this->conversationId = $conversation->id;

        $this->messages = $conversation->messages
            ->sortBy('id')
            ->map(fn (AiConversationMessage $message) => $this->presentMessage($message))
            ->values()
            ->all();
    }

    protected function presentMessage(AiConversationMessage $message): array
    {
        return [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $this->sanitizeUtf8String((string) $message->content),
        ];
    }

    protected function prepareMessages(AiConversation $conversation, array $trainer): array
    {
        $messages = [];

        $systemPrompt = $this->sanitizeUtf8String((string) ($trainer['system_prompt'] ?? ''));
        if ($systemPrompt !== '') {
            $messages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        if ($trainer['use_tools'] ?? false) {
            $messages[] = [
                'role' => 'system',
                'content' => $this->sanitizeUtf8String(ToolExecutor::getToolsPrompt()),
            ];
        }

        $historyLimit = (int) config('ai.history_limit', 30);
        $history = $conversation->messages()
            ->latest('id')
            ->limit($historyLimit)
            ->get()
            ->sortBy('id')
            ->values();

        foreach ($history as $msg) {
            if ($msg->role === AiConversationMessage::ROLE_SYSTEM) {
                continue;
            }

            $messages[] = [
                'role' => $msg->role,
                'content' => $this->sanitizeUtf8String((string) $msg->content),
            ];
        }

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
                'name' => $this->sanitizeUtf8String('Assistant'),
                'description' => '',
            ];
            if ($this->error === null) {
                $this->error = __('Aucun assistant disponible.');
            }

            return;
        }

        $this->trainerModel = $trainer;
        $this->trainer = $trainer->slug;
        $this->assistantMeta = [
            'name' => $this->sanitizeUtf8String($trainer->name),
            'description' => $this->sanitizeUtf8String($trainer->description ?? ''),
        ];
    }

    protected function trainerConfig(): array
    {
        if (! $this->trainerModel) {
            return [];
        }

        return [
            'model' => $this->trainerModel->model,
            'temperature' => $this->trainerModel->temperature,
            'use_tools' => $this->trainerModel->use_tools,
            'system_prompt' => $this->trainerModel->systemPrompt(),
        ];
    }

    protected function conversation(): ?AiConversation
    {
        if (! $this->conversationId) {
            return null;
        }

        return AiConversation::query()->find($this->conversationId);
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
