<?php

namespace App\Providers;

use App\Services\Ai\OllamaClient;
use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OllamaClient::class, function ($app) {
            return new OllamaClient(
                baseUrl: config('ai.base_url'),
                defaultModel: config('ai.default_model'),
                timeout: config('ai.timeout', 60)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
