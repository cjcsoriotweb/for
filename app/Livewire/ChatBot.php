<?php

namespace App\Livewire;

use App\Models\ChatWithBot;
use App\Models\ChatbotConversation;
use App\Models\ChatbotModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Throwable;

class ChatBot extends Component
{
    /**
     * @var array<int, array<string, string>>
     */
    public $messages = [];

    public ?int $conversationId = null;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $conversations = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $models = [];

    public ?int $selectedModelId = null;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $activeModel = null;

    public bool $selectingModel = false;

    public string $body = '';

    protected array $rules = [
        'body' => 'required|string|min:1|max:500',
    ];

    public function fetchMessage(): void
    {
        $userId = Auth::id();

        if (! $userId) {
            $this->messages = [];

            return;
        }

        if ($this->conversationId === null) {
            $this->messages = [];

            return;
        }

        $this->ensureActiveModelLoaded();

        $this->messages = ChatWithBot::query()
            ->where('user_id', $userId)
            ->where('chatbot_conversation_id', $this->conversationId)
            ->orderBy('created_at')
            ->get()
            ->map(function (ChatWithBot $message) {
                $message->sender = 'user';
                $message->time = optional($message->created_at)->format('H:i');
                $message->formatted_reply = $this->formatReply($message->reply);

                return $message;
            })
            ->all();
    }
    
    public function mount(): void
    {
        $this->fetchModels();
        $this->fetchConversations();
    }

    public function sendMessage(): void
    {
        $this->validate();

        $userId = Auth::id();

        if ($this->conversationId === null || ! $userId) {
            return;
        }

        $this->ensureActiveModelLoaded();

        ChatWithBot::create([
            'text' => $this->body,
            'user_id' => $userId,
            'see' => false,
            'conversation' => $this->conversationId,
            'chatbot_conversation_id' => $this->conversationId,
            'chatbot_model_id' => $this->selectedModelId,
        ]);

        ChatbotConversation::query()
            ->whereKey($this->conversationId)
            ->update(['updated_at' => now()]);

        $this->body = '';

        $this->fetchMessage();
        $this->fetchConversations();
        $this->dispatch('chat-scrolled');
    }

    public function render()
    {
        return view('livewire.chat-bot');
    }


    public function look($id): void
    {
        $userId = Auth::id();

        if (! $userId) {
            return;
        }

        $message = ChatWithBot::query()
            ->where('user_id', $userId)
            ->where('chatbot_conversation_id', $this->conversationId)
            ->whereKey($id)
            ->first();

        if (! $message || $message->reply) {
            return;
        }

        $message->reply = '';
        $message->save();

        $this->ensureActiveModelLoaded();

        try {
            $this->streamReplyFor($message);
        } catch (Throwable $exception) {
            $fallback = 'Erreur : '.$exception->getMessage();
            $message->reply = $fallback;
            $message->save();

            $this->streamChunk($message->id, $fallback, true);
        }

        $this->fetchMessage();
        $this->fetchConversations();
    }

    public function clearConversation(): void
    {
        $userId = Auth::id();

        if (! $userId || $this->conversationId === null) {
            return;
        }

        $conversation = ChatbotConversation::query()
            ->where('user_id', $userId)
            ->whereKey($this->conversationId)
            ->first();

        if ($conversation) {
            $conversation->delete();
        }

        $this->messages = [];
        $this->conversationId = null;
        $this->selectedModelId = null;
        $this->activeModel = null;
        $this->selectingModel = false;
        $this->fetchConversations();
        $this->dispatch('chat-scrolled');
        $this->body = '';
    }

    public function selectConversation(int $conversationId): void
    {
        $userId = Auth::id();

        if (! $userId) {
            return;
        }

        $conversation = ChatbotConversation::query()
            ->with('model')
            ->where('user_id', $userId)
            ->whereKey($conversationId)
            ->first();

        if (! $conversation) {
            return;
        }

        $this->applyActiveConversation($conversation);
        $this->fetchMessage();
        $this->dispatch('chat-scrolled');
    }

    public function startConversation(): void
    {
        if (! Auth::id()) {
            return;
        }

        $this->conversationId = null;
        $this->selectedModelId = null;
        $this->activeModel = null;
        $this->messages = [];
        $this->selectingModel = true;
        $this->body = '';
    }

    public function chooseModel(int $modelId): void
    {
        $userId = Auth::id();

        if (! $userId) {
            return;
        }

        $model = ChatbotModel::query()->find($modelId);

        if (! $model) {
            return;
        }

        $conversation = ChatbotConversation::create([
            'user_id' => $userId,
            'chatbot_model_id' => $model->id,
        ]);

        $conversation->setRelation('model', $model);

        $this->applyActiveConversation($conversation);
        $this->messages = [];
        $this->selectingModel = false;
        $this->fetchConversations();
        $this->dispatch('chat-scrolled');
        $this->body = '';
    }

    public function backToConversations(): void
    {
        $this->conversationId = null;
        $this->selectedModelId = null;
        $this->activeModel = null;
        $this->messages = [];
        $this->selectingModel = false;
        $this->fetchConversations();
        $this->body = '';
    }

    private function fetchConversations(): void
    {
        $userId = Auth::id();

        if (! $userId) {
            $this->conversations = [];

            return;
        }

        $this->conversations = ChatbotConversation::query()
            ->with(['model', 'messages' => function ($query) {
                $query->latest('created_at')->limit(1);
            }])
            ->withCount('messages')
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get()
            ->map(function (ChatbotConversation $conversation) {
                $latest = $conversation->messages->first();
                $previewSource = $latest ? ($latest->reply ?: $latest->text) : null;
                $model = $conversation->model;

                return [
                    'id' => $conversation->id,
                    'title' => $model?->name ?? 'Conversation '.$conversation->id,
                    'preview' => Str::limit($previewSource ?? 'Commencez cette conversation', 80),
                    'count' => $conversation->messages_count,
                    'updated_at' => optional($conversation->updated_at)->diffForHumans(),
                    'image' => $model?->image,
                ];
            })
            ->all();
    }

    private function fetchModels(): void
    {
        $this->models = ChatbotModel::query()
            ->orderBy('name')
            ->get()
            ->map(function (ChatbotModel $model) {
                return [
                    'id' => $model->id,
                    'key' => $model->key,
                    'name' => $model->name,
                    'image' => $model->image,
                    'description' => $model->description,
                ];
            })
            ->all();
    }

    private function applyActiveConversation(ChatbotConversation $conversation): void
    {
        $this->conversationId = $conversation->id;
        $this->selectedModelId = $conversation->chatbot_model_id;

        $this->activeModel = $conversation->model
            ? [
                'id' => $conversation->model->id,
                'key' => $conversation->model->key,
                'name' => $conversation->model->name,
                'image' => $conversation->model->image,
                'description' => $conversation->model->description,
            ]
            : null;

        $this->selectingModel = false;
    }

    private function ensureActiveModelLoaded(): void
    {
        if (! $this->conversationId || ($this->activeModel && $this->selectedModelId)) {
            return;
        }

        $conversation = ChatbotConversation::query()
            ->with('model')
            ->where('user_id', Auth::id())
            ->whereKey($this->conversationId)
            ->first();

        if ($conversation) {
            $this->applyActiveConversation($conversation);
        }
    }

    private function resolveModelKey(): string
    {
        if ($this->activeModel && isset($this->activeModel['key'])) {
            return (string) $this->activeModel['key'];
        }

        return config('services.chatbot.model', 'llama3:latest');
    }

    private function streamReplyFor(ChatWithBot $message): void
    {
        $accumulated = '';

        $this->requestReplyStream($message->text, function (string $chunk) use (&$accumulated, $message) {
            $accumulated .= $chunk;
            $this->streamChunk($message->id, $accumulated, true);
        });

        $message->reply = $accumulated;
        $message->save();
    }

    private function requestReplyStream(string $prompt, callable $onChunk): void
    {
        $endpoint = config('services.chatbot.endpoint', 'http://192.168.1.62:8000/api/chat/completions');
        $token = config('services.chatbot.token', '#####');
        $model = $this->resolveModelKey();

        $payload = [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'stream' => true,
        ];

        $rawResponse = '';
        $buffer = '';
        $receivedStream = false;
        $component = $this;

        $ch = curl_init($endpoint);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$token,
                'Content-Type: application/json',
                'Accept: text/event-stream',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$rawResponse, &$buffer, &$receivedStream, $onChunk, $component) {
                $rawResponse .= $chunk;
                $buffer .= str_replace("\r", '', $chunk);

                while (($position = strpos($buffer, "\n\n")) !== false) {
                    $packet = substr($buffer, 0, $position);
                    $buffer = substr($buffer, $position + 2);

                    if ($component->emitStreamPacket($packet, $onChunk)) {
                        $receivedStream = true;
                    }
                }

                return strlen($chunk);
            },
        ]);

        $result = curl_exec($ch);

        if ($buffer !== '' && $this->emitStreamPacket($buffer, $onChunk)) {
            $receivedStream = true;
        }

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new \RuntimeException('Erreur cURL : '.$error);
        }

        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if (! $receivedStream && $rawResponse !== '') {
            $content = $this->extractContentFromRawResponse($rawResponse);

            if ($content) {
                $onChunk($content);
                $receivedStream = true;
            }
        }

        if ($status >= 400 || ! $receivedStream) {
            throw new \RuntimeException('Réponse du bot indisponible (code '.$status.')');
        }
    }

    private function streamChunk(int $messageId, string $chunk, bool $replace = false): void
    {
        $this->stream('reply-'.$messageId, $this->formatReply($chunk), $replace);
    }

    private function emitStreamPacket(string $packet, callable $onChunk): bool
    {
        $emitted = false;

        foreach (explode("\n", $packet) as $line) {
            $line = trim($line);

            if ($line === '' || $line === 'data: [DONE]' || $line === '[DONE]') {
                continue;
            }

            if (str_starts_with($line, 'data:')) {
                $line = ltrim(substr($line, 5));
            }

            if ($line === '' || $line === '[DONE]') {
                continue;
            }

            $payload = json_decode($line, true);

            if (! is_array($payload)) {
                continue;
            }

            $chunk = $this->extractChunkContent($payload);

            if ($chunk === null || $chunk === '') {
                continue;
            }

            $onChunk($chunk);
            $emitted = true;
        }

        return $emitted;
    }

    private function extractContentFromRawResponse(string $rawResponse): ?string
    {
        $lines = preg_split("/\r?\n/", trim($rawResponse));

        if (! $lines) {
            return null;
        }

        for ($index = count($lines) - 1; $index >= 0; $index--) {
            $line = trim($lines[$index]);

            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, 'data:')) {
                $line = ltrim(substr($line, 5));
            }

            if ($line === '' || $line === '[DONE]') {
                continue;
            }

            $decoded = json_decode($line, true);

            if (! is_array($decoded)) {
                continue;
            }

            $content = $this->extractChunkContent($decoded);

            if ($content) {
                return $content;
            }
        }

        return null;
    }

    private function extractChunkContent(array $payload): ?string
    {
        $candidates = [
            data_get($payload, 'choices.0.delta.content'),
            data_get($payload, 'choices.0.text'),
            data_get($payload, 'choices.0.message.content'),
            data_get($payload, 'message.content'),
            data_get($payload, 'response'),
            data_get($payload, 'content'),
        ];

        foreach ($candidates as $candidate) {
            $chunk = $this->stringifyChunk($candidate);

            if ($chunk !== null && $chunk !== '') {
                return $chunk;
            }
        }

        return null;
    }

    private function stringifyChunk(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            $result = '';

            array_walk_recursive($value, function ($piece) use (&$result) {
                if (is_string($piece)) {
                    $result .= $piece;
                }
            });

            return $result;
        }

        return null;
    }

    private function formatReply(?string $content): string
    {
        if (! $content) {
            return '';
        }

        return trim(Str::markdown($content, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]));
    }
}
