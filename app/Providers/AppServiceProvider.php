<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->alias('PDF', \Barryvdh\DomPDF\Facade\Pdf::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade component aliases
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.breadcrumb', \App\View\Components\Organisateur\Parts\Breadcrumb::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.stats-cards', \App\View\Components\Organisateur\Parts\StatsCards::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.filters', \App\View\Components\Organisateur\Parts\Filters::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.student-card', \App\View\Components\Organisateur\Parts\StudentCard::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.formation-card', \App\View\Components\Organisateur\Parts\FormationCard::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.formation-catalogue-card', \App\View\Components\Organisateur\Parts\FormationCatalogueCard::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.empty-state', \App\View\Components\Organisateur\Parts\EmptyState::class);
        \Illuminate\Support\Facades\Blade::component('organisateur.parts.action-buttons', \App\View\Components\Organisateur\Parts\ActionButtons::class);
    }
}
