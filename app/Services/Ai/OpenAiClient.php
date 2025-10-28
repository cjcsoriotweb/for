<?php

namespace App\Services\Ai;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use RuntimeException;
use function app;

class OpenAiClient
{
    private HttpFactory $http;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly string $chatEndpoint,
        ?HttpFactory $http = null
    ) {
        if ($this->apiKey === '') {
            throw new RuntimeException('OPENAI_API_KEY is not configured.');
        }

        $this->http = $http ?: app(HttpFactory::class);
    }

    public static function fromConfig(array $config): self
    {
        $apiKey = Arr::get($config, 'api_key', '');
        $baseUrl = rtrim(Arr::get($config, 'base_url', 'https://api.openai.com/v1'), '/');
        $chatEndpoint = Arr::get($config, 'chat_endpoint', '/chat/completions');

        return new self($apiKey, $baseUrl, $chatEndpoint);
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

        $response = $this->http
            ->withToken($this->apiKey)
            ->acceptJson()
            ->timeout(30)
            ->post($url, $payload);

        $response->throw();

        return $response->json();
    }
}
