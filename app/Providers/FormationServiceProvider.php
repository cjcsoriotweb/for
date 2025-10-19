<?php

namespace App\Providers;

use App\Services\Formation\AdminFormationService;
use App\Services\Formation\ChapterFormationService;
use App\Services\Formation\StudentFormationService;
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
            return new FormationService(
                $app->make(AdminFormationService::class),
                $app->make(StudentFormationService::class),
                $app->make(ChapterFormationService::class),
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
