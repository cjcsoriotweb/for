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
            'description' => __('Recherche avancée sur les formations'),
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

    $quickStats = [
        [
            'label' => __('Equipes'),
            'value' => number_format($stats->get('teams', 0)),
            'description' => __('Structures actives'),
            'icon' => 'groups',
            'route' => route('superadmin.teams.index'),
        ],
        [
            'label' => __('Utilisateurs'),
            'value' => number_format($stats->get('users', 0)),
            'description' => __('Comptes validés'),
            'icon' => 'badge',
            'route' => route('superadmin.users.index'),
        ],
        [
            'label' => __('Formations'),
            'value' => number_format($stats->get('formations', 0)),
            'description' => __('Catalogues disponibles'),
            'icon' => 'school',
            'route' => route('superadmin.formations.index'),
        ],
        [
            'label' => __('Formations en attente'),
            'value' => number_format($stats->get('completion_requests_pending', 0)),
            'description' => __('Demandes à valider'),
            'icon' => 'check_circle',
            'route' => route('superadmin.completion-requests.index'),
        ],
        [
            'label' => __('Tickets en attente de résolution'),
            'value' => number_format($stats->get('tickets_pending_resolution', 0)),
            'description' => __('Demandes support à traiter'),
            'icon' => 'support_agent',
            'route' => route('superadmin.support.index'),
        ],
    ];
@endphp

<x-admin.global-layout
    icon="dashboard"
    :title="__('Espace Super-Admin')"
    :subtitle="__('')"
>

        <section class="space-y-8">
            <div class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Statistiques rapides') }}
                    </h2>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($quickStats as $stat)
                        <a href="{{ $stat['route'] }}"
                            class="group rounded-2xl border border-slate-100 bg-white/80 p-5 shadow-sm ring-1 ring-black/5 backdrop-blur transition hover:-translate-y-0.5 hover:border-indigo-400 hover:bg-indigo-50/70 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 dark:border-slate-800 dark:bg-slate-900/60 dark:hover:border-indigo-500 dark:hover:bg-slate-900/70">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 transition group-hover:text-slate-900 dark:text-slate-400 dark:group-hover:text-white">
                                    {{ $stat['label'] }}
                                </span>
                                <span class="material-symbols-outlined text-base text-slate-500 transition group-hover:text-slate-900 dark:text-slate-400 dark:group-hover:text-white">
                                    {{ $stat['icon'] }}
                                </span>
                            </div>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                                {{ $stat['value'] }}
                            </p>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ $stat['description'] }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Modules et accès rapides') }}
                    </h2>
                </div>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($modules as $module)
                        <a href="{{ $module['route'] }}"
                            class="group flex flex-col gap-3 rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-lg ring-1 ring-black/5 transition hover:-translate-y-1 hover:border-indigo-400 hover:bg-indigo-50/80 dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-indigo-500 dark:hover:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $module['title'] }}
                                </span>
                                <span class="material-symbols-outlined text-xl text-slate-400 dark:text-slate-500">
                                    {{ $module['icon'] }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $module['description'] }}
                            </p>
                            @if (!empty($module['stat']))
                                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-300">
                                    {{ $module['stat'] }}
                                </p>
                            @endif
                            <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500 transition group-hover:text-indigo-600 dark:text-indigo-400">
                                {{ __('Explorer') }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

        </section>

</x-admin.global-layout>
