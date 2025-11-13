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
        $token = config('services.chatbot.token', 'sk-caf6eaff4e514f47bf7dae014a37375d');
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
            CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$rawResponse, &$buffer, &$receivedStream, $onChunk) {
                $rawResponse .= $chunk;
                $buffer .= str_replace("\r", '', $chunk);

                while (($position = strpos($buffer, "\n\n")) !== false) {
                    $packet = substr($buffer, 0, $position);
                    $buffer = substr($buffer, $position + 2);

                    foreach (explode("\n", $packet) as $line) {
                        $line = trim($line);

                        if ($line === '' || $line === 'data: [DONE]') {
                            continue;
                        }

                        if (str_starts_with($line, 'data: ')) {
                            $payload = json_decode(substr($line, 6), true);
                            $delta = data_get($payload, 'choices.0.delta.content');

                            if ($delta) {
                                $receivedStream = true;
                                $onChunk($delta);
                            }
                        }
                    }
                }

                return strlen($chunk);
            },
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new \RuntimeException('Erreur cURL : '.$error);
        }

        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if (! $receivedStream && $rawResponse !== '') {
            $decoded = json_decode($rawResponse, true);
            $content = data_get($decoded, 'choices.0.message.content');

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
