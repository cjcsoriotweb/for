<?php

namespace App\Providers;

use App\Models\Claim;
use App\Policies\ClaimPolicy;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
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
        $anonymousComponents = [
            'button' => 'components.ui.buttons.button',
            'primary-button' => 'components.ui.buttons.primary-button',
            'secondary-button' => 'components.ui.buttons.secondary-button',
            'danger-button' => 'components.ui.buttons.danger-button',
            'button-block' => 'components.ui.buttons.button-block',
            'big-button' => 'components.ui.buttons.big-button',
            'input' => 'components.ui.forms.input',
            'input-error' => 'components.ui.forms.input-error',
            'label' => 'components.ui.forms.label',
            'checkbox' => 'components.checkbox',
            'form-section' => 'components.ui.forms.form-section',
            'validation-errors' => 'components.ui.forms.validation-errors',
            'action-section' => 'components.ui.surfaces.action-section',
            'block-div' => 'components.ui.surfaces.block-div',
            'section-border' => 'components.ui.surfaces.section-border',
            'section-title' => 'components.ui.surfaces.section-title',
            'banner' => 'components.ui.surfaces.banner',
            'action-message' => 'components.ui.feedback.action-message',
            'auth-debug-panel' => 'components.ui.feedback.auth-debug-panel',
            'error-display' => 'components.ui.feedback.error-display',
            'debug' => 'components.ui.feedback.debug',
            'block-navigation' => 'components.ui.navigation.block-navigation',
            'nav-link' => 'components.ui.navigation.nav-link',
            'responsive-nav-link' => 'components.ui.navigation.responsive-nav-link',
            'out-app-navigation-menu' => 'components.ui.navigation.out-app-navigation-menu',
            'team-navigation-menu' => 'components.ui.navigation.team-navigation-menu',
            'switchable-team' => 'components.ui.navigation.switchable-team',
            'deconnexion' => 'components.ui.navigation.deconnexion',
            'dropdown' => 'components.ui.navigation.dropdown',
            'dropdown-link' => 'components.ui.navigation.dropdown-link',
            'modal' => 'components.ui.modals.modal',
            'dialog-modal' => 'components.ui.modals.dialog-modal',
            'confirmation-modal' => 'components.ui.modals.confirmation-modal',
            'confirms-password' => 'components.ui.modals.confirms-password',
            'application-logo' => 'components.ui.logo.application-logo',
            'application-mark' => 'components.ui.logo.application-mark',
            'authentication-card-logo' => 'components.ui.logo.authentication-card-logo',
            'formation-card' => 'components.ui.cards.formation-card',
            'admin-money-team' => 'components.ui.cards.admin-money-team',
            'select-plateform' => 'components.ui.cards.select-plateform',
            'welcome' => 'components.ui.cards.welcome',
            'authentication-card' => 'components.ui.auth.authentication-card',
            'auth.form-page' => 'components.ui.auth.form-page',
            'admin.layout' => 'components.admin.layouts.app',
            'admin.global-layout' => 'components.admin.layouts.global',
            'eleve.notification-messages' => 'components.eleve.dashboard.notification-messages',
            'eleve.hello' => 'components.eleve.dashboard.hello',
            'eleve.formation-actions' => 'components.eleve.formation.actions',
            'eleve.formation-timeline' => 'components.eleve.formation.timeline',
            'eleve.formation-choice' => 'components.eleve.formation.choice',
            'eleve.formation-continue' => 'components.eleve.formation.continue',
            'eleve.formation-header' => 'components.eleve.formation.header',

            'header' => 'components.app.layout.header',

            'layout.header' => 'components.app.layout.header',
            'organisateur.layout' => 'components.organisateur.layouts.app',
        ];

        foreach ($anonymousComponents as $alias => $view) {
            Blade::component($view, $alias);
        }

        Blade::anonymousComponentNamespace('components.ui', 'ui');

        // Register policies
        Gate::policy(Claim::class, ClaimPolicy::class);

        // Register Blade component aliases for class-based components
        Blade::component('organisateur.parts.breadcrumb', \App\View\Components\Organisateur\Parts\Breadcrumb::class);
        Blade::component('organisateur.parts.stats-cards', \App\View\Components\Organisateur\Parts\StatsCards::class);
        Blade::component('organisateur.parts.filters', \App\View\Components\Organisateur\Parts\Filters::class);
        Blade::component('organisateur.parts.student-card', \App\View\Components\Organisateur\Parts\StudentCard::class);
        Blade::component('organisateur.parts.formation-card', \App\View\Components\Organisateur\Parts\FormationCard::class);
        Blade::component('organisateur.parts.formation-catalogue-card', \App\View\Components\Organisateur\Parts\FormationCatalogueCard::class);
        Blade::component('organisateur.parts.empty-state', \App\View\Components\Organisateur\Parts\EmptyState::class);
        Blade::component('organisateur.parts.action-buttons', \App\View\Components\Organisateur\Parts\ActionButtons::class);
    }
}
