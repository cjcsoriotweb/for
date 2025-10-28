@php
    $totalStats = collect($stats);
@endphp

<x-admin.global-layout
    icon="domain"
    :title="__('Pilotage global')"
    :subtitle="__('Gérez vos équipes, vos membres et leurs accès depuis un seul espace.')"
>
    <div class="space-y-12">
        <section>
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                            {{ __('Équipes actives') }}
                        </p>
                        <span class="material-symbols-outlined text-lg text-indigo-500">groups_2</span>
                    </div>
                    <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($totalStats->get('teams', 0)) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Équipes disponibles dans la plateforme.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                            {{ __('Utilisateurs enregistrés') }}
                        </p>
                        <span class="material-symbols-outlined text-lg text-indigo-500">person_check</span>
                    </div>
                    <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($totalStats->get('users', 0)) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Comptes actifs toutes équipes confondues.') }}
                    </p>
                </div>

                <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                            {{ __('Invitations en attente') }}
                        </p>
                        <span class="material-symbols-outlined text-lg text-indigo-500">forward_to_inbox</span>
                    </div>
                    <p class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($totalStats->get('invitations', 0)) }}
                    </p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Invitations à relancer ou valider rapidement.') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-2">
            <div class="space-y-5 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                <header class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                            {{ __('Équipes à superviser') }}
                        </p>
                        <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                            {{ __('Dernières activités équipes') }}
                        </h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Consultez les organisations récemment mises à jour et accédez à leur espace.') }}
                        </p>
                    </div>
                    @if (Route::has('teams.create'))
                        <a
                            href="{{ route('teams.create') }}"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800/70 dark:text-slate-300"
                        >
                            <span class="material-symbols-outlined mr-1 text-sm">add_business</span>
                            {{ __('Créer une équipe') }}
                        </a>
                    @endif
                </header>

                <ul class="space-y-4">
                    @forelse ($teams as $team)
                        <li class="rounded-2xl border border-slate-200/70 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700/70 dark:bg-slate-900">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                        <span class="material-symbols-outlined text-xl">diversity_3</span>
                                    </div>

                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $team->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ __('Propriétaire : :name', ['name' => $team->owner?->name ?? __('Inconnu')]) }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            {{ __('Mise à jour :date', ['date' => optional($team->updated_at)->diffForHumans()]) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                        <span class="material-symbols-outlined mr-1 text-sm">group</span>
                                        {{ ($team->users_count ?? 0) + 1 }} {{ \Illuminate\Support\Str::plural(__('membre'), ($team->users_count ?? 0) + 1) }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                        <span class="material-symbols-outlined mr-1 text-sm">mail</span>
                                        {{ $team->team_invitations_count }} {{ \Illuminate\Support\Str::plural(__('invitation'), $team->team_invitations_count) }}
                                    </span>
                                    <a
                                        href="{{ route('application.admin.index', $team) }}"
                                        class="inline-flex items-center rounded-full bg-emerald-500 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-600"
                                    >
                                        {{ __('Administrer') }}
                                        <span class="material-symbols-outlined ml-1 text-sm">arrow_outward</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                            {{ __("Aucune équipe n'a encore été créée sur la plateforme.") }}
                        </li>
                    @endforelse
                </ul>
            </div>

            <div class="space-y-5 rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                <header class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                            {{ __('Utilisateurs récents') }}
                        </p>
                        <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                            {{ __('Derniers comptes créés') }}
                        </h2>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ __('Gardez un œil sur les nouveaux arrivants et leurs rattachements aux équipes.') }}
                        </p>
                    </div>
                    @if (Route::has('users.create'))
                        <a
                            href="{{ route('users.create') }}"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800/70 dark:text-slate-300"
                        >
                            <span class="material-symbols-outlined mr-1 text-sm">group_add</span>
                            {{ __('Inviter un utilisateur') }}
                        </a>
                    @endif
                </header>

                <ul class="space-y-3">
                    @forelse ($recentUsers as $recentUser)
                        <li class="rounded-2xl border border-slate-200/70 bg-white px-4 py-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700/70 dark:bg-slate-900">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800/60 dark:text-slate-200">
                                        <span class="material-symbols-outlined text-lg">person</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $recentUser->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ $recentUser->email }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                        <span class="material-symbols-outlined mr-1 text-sm">calendar_month</span>
                                        {{ optional($recentUser->created_at)->diffForHumans() }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                        <span class="material-symbols-outlined mr-1 text-sm">diversity_2</span>
                                        {{ $recentUser->teams_count }} {{ \Illuminate\Support\Str::plural(__('équipe'), $recentUser->teams_count) }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-200">
                                        <span class="material-symbols-outlined mr-1 text-sm">location_away</span>
                                        {{ $recentUser->currentTeam?->name ?? __('Aucune équipe active') }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                            {{ __("Personne n'a encore rejoint la plateforme.") }}
                        </li>
                    @endforelse
                </ul>
            </div>
        </section>
    </div>
</x-admin.global-layout>
