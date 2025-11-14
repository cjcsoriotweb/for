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
        ],
        [
            'label' => __('Utilisateurs'),
            'value' => number_format($stats->get('users', 0)),
            'description' => __('Comptes validés'),
            'icon' => 'badge',
        ],
        [
            'label' => __('Formations'),
            'value' => number_format($stats->get('formations', 0)),
            'description' => __('Catalogues disponibles'),
            'icon' => 'school',
        ],
        [
            'label' => __('Tickets en cours'),
            'value' => number_format($stats->get('tickets', 0)),
            'description' => __('Suivi support'),
            'icon' => 'headset_mic',
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
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            {{ __('Bureau central') }}
                        </p>
                        <div>
                            <h1 class="text-2xl font-semibold leading-tight text-slate-900 dark:text-white">
                                {{ __('Vue d’ensemble Super-Admin') }}
                            </h1>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Suivez les métriques clés, apportez un soutien immédiat et basculez vers un module en un clic.') }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3 pt-1">
                            <a href="{{ route('superadmin.support.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100">
                                {{ __('Ouvrir le support') }}
                            </a>
                            <a href="{{ route('superadmin.completion-requests.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-400 hover:text-slate-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-400 dark:border-slate-700 dark:text-slate-300 dark:hover:border-slate-500 dark:hover:text-white">
                                {{ __('Valider les formations') }}
                            </a>
                        </div>
                    </div>
                    <div class="flex-1 rounded-2xl bg-slate-900/95 p-5 text-white shadow-lg shadow-slate-900/20 dark:bg-gradient-to-br dark:from-indigo-900 dark:to-slate-900">
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-200">
                            {{ __('Aperçu instantané') }}
                        </p>
                        <p class="mt-2 text-3xl font-semibold">
                            {{ number_format($stats->get('teams', 0)) }}
                        </p>
                        <p class="text-sm text-slate-300">
                            {{ __('Equipes suivies en temps réel') }}
                        </p>
                        <div class="mt-4 flex gap-4 text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-300">
                            <span>{{ __('Tickets ouverts') }} : {{ number_format($stats->get('tickets', 0)) }}</span>
                            <span>{{ __('Demandes de validation') }} : {{ number_format($stats->get('completion_requests_pending', 0)) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Statistiques rapides') }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Chiffres à jour au dernier refresh') }}
                    </p>
                </div>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($quickStats as $stat)
                        <div class="rounded-2xl border border-slate-100 bg-white/80 p-5 shadow-sm ring-1 ring-black/5 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    {{ $stat['label'] }}
                                </span>
                                <span class="text-slate-500 dark:text-slate-400">
                                    <span class="material-symbols-rounded text-base">
                                        {{ $stat['icon'] }}
                                    </span>
                                </span>
                            </div>
                            <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                                {{ $stat['value'] }}
                            </p>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ $stat['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Modules et accès rapides') }}
                    </h2>
                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Cliquez pour ouvrir') }}
                    </span>
                </div>
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($modules as $module)
                        <a href="{{ $module['route'] }}"
                            class="group flex flex-col gap-3 rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-lg ring-1 ring-black/5 transition hover:-translate-y-1 hover:border-indigo-400 hover:bg-indigo-50/80 dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-indigo-500 dark:hover:bg-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $module['title'] }}
                                </span>
                                <span class="material-symbols-rounded text-xl text-slate-400 dark:text-slate-500">
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

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-lg ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/70">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                            {{ __('Focus support & conformité') }}
                        </h3>
                        <span class="text-sm uppercase tracking-wide text-slate-400 dark:text-slate-500">
                            {{ __('Priorité') }}
                        </span>
                    </div>
                    <ul class="mt-4 space-y-4 text-sm text-slate-600 dark:text-slate-400">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            {{ __('Suivez les tickets clients ouverts et relancez les équipes pédagogiques concernées.') }}
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                            {{ __('Validez ou refusez les demandes de fin de formation en attente avant la fin du mois.') }}
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-sky-500"></span>
                            {{ __('Gardez la base de données à jour en vérifiant les dernières sauvegardes avant chaque opération.') }}
                        </li>
                    </ul>
                </div>
                <div class="rounded-3xl border border-slate-100 bg-gradient-to-br from-indigo-900 to-slate-900 p-6 shadow-lg ring-1 ring-black/30 text-white">
                    <p class="text-sm font-semibold uppercase tracking-wide text-indigo-200">
                        {{ __('Performance opérationnelle') }}
                    </p>
                    <p class="mt-2 text-xl font-semibold leading-tight">
                        {{ __('Alertez, déléguez et automatisez avec l’assistant IA et la console dédiée.') }}
                    </p>
                    <div class="mt-4 space-y-3 text-sm text-indigo-100">
                        <p>
                            {{ __('Utilisez l’assistant pour générer des sujets, tester des commandes ou consolider vos rapports.') }}
                        </p>
                        <p>
                            {{ __('Chaque module conserve son propre historique, accessible en un clic depuis la grille ci-dessus.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

</x-admin.global-layout>
