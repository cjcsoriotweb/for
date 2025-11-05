@php
    $stats = collect($stats);

    $modules = [
        [
            'title' => __('Equipes'),
            'description' => __('Pilotez toutes les organisations et leurs acces.'),
            'stat' => number_format($stats->get('teams', 0)) . ' ' . __('equipes'),
            'icon' => 'groups_2',
            'route' => route('superadmin.teams.index'),
        ],
        [
            'title' => __('Utilisateurs'),
            'description' => __('Consultez les comptes et leurs droits.'),
            'stat' => number_format($stats->get('users', 0)) . ' ' . __('comptes'),
            'icon' => 'badge',
            'route' => route('superadmin.users.index'),
        ],
        [
            'title' => __('Formations'),
            'description' => __('Mettez a jour le catalogue global.'),
            'stat' => number_format($stats->get('formations', 0)) . ' ' . __('formations'),
            'icon' => 'library_books',
            'route' => route('superadmin.formations.index'),
        ],
        [
            'title' => __('Assistants IA'),
            'description' => __('Creez ou ajustez les formateurs IA.'),
            'stat' => number_format($trainerCount) . ' ' . __('assistants'),
            'icon' => 'smart_toy',
            'route' => route('superadmin.ai.index'),
        ],
        [
            'title' => __('Invitations'),
            'description' => __('Suivez les acces en attente et relancez.'),
            'stat' => number_format($stats->get('invitations', 0)) . ' ' . __('invites'),
            'icon' => 'forward_to_inbox',
            'route' => route('superadmin.teams.index', ['focus' => 'invitations']),
        ],
        [
            'title' => __('Support & tests'),
            'description' => __('Centre support et batterie de tests.'),
            'stat' => number_format($stats->get('tickets', 0)) . ' ' . __('tickets'),
            'icon' => 'support_agent',
            'route' => route('superadmin.support.index'),
        ],
    ];
@endphp

<x-admin.global-layout
    icon="dashboard"
    :title="__('Espace superadmin')"
    :subtitle="__('Choisissez directement la zone de travail ou un raccourci.')"
>
    <div class="space-y-12">
        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($modules as $module)
                <a
                    href="{{ $module['route'] }}"
                    class="group flex h-full flex-col rounded-3xl border border-slate-100 bg-white/80 p-8 shadow-lg ring-1 ring-black/5 transition hover:-translate-y-1 hover:border-indigo-200 hover:bg-white hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-indigo-500/40"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                                {{ __('Module') }}
                            </p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-200">
                                {{ $module['title'] }}
                            </h2>
                            <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                                {{ $module['description'] }}
                            </p>
                        </div>
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-200">
                            <span class="material-symbols-outlined text-3xl">{{ $module['icon'] }}</span>
                        </span>
                    </div>
                    <div class="mt-6 flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                        <span class="text-3xl font-semibold text-slate-900 dark:text-white">
                            {{ $module['stat'] }}
                        </span>
                        <span class="inline-flex items-center gap-1 font-semibold text-indigo-600 dark:text-indigo-200">
                            {{ __('Ouvrir') }}
                            <span class="material-symbols-outlined text-base">chevron_right</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </section>
    </div>
</x-admin.global-layout>
