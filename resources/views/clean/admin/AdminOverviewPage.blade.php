@php
    $stats = collect($stats);

    $cards = [
        [
            'route' => route('superadmin.teams.index'),
            'icon' => 'groups_2',
            'label' => __('Equipes actives'),
            'stat' => number_format($stats->get('teams', 0)),
            'description' => __('Gerez toutes les organisations et leurs acces.'),
            'cta' => __('Gerer les equipes'),
        ],
        [
            'route' => route('superadmin.users.index'),
            'icon' => 'person_check',
            'label' => __('Utilisateurs enregistres'),
            'stat' => number_format($stats->get('users', 0)),
            'description' => __('Supervisez les comptes et leurs roles.'),
            'cta' => __('Gerer les utilisateurs'),
        ],
        [
            'route' => route('formateur.home'),
            'icon' => 'library_books',
            'label' => __('Formations disponibles'),
            'stat' => number_format($stats->get('formations', 0)),
            'description' => __('Pilotez le catalogue pour chaque equipe.'),
            'cta' => __('Gerer les formations'),
        ],
        [
            'route' => route('superadmin.support.index'),
            'icon' => 'support_agent',
            'label' => __('Tickets support'),
            'stat' => number_format($stats->get('tickets', 0)),
            'description' => __('Suivez les demandes utilisateurs en direct.'),
            'cta' => __('Ouvrir le centre support'),
        ],
        [
            'route' => route('superadmin.ai.index'),
            'icon' => 'smart_toy',
            'label' => __('Formateurs IA'),
            'stat' => number_format($stats->get('ai_trainers', 0)),
            'description' => __('Configurez les profils IA et testez leurs prompts.'),
            'cta' => __('Gerer lIA'),
        ],
        [
            'route' => route('superadmin.teams.index', ['focus' => 'invitations']),
            'icon' => 'forward_to_inbox',
            'label' => __('Invitations en attente'),
            'stat' => number_format($stats->get('invitations', 0)),
            'description' => __('Relancez ou validez les acces en attente.'),
            'cta' => __('Voir les invitations'),
        ],
    ];
@endphp

<x-admin.global-layout
    icon="domain"
    :title="__('Pilotage global')"
    :subtitle="__('Accedez rapidement a chaque espace de gestion superadministrateur.')"
>
    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <a
                href="{{ $card['route'] }}"
                class="group flex h-full flex-col justify-between rounded-3xl border border-slate-200/70 bg-white p-7 shadow-lg transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-2xl dark:border-slate-700/70 dark:bg-slate-900 dark:hover:border-indigo-500/50"
            >
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 transition group-hover:text-indigo-600 dark:text-slate-400 dark:group-hover:text-indigo-300">
                            {{ $card['label'] }}
                        </p>
                        <p class="mt-6 text-4xl font-bold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-200">
                            {{ $card['stat'] }}
                        </p>
                        <p class="mt-3 text-sm text-slate-500 transition group-hover:text-slate-600 dark:text-slate-400 dark:group-hover:text-slate-300">
                            {{ $card['description'] }}
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-3xl text-indigo-500 transition group-hover:scale-110">
                        {{ $card['icon'] }}
                    </span>
                </div>
                <span class="mt-8 inline-flex items-center justify-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition group-hover:border-indigo-300 group-hover:bg-indigo-100 group-hover:text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-200 dark:group-hover:border-indigo-400/40 dark:group-hover:bg-indigo-500/20">
                    {{ $card['cta'] }}
                    <span class="material-symbols-outlined text-sm">arrow_outward</span>
                </span>
            </a>
        @endforeach
    </div>
</x-admin.global-layout>
