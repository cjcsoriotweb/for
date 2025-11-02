<?php

namespace App\Livewire;

use App\Models\AiConversation;
use App\Models\AiConversationMessage;
use App\Services\Ai\OllamaClient;
use App\Services\Ai\ToolExecutor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class ChatBox extends Component
{
    public string $trainer = 'default';
    public ?int $conversationId = null;
    public bool $isOpen = false;
    public string $title = 'Assistant IA';
    public array $shortcodeTemplates = [];

    public array $trainerOptions = [];
    public array $trainerMeta = [];
    public array $messages = [];
    public string $message = '';
    public bool $isLoading = false;
    public bool $isSending = false;
    public ?string $error = null;
    public string $selectedTrainer = '';
    public string $ticketsUrl = '/mes-tickets';
    public string $ticketButtonLabel;
    public array $suggestionPresets = [];

    protected OllamaClient $ollamaClient;
    protected ToolExecutor $toolExecutor;

    public function boot(OllamaClient $ollamaClient, ToolExecutor $toolExecutor): void
    {
        $this->ollamaClient = $ollamaClient;
        $this->toolExecutor = $toolExecutor;
    }

    public function mount(?string $trainer = null, ?int $conversationId = null, string $title = 'Assistant IA'): void
    {
        $defaultTrainer = config('ai.default_trainer_slug', 'default');

        $initialTrainer = trim((string) ($trainer ?? $defaultTrainer));
        $this->trainer = $initialTrainer !== '' ? $initialTrainer : $defaultTrainer;
        $this->selectedTrainer = $this->trainer;
        $this->conversationId = $conversationId;
        $this->title = $title;
        $this->shortcodeTemplates = $this->resolveShortcodeTemplates();
        $this->trainerOptions = $this->prepareTrainerOptions();
        $this->ticketButtonLabel = $this->sanitizeUtf8String((string) __('Consulter le ticket'));
        $this->ticketsUrl = $this->sanitizeUtf8String(route('user.tickets'));
        $this->suggestionPresets = $this->prepareSuggestionPresets();

        if (! array_key_exists($this->trainer, $this->trainerOptions)) {
            $this->trainer = $defaultTrainer;
            $this->selectedTrainer = $defaultTrainer;
        }

        $this->trainerMeta = $this->trainerOptions[$this->trainer] ?? [
            'name' => $this->sanitizeUtf8String('Assistant'),
            'description' => '',
        ];

        if ($this->conversationId) {
            $this->loadConversation($this->conversationId);
        }
    }

    public function render()
    {
        return view('livewire.chat-box');
    }

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;

        if ($this->isOpen) {
            $this->ensureConversation();
        }
    }

    public function updatedSelectedTrainer(string $value): void
    {
        $this->changeTrainer($value);
    }

    public function changeTrainer(string $slug): void
    {
        $slug = trim($slug);

        if ($slug === '' || $slug === $this->trainer || ! array_key_exists($slug, $this->trainerOptions)) {
            $this->selectedTrainer = $this->trainer;
            return;
        }

        $this->trainer = $slug;
        $this->selectedTrainer = $slug;
        $this->trainerMeta = $this->trainerOptions[$slug];
        $this->conversationId = null;
        $this->messages = [];
        $this->error = null;

        if ($this->isOpen) {
            $this->ensureConversation();
        }
    }

    public function sendSuggestedMessageEncoded(string $encoded): void
    {
        $text = base64_decode($encoded, true);
        if ($text === false) {
            return;
        }

        $this->sendSuggestedMessage($text);
    }

    public function sendSuggestedMessage(string $text): void
    {
        if ($this->isSending) {
            return;
        }

        $sanitized = trim($this->sanitizeUtf8String($text));

        if ($sanitized === '') {
            return;
        }

        $this->message = $sanitized;
        $this->sendMessage();
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

                $messages = $this->prepareMessages($conversationFresh, $trainerConfig);
                $options = [
                    'model' => $trainerConfig['model'] ?? null,
                    'temperature' => $trainerConfig['temperature'] ?? 0.7,
                ];

                $result = $this->ollamaClient->chat($messages, $options);
                $assistantText = $this->sanitizeUtf8String((string) ($result['text'] ?? ''));

                $toolResult = [
                    'content' => $assistantText,
                    'tool_results' => [],
                ];

                if ($trainerConfig['use_tools'] ?? false) {
                    $toolResult = $this->toolExecutor->parseAndExecuteTools($assistantText, $user);
                    $toolResult['content'] = $this->sanitizeUtf8String((string) $toolResult['content']);
                }

                $assistantPayload = $this->buildAssistantPayload($toolResult['content'], $toolResult['tool_results'] ?? []);

                $conversation->messages()->create([
                    'role' => AiConversationMessage::ROLE_ASSISTANT,
                    'content' => $assistantPayload['content'],
                    'metadata' => [
                        'buttons' => $assistantPayload['buttons'],
                        'ticket_url' => $assistantPayload['ticket_url'],
                    ],
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

    public function getHasTrainerChoiceProperty(): bool
    {
        return count($this->trainerOptions) > 1;
    }

    protected function ensureConversation(): void
    {
        if ($this->conversationId) {
            $this->loadConversation($this->conversationId);
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
        $metadataTrainer = trim((string) ($conversation->metadata['trainer'] ?? $this->trainer));

        if ($metadataTrainer !== '' && array_key_exists($metadataTrainer, $this->trainerOptions)) {
            $this->trainer = $metadataTrainer;
            $this->selectedTrainer = $metadataTrainer;
            $this->trainerMeta = $this->trainerOptions[$metadataTrainer];
        }

        $this->conversationId = $conversation->id;

        $this->messages = $conversation->messages
            ->sortBy('id')
            ->map(fn (AiConversationMessage $message) => $this->presentMessage($message))
            ->values()
            ->all();
    }

    protected function presentMessage(AiConversationMessage $message): array
    {
        $content = $this->sanitizeUtf8String((string) $message->content);
        $metadata = is_array($message->metadata) ? $message->metadata : [];
        $buttons = array_map(
            fn ($label) => $this->sanitizeUtf8String((string) $label),
            Arr::wrap($metadata['buttons'] ?? [])
        );
        $buttons = array_values(array_filter($buttons, fn ($label) => $label !== ''));

        $ticketUrl = null;
        if (! empty($metadata['ticket_url'])) {
            $ticketUrl = $this->buildTicketsUrl((string) $metadata['ticket_url']);
        }

        return [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $content,
            'buttons' => $buttons,
            'ticket_url' => $ticketUrl,
        ];
    }

    protected function prepareTrainerOptions(): array
    {
        $trainersConfig = config('ai.trainers', []);
        $options = [];

        foreach ($trainersConfig as $slug => $config) {
            $options[$slug] = [
                'name' => $this->sanitizeUtf8String((string) ($config['name'] ?? ucfirst($slug))),
                'description' => $this->sanitizeUtf8String((string) ($config['description'] ?? '')),
            ];
        }

        return $options;
    }

    protected function prepareSuggestionPresets(): array
    {
        $presets = [
            __('Voir mes prochains cours'),
            __('Comment rejoindre une application ?'),
            __('Quelles sont les nouveautes de la plateforme ?'),
        ];

        return array_map(fn ($value) => $this->sanitizeUtf8String((string) $value), $presets);
    }

    protected function trainerConfig(): array
    {
        $config = config("ai.trainers.{$this->trainer}", []);

        return is_array($config) ? $config : [];
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

        $user = $conversation->user;
        if ($user && method_exists($user, 'getIaContext')) {
            $userContext = trim((string) $user->getIaContext());
            if ($userContext !== '') {
                $messages[] = [
                    'role' => 'system',
                    'content' => $this->sanitizeUtf8String("Contexte utilisateur :\n" . $userContext),
                ];
            }
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

    protected function buildAssistantPayload(string $content, array $toolResults): array
    {
        $cleanContent = $content;

        $buttons = [];
        $buttonPattern = '/\[BUTTONS\](.*?)\[\/BUTTONS\]/is';
        if (preg_match($buttonPattern, $cleanContent, $match)) {
            $buttonsBlock = $match[1] ?? '';
            $lines = preg_split('/\r\n|\r|\n/', $buttonsBlock);
            foreach ($lines as $line) {
                $line = trim($line);
                if (Str::startsWith($line, '-')) {
                    $label = trim(ltrim($line, '-'));
                    if ($label !== '') {
                        $buttons[] = $this->sanitizeUtf8String(Str::limit($label, 100, ''));
                    }
                }
            }

            $cleanContent = trim(preg_replace($buttonPattern, '', $cleanContent));
        }

        $ticketUrl = $this->extractTicketUrlFromToolResults($toolResults);

        if ($ticketUrl && ! in_array($this->ticketButtonLabel, $buttons, true)) {
            $buttons[] = $this->ticketButtonLabel;
        }

        return [
            'content' => $this->sanitizeUtf8String($cleanContent),
            'buttons' => $buttons,
            'ticket_url' => $ticketUrl ? $this->buildTicketsUrl($ticketUrl) : null,
        ];
    }

    protected function extractTicketUrlFromToolResults(array $toolResults): ?string
    {
        foreach ($toolResults as $entry) {
            $result = $entry['result'] ?? null;
            if (! is_array($result)) {
                continue;
            }

            if (! empty($result['ticket_url'])) {
                return (string) $result['ticket_url'];
            }

            if (! empty($result['ticket']['ticket_url'])) {
                return (string) $result['ticket']['ticket_url'];
            }
        }

        return null;
    }

    public function buildTicketsUrl(?string $rawUrl): ?string
    {
        $rawUrl = $this->sanitizeUtf8String((string) $rawUrl);

        if ($rawUrl === '') {
            return null;
        }

        $baseUrl = $this->ticketsUrl ?: url('/mes-tickets');
        if (! str_starts_with($baseUrl, 'http')) {
            $baseUrl = url('/mes-tickets');
        }

        $ticketId = null;
        if (preg_match('/(\d+)(?!.*\d)/', $rawUrl, $matches)) {
            $ticketId = $matches[1] ?? null;
        }

        if ($ticketId) {
            return $baseUrl . '?ticket=' . $ticketId;
        }

        return $baseUrl;
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
