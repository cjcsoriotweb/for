<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use RuntimeException;

use function app;

class ChatCompletionClient
{
    private HttpFactory $http;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly string $chatEndpoint,
        private readonly bool $requiresApiKey = true,
        ?HttpFactory $http = null
    ) {
        if ($this->requiresApiKey && $this->apiKey === '') {
            throw new RuntimeException('AI provider API key is not configured.');
        }

        $this->http = $http ?: app(HttpFactory::class);
    }

    public static function fromConfig(array $config): self
    {
        $apiKey = Arr::get($config, 'api_key', '');
        $baseUrl = rtrim(Arr::get($config, 'base_url', 'http://localhost:11434/v1'), '/');
        $chatEndpoint = Arr::get($config, 'chat_endpoint', '/chat/completions');
        $requiresApiKey = (bool) Arr::get($config, 'requires_api_key', false);

        return new self($apiKey, $baseUrl, $chatEndpoint, $requiresApiKey);
    }

    /**
     * Send a chat completion request.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function chat(array $payload): array
    {
        $url = $this->baseUrl.'/'.ltrim($this->chatEndpoint, '/');

        $client = $this->http
            ->acceptJson()
            ->timeout(30);

        if ($this->apiKey !== '') {
            $client = $client->withToken($this->apiKey);
        }

        $response = $client->post($url, $payload);

        $response->throw();

        return $response->json();
    }
}
