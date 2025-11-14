@props([
    'icon' => 'admin_panel_settings',
    'title' => __('Administration centrale'),
    'subtitle' => __('Supervisez l’ensemble des équipes et des utilisateurs.'),
])

@php
    $menuModules = [
        [
            'title' => __('Équipes'),
            'description' => __('Pilotez toutes les organisations et leurs accès.'),
            'icon' => 'groups_2',
            'route' => route('superadmin.teams.index'),
        ],
        [
            'title' => __('Utilisateurs'),
            'description' => __('Consultez les comptes et leurs droits.'),
            'icon' => 'badge',
            'route' => route('superadmin.users.index'),
        ],
        [
            'title' => __('Valider fin de formation'),
            'description' => __('Validez les demandes de fin de formation.'),
            'icon' => 'check_circle',
            'route' => route('superadmin.completion-requests.index'),
        ],
        [
            'title' => __('Support client'),
            'description' => __('Centre support et batterie de tests.'),
            'icon' => 'support_agent',
            'route' => route('superadmin.support.index'),
        ],
        [
            'title' => __('Suivis des formations'),
            'description' => __('Recherche avancée sur les formations'),
            'icon' => 'book',
            'route' => route('superadmin.compta.index'),
        ],
        [
            'title' => __('Assistant IA'),
            'description' => __('Dialogue et recherches IA dans un iframe sécurisé.'),
            'icon' => 'smart_toy',
            'route' => route('superadmin.assistant'),
        ],
        [
            'title' => __('Console debug'),
            'description' => __('Exécutez des commandes Artisan supervisées.'),
            'icon' => 'terminal',
            'route' => route('superadmin.console'),
        ],
        [
            'title' => __('Base de données'),
            'description' => __('PhpMyAdmin et sauvegardes en un clic.'),
            'icon' => 'dns',
            'route' => route('superadmin.db'),
        ],
    ];
@endphp

<x-app-layout>
    <header class="mb-5">
        <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950">
            <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-10 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:py-12">
                <div class="flex items-start gap-4">
                    <a href="{{ route('superadmin.overview') }}">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-500/20 text-indigo-200 ring-1 ring-inset ring-indigo-500/40">
                            <span class="material-symbols-outlined text-2xl">{{ $icon }}</span>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-white sm:text-4xl">
                            {{ $title }}
                        </h1>
                        <p class="mt-2 text-sm text-indigo-100/90">
                            {{ $subtitle }}
                        </p>
                    </div>
                </div>

                @isset($headerActions)
                    <div class="flex flex-wrap items-center gap-3">
                        {{ $headerActions }}
                    </div>
                @endisset
            </div>
        </div>
    </header>

    <div class="mx-auto grid max-w-7xl gap-8 px-4 pb-12 sm:px-6 lg:grid-cols-[280px_minmax(0,1fr)] lg:px-8">
        <aside class="space-y-6 rounded-3xl bg-white/80 p-6 ring-black/5 dark:border-slate-800 dark:bg-slate-900/80">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                    {{ __('Menu principal') }}
                </p>
            </div>
            <nav>
                <ul class="space-y-3">
                    @foreach ($menuModules as $module)
                        <li>
                            <a
                                href="{{ $module['route'] }}"
                                class="group flex items-center gap-3 rounded-2xl   bg-white/70  text-left transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-white dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-indigo-500/40"
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

        <main class="space-y-8">
            {{ $slot }}
        </main>
    </div>
</x-app-layout>
