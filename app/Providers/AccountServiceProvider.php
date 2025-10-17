<?php

namespace App\Providers;

use App\Services\Clean\Account\AccountService;
use App\Services\Clean\Account\OrganisationService;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AccountService::class, function ($app) {
            return new AccountService(
                $app->make(OrganisationService::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
