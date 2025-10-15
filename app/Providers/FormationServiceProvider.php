<?php

namespace App\Providers;

use App\Services\FormationService;
use Illuminate\Support\ServiceProvider;

class FormationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FormationService::class, function ($app) {
            return new FormationService();
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
