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
    protected $listeners = [
        // Accept both Livewire.emit('launchAssistant', slug) and Livewire.dispatch('launchAssistant', { slug })
        'launchAssistant' => 'onLaunchAssistant',
    ];
    public string $trainer = 'default';
    public ?int $conversationId = null;
    public bool $isOpen = false;
    public string $title = 'Assistant IA';
    public array $messages = [];
    public string $message = '';
    public bool $isLoading = false;
    public bool $isSending = false;
    public ?string $error = null;
    public array $assistantMeta = [];
    public array $shortcodeTemplates = [];

    protected OllamaClient $ollamaClient;
    protected ToolExecutor $toolExecutor;
    protected ?AiTrainer $trainerModel = null;

    public function boot(OllamaClient $ollamaClient, ToolExecutor $toolExecutor): void
    {
        $this->ollamaClient = $ollamaClient;
        $this->toolExecutor = $toolExecutor;
    }

    public function mount(?string $trainer = null, ?int $conversationId = null, string $title = 'Assistant IA'): void
    {
        $defaultTrainer = config('ai.default_trainer_slug', 'default');
        $resolvedTrainer = is_string($trainer) && $trainer !== '' ? $trainer : $defaultTrainer;

        $this->trainer = $resolvedTrainer;
        $this->conversationId = $conversationId;
        $this->title = $this->sanitizeUtf8String($title);
        $this->loadTrainerModel($resolvedTrainer);
        $this->shortcodeTemplates = $this->resolveShortcodeTemplates();

        if ($this->conversationId) {
            $this->loadConversation($this->conversationId);
        }
    }

    public function render()
    {
        $this->ensureValidUtf8State();

        return view('livewire.chat-box');
    }

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;

        if ($this->isOpen) {
            $this->ensureConversation();
        }
    }

    /**
     * Handle a global request to launch/open an assistant.
     * Payload can be a string slug or an object containing { slug }.
     */
    public function onLaunchAssistant($payload = null): void
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
            $this->ensureConversation();
        }
    }

    public function sendMessage(): void
    {
        if ($this->isSending) {
            return;
        }

        $this->validate([
            'message' => [
                'required',
                'string',
                'min:1',
                'max:' . (int) config('ai.max_message_length', 2000),
            ],
        ]);

        $user = $this->user();
        if (! $user) {
            $this->error = __('Authentification requise.');
            return;
        }

        $this->ensureConversation();

        if (! $this->conversationId) {
            return;
        }

        $conversation = $this->conversation();
        if (! $conversation) {
            $this->error = __('Conversation introuvable.');
            return;
        }

        $trainerConfig = $this->trainerConfig();
        $plainMessage = trim($this->message);
        $this->message = '';
        $this->isSending = true;
        $this->error = null;

        try {
            DB::transaction(function () use ($conversation, $plainMessage, $user, $trainerConfig): void {
                $userContent = $this->sanitizeUtf8String($plainMessage);

                $conversation->messages()->create([
                    'role' => AiConversationMessage::ROLE_USER,
                    'content' => $userContent,
                    'user_id' => $user->getAuthIdentifier(),
                ]);

                $conversationFresh = $conversation->fresh('messages');
                if (! $conversationFresh instanceof AiConversation) {
                    throw new \RuntimeException('Conversation introuvable.');
                }

                $this->loadTrainerModel($conversationFresh->metadata['trainer'] ?? $this->trainer);
                $trainerConfig = $this->trainerConfig();

                $messages = $this->prepareMessages($conversationFresh, $trainerConfig);
                $options = [
                    'model' => $trainerConfig['model'] ?? null,
                    'temperature' => $trainerConfig['temperature'] ?? 0.7,
                ];

                $result = $this->ollamaClient->chat($messages, $options);
                $assistantText = $this->sanitizeUtf8String((string) ($result['text'] ?? ''));

                if ($trainerConfig['use_tools'] ?? false) {
                    $toolResult = $this->toolExecutor->parseAndExecuteTools($assistantText, $user);
                    $assistantText = $this->sanitizeUtf8String((string) ($toolResult['content'] ?? ''));
                }

                $conversation->messages()->create([
                    'role' => AiConversationMessage::ROLE_ASSISTANT,
                    'content' => $assistantText,
                ]);

                $conversation->forceFill([
                    'last_message_at' => now(),
                ])->save();
            });

            $this->loadConversation($this->conversationId);
        } catch (Throwable $exception) {
            report($exception);
            $this->error = __('Une erreur est survenue : :message', [
                'message' => $this->sanitizeUtf8String($exception->getMessage()),
            ]);
            $this->loadConversation($this->conversationId);
        } finally {
            $this->isSending = false;
        }
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

    public function renderMessageHtml(string $content): string
    {
        [$processed, $shortcodes] = $this->replaceShortcodesWithPlaceholders($content);

        $escaped = e($processed);

        $escaped = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $escaped);
        $escaped = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $escaped);
        $escaped = preg_replace('/`(.+?)`/s', '<code class="bg-gray-200 px-1 rounded">$1</code>', $escaped);
        $escaped = preg_replace('/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', '<a href="$2" target="_blank" class="text-blue-600 underline hover:text-blue-800">$1</a>', $escaped);

        $escaped = nl2br($escaped);

        foreach ($shortcodes as $placeholder => $html) {
            $escaped = str_replace($placeholder, $html, $escaped);
        }

        return $escaped;
    }

    /**
     * Ensure every public property that will be dehydrated is valid UTF-8.
     */
    protected function ensureValidUtf8State(): void
    {
        $this->title = $this->sanitizeUtf8String($this->title);
        $this->message = $this->sanitizeUtf8String($this->message);
        $this->error = $this->error !== null ? $this->sanitizeUtf8String($this->error) : null;

        $this->assistantMeta = $this->sanitizeUtf8Array($this->assistantMeta);

        $this->messages = collect($this->messages)
            ->map(function ($message) {
                if (! is_array($message)) {
                    return [
                        'id' => null,
                        'role' => '',
                        'content' => '',
                    ];
                }

                return [
                    'id' => $message['id'] ?? null,
                    'role' => $this->sanitizeUtf8String((string) ($message['role'] ?? '')),
                    'content' => $this->sanitizeUtf8String((string) ($message['content'] ?? '')),
                ];
            })
            ->values()
            ->all();

        $this->shortcodeTemplates = $this->sanitizeUtf8Array($this->shortcodeTemplates);
    }

    /**
     * Recursively sanitize array string values to valid UTF-8.
     */
    protected function sanitizeUtf8Array(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            $sanitizedKey = is_string($key) ? $this->sanitizeUtf8String($key) : $key;

            if (is_string($value)) {
                $sanitized[$sanitizedKey] = $this->sanitizeUtf8String($value);
            } elseif (is_array($value)) {
                $sanitized[$sanitizedKey] = $this->sanitizeUtf8Array($value);
            } else {
                $sanitized[$sanitizedKey] = $value;
            }
        }

        return $sanitized;
    }

    protected function replaceShortcodesWithPlaceholders(string $content): array
    {
        $templates = $this->shortcodeTemplates;
        $placeholders = [];

        $processed = preg_replace_callback('/\[([A-Z0-9_]+)\]/', function ($matches) use (&$placeholders, $templates) {
            $key = strtoupper($matches[1]);
            if (! array_key_exists($key, $templates)) {
                return $matches[0];
            }

            $placeholder = '__SHORTCODE_' . count($placeholders) . '__';
            $placeholders[$placeholder] = $templates[$key];

            return $placeholder;
        }, $content) ?? $content;

        return [$processed, $placeholders];
    }

    protected function resolveShortcodeTemplates(): array
    {
        $shortcodes = config('ai.shortcodes', []);
        $templates = [];

        foreach ($shortcodes as $name => $options) {
            $view = $options['view'] ?? null;
            $data = $options['data'] ?? [];

            if (! is_string($view) || $view === '') {
                continue;
            }

            try {
                $html = trim(view($view, $data)->render());
                $html = $this->sanitizeUtf8String((string) $html);
                $templates[strtoupper((string) $name)] = $html;
            } catch (Throwable $exception) {
                report($exception);
                continue;
            }
        }

        return $templates;
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
}



