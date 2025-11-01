<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Client HTTP pour Ollama (natif par défaut) + compat OpenAI optionnelle.
 */
class OllamaClient
{
    private HttpFactory $http;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $defaultModel,
        private readonly int $timeout = 60,
        private readonly bool $useOpenAICompat = false, // false = /api/chat ; true = /v1/chat/completions
        ?HttpFactory $http = null
    ) {
        $this->http = $http ?: app(HttpFactory::class);
    }

    private function chatUrl(): string
    {
        $path = '/api/chat';
        return rtrim($this->baseUrl, '/') . $path;
    }

    /**
     * Corps de requête selon mode (natif vs compat).
     *
     * @param  array<int, array{role:string, content:string}> $messages
     * @return array<string,mixed>
     */
    private function buildPayload(array $messages, float $temperature, bool $stream): array
    {
        if ($this->useOpenAICompat) {
            // OpenAI-compat
            return [
                'model' => $this->defaultModel,
                'messages' => $messages,
                'temperature' => $temperature,
                'stream' => $stream,
            ];
        }

        // Ollama natif
        return [
            'model' => $this->defaultModel,
            'messages' => $messages,
            'options' => [
                'temperature' => $temperature,
            ],
            'stream' => $stream,
        ];
    }

    /**
     * Chat (non stream).
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     *
     * @throws RequestException|RuntimeException
     */
    public function chat(array $messages, array $options = []): array
    {
        $model = (string)($options['model'] ?? $this->defaultModel);
        $temperature = (float)($options['temperature'] ?? 0.7);

        $payload = $this->buildPayload($messages, $temperature, false);
        // S’assure que le modèle demandé (optionnel) prime
        $payload['model'] = $model;

        $url = $this->chatUrl();
        $startTime = microtime(true);

        try {
            $response = $this->http
                ->acceptJson()
                ->timeout($this->timeout)
                ->post($url, $payload);

            $response->throw();
            $data = $response->json();

            // Normalisation minimale du résultat
            $normalized = $this->useOpenAICompat
                ? [
                    'text' => $data['choices'][0]['message']['content'] ?? '',
                    'usage' => $data['usage'] ?? null,
                    'raw' => $data,
                ]
                : [
                    'text' => $data['message']['content'] ?? ($data['response'] ?? ''),
                    'raw' => $data,
                ];

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            if (app()->environment('local', 'development')) {
                Log::info('Ollama chat completion', [
                    'compat' => $this->useOpenAICompat,
                    'model' => $model,
                    'duration_ms' => $duration,
                    'prompt_tokens' => $normalized['usage']['prompt_tokens'] ?? null,
                    'completion_tokens' => $normalized['usage']['completion_tokens'] ?? null,
                ]);
            }

            return $normalized;
        } catch (RequestException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            if (app()->environment('local', 'development')) {
                Log::error('Ollama chat completion failed', [
                    'compat' => $this->useOpenAICompat,
                    'url' => $url,
                    'model' => $model,
                    'duration_ms' => $duration,
                    'error' => $e->getMessage(),
                    'body' => $e->response?->body(),
                ]);
            }
            throw $e;
        }
    }

    /**
     * Chat streaming — renvoie un générateur de chunks (texte).
     * NB: pour un vrai streaming HTTP chunked vers le client, utilise chatStreamRaw()
     * dans une StreamedResponse.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array<string, mixed>  $options
     * @return \Generator<string>
     *
     * @throws RequestException|RuntimeException
     */
    public function chatStream(array $messages, array $options = []): \Generator
    {
        $model = (string)($options['model'] ?? $this->defaultModel);
        $temperature = (float)($options['temperature'] ?? 0.7);

        $payload = $this->buildPayload($messages, $temperature, true);
        $payload['model'] = $model;

        $url = $this->chatUrl();
        $startTime = microtime(true);

        try {
            // ATTENTION: Le client HTTP Laravel télécharge d’abord toute la réponse.
            // On parse donc le body complet ici (utile pour tests/dev), mais
            // pour du vrai flux vers le navigateur, préférer chatStreamRaw().
            $response = $this->http
                ->timeout($this->timeout)
                ->withHeaders([
                    'Accept' => $this->useOpenAICompat ? 'text/event-stream' : 'application/json',
                ])
                ->post($url, $payload);

            if (!$response->successful()) {
                throw new RuntimeException('Ollama streaming request failed: ' . $response->body());
            }

            $body = $response->body();

            if ($this->useOpenAICompat) {
                // OpenAI-compat: SSE "data: {json}" lignes + "data: [DONE]"
                foreach (explode("\n", $body) as $line) {
                    $line = trim($line);
                    if ($line === '' || $line === 'data: [DONE]') {
                        continue;
                    }
                    if (str_starts_with($line, 'data: ')) {
                        $json = substr($line, 6);
                        $data = json_decode($json, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $delta = $data['choices'][0]['delta']['content'] ?? null;
                            if ($delta !== null && $delta !== '') {
                                yield $delta;
                            }
                        }
                    }
                }
            } else {
                // Ollama natif: JSONL (une ligne JSON par chunk) jusqu’à {"done":true}
                foreach (preg_split('/\r\n|\r|\n/', $body) as $line) {
                    $line = trim($line);
                    if ($line === '') continue;

                    $data = json_decode($line, true);
                    if (json_last_error() !== JSON_ERROR_NONE) continue;

                    if (!empty($data['done'])) {
                        break;
                    }

                    // Deux formats possibles selon versions:
                    // - {"message":{"content":"..."}}
                    // - {"response":"..."}
                    $chunk = $data['message']['content'] ?? ($data['response'] ?? null);
                    if ($chunk !== null && $chunk !== '') {
                        yield $chunk;
                    }
                }
            }

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            if (app()->environment('local', 'development')) {
                Log::info('Ollama chat stream completed', [
                    'compat' => $this->useOpenAICompat,
                    'model' => $model,
                    'duration_ms' => $duration,
                ]);
            }
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            if (app()->environment('local', 'development')) {
                Log::error('Ollama chat stream failed', [
                    'compat' => $this->useOpenAICompat,
                    'url' => $url,
                    'model' => $model,
                    'duration_ms' => $duration,
                    'error' => $e->getMessage(),
                ]);
            }
            throw $e;
        }
    }

    /**
     * Streaming brut via cURL — à brancher dans une StreamedResponse pour pousser les chunks
     * directement au client sans bufferiser.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array<string, mixed>  $options
     * @return resource
     */
    public function chatStreamRaw(array $messages, array $options = [])
    {
        $model = (string)($options['model'] ?? $this->defaultModel);
        $temperature = (float)($options['temperature'] ?? 0.7);

        $payload = $this->buildPayload($messages, $temperature, true);
        $payload['model'] = $model;

        $url = $this->chatUrl();

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            $this->useOpenAICompat ? 'Accept: text/event-stream' : 'Accept: application/json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        // IMPORTANT: ne pas définir WRITEFUNCTION ici, laisse la StreamedResponse
        // relayer stdout du handle cURL directement.
        return $ch;
    }
}
