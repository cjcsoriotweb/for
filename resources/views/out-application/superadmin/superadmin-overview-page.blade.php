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
    <div class="grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="space-y-6 rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-lg ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/80">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                    {{ __('Menu principal') }}
                </p>
                <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">
                    {{ __('Modules clefs') }}
                </h2>
            </div>
            <nav>
                <ul class="space-y-3">
                    @foreach ($modules as $module)
                        <li>
                            <a
                                href="{{ $module['route'] }}"
                                class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-white/70 p-3 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-white dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-indigo-500/40"
                            >
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-200">
                                    <span class="material-symbols-outlined text-2xl">{{ $module['icon'] }}</span>
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-200">
                                        {{ $module['title'] }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $module['description'] }}
                                    </p>
                                </div>
                                <span class="material-symbols-outlined text-base text-slate-400 transition group-hover:text-indigo-500 dark:text-slate-500">
                                    chevron_right
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </aside>

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

    </div>
</x-admin.global-layout>
