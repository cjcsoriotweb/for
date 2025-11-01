<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Client HTTP unique pour communiquer avec Ollama.
 * Gère les requêtes de chat completion avec streaming optionnel.
 */
class OllamaClient
{
    private HttpFactory $http;

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $defaultModel,
        private readonly int $timeout = 60,
        ?HttpFactory $http = null
    ) {
        $this->http = $http ?: app(HttpFactory::class);
    }

    /**
     * Envoie une requête de chat completion.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     *
     * @throws RequestException|RuntimeException
     */
    public function chat(array $messages, array $options = []): array
    {
        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (float) $temperature,
            'stream' => false,
        ];

        $url = rtrim($this->baseUrl, '/') . '/v1/chat/completions';

        $startTime = microtime(true);

        try {
            $response = $this->http
                ->acceptJson()
                ->timeout($this->timeout)
                ->post($url, $payload);

            $response->throw();

            $data = $response->json();
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            // Log en dev seulement
            if (app()->environment('local', 'development')) {
                Log::info('Ollama chat completion', [
                    'model' => $model,
                    'duration_ms' => $duration,
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? null,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? null,
                ]);
            }

            return $data;
        } catch (RequestException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if (app()->environment('local', 'development')) {
                Log::error('Ollama chat completion failed', [
                    'model' => $model,
                    'duration_ms' => $duration,
                    'error' => $e->getMessage(),
                ]);
            }

            throw $e;
        }
    }

    /**
     * Envoie une requête de chat completion avec streaming.
     * Retourne un générateur qui yield chaque chunk de réponse.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array<string, mixed>  $options
     * @return \Generator<string>
     *
     * @throws RequestException|RuntimeException
     */
    public function chatStream(array $messages, array $options = []): \Generator
    {
        $model = $options['model'] ?? $this->defaultModel;
        $temperature = $options['temperature'] ?? 0.7;

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => (float) $temperature,
            'stream' => true,
        ];

        $url = rtrim($this->baseUrl, '/') . '/v1/chat/completions';

        $startTime = microtime(true);

        try {
            $response = $this->http
                ->timeout($this->timeout)
                ->withHeaders(['Accept' => 'text/event-stream'])
                ->post($url, $payload);

            if (!$response->successful()) {
                throw new RuntimeException('Ollama streaming request failed: ' . $response->body());
            }

            $body = $response->body();
            $lines = explode("\n", $body);

            foreach ($lines as $line) {
                $line = trim($line);
                
                if ($line === '' || $line === 'data: [DONE]') {
                    continue;
                }

                if (str_starts_with($line, 'data: ')) {
                    $jsonData = substr($line, 6);
                    $data = json_decode($jsonData, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        $content = $data['choices'][0]['delta']['content'] ?? null;
                        if ($content !== null && $content !== '') {
                            yield $content;
                        }
                    }
                }
            }

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if (app()->environment('local', 'development')) {
                Log::info('Ollama chat stream completed', [
                    'model' => $model,
                    'duration_ms' => $duration,
                ]);
            }
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if (app()->environment('local', 'development')) {
                Log::error('Ollama chat stream failed', [
                    'model' => $model,
                    'duration_ms' => $duration,
                    'error' => $e->getMessage(),
                ]);
            }

            throw $e;
        }
    }
}
