@php
    $stats = collect($stats);
    $trainerCount = \App\Models\AiTrainer::query()->count();

    $categories = [
        [
            'title' => __('Organisations'),
            'tagline' => __('Equipes, utilisateurs, invitations'),
            'icon' => 'groups_3',
            'highlight' => [
                'value' => number_format($stats->get('teams', 0)),
                'label' => __('equipes actives'),
            ],
            'links' => [
                ['label' => __('Equipes'), 'route' => route('superadmin.teams.index')],
                ['label' => __('Utilisateurs'), 'route' => route('superadmin.users.index')],
                ['label' => __('Invitations'), 'route' => route('superadmin.teams.index', ['focus' => 'invitations'])],
            ],
        ],
        [
            'title' => __('Catalogue & IA'),
            'tagline' => __('Formations et assistants'),
            'icon' => 'library_books',
            'highlight' => [
                'value' => number_format($stats->get('formations', 0)),
                'label' => __('formations publiees'),
            ],
            'links' => [
                ['label' => __('Catalogue'), 'route' => route('superadmin.formations.index')],
                ['label' => __('Assistants IA'), 'route' => route('superadmin.ai.index')],
                ['label' => __('Categories de formation'), 'route' => route('superadmin.formation-categories.index')],
            ],
        ],
        [
            'title' => __('Activation'),
            'tagline' => __('Acces et parcours'),
            'icon' => 'bolt',
            'highlight' => [
                'value' => number_format($stats->get('invitations', 0)),
                'label' => __('invitations en attente'),
            ],
            'links' => [
                ['label' => __('Inviter une equipe'), 'route' => route('superadmin.teams.index', ['focus' => 'invitations'])],
                ['label' => __('Rechercher un compte'), 'route' => route('superadmin.users.index')],
            ],
        ],
    ];

    $quickActions = [
        [
            'icon' => 'support_agent',
            'label' => __('Centre support'),
            'description' => __('Suivre les tickets ouverts'),
            'stat' => number_format($stats->get('tickets', 0)) . ' ' . __('tickets'),
            'route' => route('superadmin.support.index'),
        ],
        [
            'icon' => 'smart_toy',
            'label' => __('AI Studio'),
            'description' => __('Configurer les formateurs IA'),
            'stat' => number_format($trainerCount) . ' ' . __('assistants'),
            'route' => route('superadmin.ai.index'),
        ],
        [
            'icon' => 'checklist',
            'label' => __('Tests superadmin'),
            'description' => __('Verifier l application'),
            'stat' => __('Automatise'),
            'route' => route('superadmin.tests.index'),
        ],
    ];
@endphp

<x-admin.global-layout
    icon="dashboard"
    :title="__('Superadmin')"
    :subtitle="__('Choisissez une categorie ou lancez une action rapide.')"
>
    <div class="space-y-12">
        <section class="grid gap-6 lg:grid-cols-3">
            @foreach ($categories as $category)
                <a
                    href="{{ $category['links'][0]['route'] }}"
                    class="group flex flex-col rounded-3xl border border-slate-100 bg-white/80 p-8 shadow-lg ring-1 ring-black/5 transition hover:-translate-y-1 hover:border-indigo-200 hover:bg-white hover:shadow-2xl dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-indigo-500/40"
                >
                    <div class="flex items-center gap-5">
                        <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-500/10 text-4xl text-indigo-500 dark:bg-indigo-500/20 dark:text-indigo-200">
                            <span class="material-symbols-outlined text-4xl">{{ $category['icon'] }}</span>
                        </span>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                                {{ $category['title'] }}
                            </p>
                            <p class="mt-1 text-2xl font-semibold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-200">
                                {{ $category['tagline'] }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach ($category['links'] as $link)
                            <span class="inline-flex items-center gap-2 rounded-full border border-slate-200/80 px-4 py-1.5 text-sm font-medium text-slate-600 transition group-hover:border-indigo-200 group-hover:text-indigo-600 dark:border-slate-700 dark:text-slate-300 dark:group-hover:border-indigo-500/50">
                                {{ $link['label'] }}
                                <span class="material-symbols-outlined text-base">arrow_outward</span>
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-8 flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                        <span class="font-semibold text-slate-900 dark:text-white">
                            {{ $category['highlight']['value'] }}
                            <span class="font-normal text-slate-500 dark:text-slate-400">{{ $category['highlight']['label'] }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1 font-semibold text-indigo-600 dark:text-indigo-200">
                            {{ __('Ouvrir') }}
                            <span class="material-symbols-outlined text-base">chevron_right</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </section>

        <section>
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Actions rapides') }}
                    </p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Acces directs lorsque ce ne sont pas des categories.') }}
                    </h2>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($quickActions as $action)
                    <a
                        href="{{ $action['route'] }}"
                        class="group flex items-center justify-between rounded-2xl border border-slate-100 bg-white/70 px-6 py-5 text-left shadow ring-1 ring-black/5 transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-white hover:text-indigo-600 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900/80 dark:hover:border-indigo-500/40"
                    >
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                                {{ __('Action') }}
                            </p>
                            <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                                {{ $action['label'] }}
                            </p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $action['description'] }}
                            </p>
                            <p class="mt-3 text-xs font-semibold text-slate-400 dark:text-slate-500">
                                {{ $action['stat'] }}
                            </p>
                        </div>
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-indigo-500 transition group-hover:bg-indigo-50 group-hover:text-indigo-600 dark:bg-slate-800 dark:text-indigo-200">
                            <span class="material-symbols-outlined">{{ $action['icon'] }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-admin.global-layout>
