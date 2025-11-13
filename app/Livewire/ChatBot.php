<?php

namespace App\Livewire;

use App\Models\ChatWithBot;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Throwable;

class ChatBot extends Component
{
    /**
     * @var array<int, array<string, string>>
     */
    public $messages = [];

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

        $this->messages = ChatWithBot::query()
            ->where('user_id', $userId)
            ->orderBy('created_at')
            ->get()
            ->map(function (ChatWithBot $message) {
                $message->sender = 'user';
                $message->time = optional($message->created_at)->format('H:i');

                return $message;
            })
            ->all();
    }
    
    public function mount(): void
    {
       $this->fetchMessage();
    }

    public function sendMessage(): void
    {
        $this->validate();

        ChatWithBot::create([
            'text' => $this->body,
            'user_id' => Auth::id(),
            'see' => false,
        ]);

        $this->body = '';

        $this->fetchMessage();
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
            ->whereKey($id)
            ->first();

        if (! $message || $message->reply) {
            return;
        }

        $message->reply = '';
        $message->save();

        try {
            $this->streamReplyFor($message);
        } catch (Throwable $exception) {
            $fallback = 'Erreur : '.$exception->getMessage();
            $message->reply = $fallback;
            $message->save();

            $this->streamChunk($message->id, $fallback, true);
        }

        $this->fetchMessage();
    }

    public function clearConversation(): void
    {
        $userId = Auth::id();

        if (! $userId) {
            $this->messages = [];

            return;
        }

        ChatWithBot::query()
            ->where('user_id', $userId)
            ->delete();

        $this->messages = [];
        $this->dispatch('chat-scrolled');
    }

    private function streamReplyFor(ChatWithBot $message): void
    {
        $accumulated = '';
        $isFirstChunk = true;

        $this->requestReplyStream($message->text, function (string $chunk) use (&$accumulated, &$isFirstChunk, $message) {
            $accumulated .= $chunk;
            $this->streamChunk($message->id, $chunk, $isFirstChunk);
            $isFirstChunk = false;
        });

        $message->reply = $accumulated;
        $message->save();
    }

    private function requestReplyStream(string $prompt, callable $onChunk): void
    {
        $endpoint = config('services.chatbot.endpoint', 'http://192.168.1.62:8000/api/chat/completions');
        $token = config('services.chatbot.token', 'sk-caf6eaff4e514f47bf7dae014a37375d');
        $model = config('services.chatbot.model', 'llama3:latest');

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
        $this->stream('reply-'.$messageId, $this->escapeForStream($chunk), $replace);
    }

    private function escapeForStream(string $chunk): string
    {
        return nl2br(e($chunk));
    }
}
