<?php

namespace App\Providers;

use App\Services\Clean\Account\AccountService;
use App\Services\Clean\Account\TeamService;
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
                $app->make(TeamService::class),
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
