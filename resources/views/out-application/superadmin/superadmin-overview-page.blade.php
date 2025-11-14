@php
    $stats = collect($stats);

    $modules = [
        [
            'title' => __('Equipes'),
            'description' => __('Pilotez toutes les organisations et leurs acces.'),
            'stat' => '',
            'icon' => 'groups_2',
            'route' => route('superadmin.teams.index'),
        ],
        [
            'title' => __('Utilisateurs'),
            'description' => __('Consultez les comptes et leurs droits.'),
            'stat' => '',
            'icon' => 'badge',
            'route' => route('superadmin.users.index'),
        ],
        [
            'title' => __('Valider fin de formation'),
            'description' => __('Validez les demandes de fin de formation.'),
            'stat' => number_format($stats->get('completion_requests_pending', 0)),
            'icon' => 'check_circle',
            'route' => route('superadmin.completion-requests.index'),
        ],
        [
            'title' => __('Support client'),
            'description' => __('Centre support et batterie de tests.'),
            'stat' => number_format($stats->get('tickets', 0)),
            'icon' => 'support_agent',
            'route' => route('superadmin.support.index'),
        ],
        [
            'title' => __('Suivis des formations'),
            'description' => __('Recherche avancé sur les formations'),
            'stat' => '',
            'icon' => 'book',
            'route' => route('superadmin.compta.index'),
        ],

        [
            'title' => __('Assistant IA'),
            'description' => __('Dialogue et recherches IA dans un iframe sécurisé.'),
            'stat' => '',
            'icon' => 'smart_toy',
            'route' => route('superadmin.assistant'),
        ],
        [
            'title' => __('Console debug'),
            'description' => __('Exécutez des commandes Artisan supervisées.'),
            'stat' => '',
            'icon' => 'terminal',
            'route' => route('superadmin.console'),
        ],
        [
            'title' => __('Base de données'),
            'description' => __('PhpMyAdmin et sauvegardes en un clic.'),
            'stat' => '',
            'icon' => 'dns',
            'route' => route('superadmin.db'),
        ],
    ];
@endphp

<x-admin.global-layout
    icon="dashboard"
    :title="__('Espace Super-Admin')"
    :subtitle="__('')"
>

        <section class="space-y-8">
            <div class="rounded-3xl border border-slate-100 bg-white/90 p-8 shadow-lg ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/80">
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    {{ __('Utilisez le menu principal à gauche pour lancer l’assistant, la console ou la vue base de données.') }}
                </p>
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Aucune vignette n’est affichée côté contenu pour garder cette page dégagée.') }}
                </p>
            </div>
        </section>

</x-admin.global-layout>
