<x-admin.layout
    :team="$team"
    icon="shield_person"
    :title="__('Tableau de bord administrateur')"
    :subtitle="__('Pilotez votre plateforme de formation avec une vue claire sur vos actions clés.')"
>
    <x-slot:headerActions>
        <a
            href="{{ route('application.admin.formations.index', $team) }}"
            class="inline-flex items-center rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-white transition-colors hover:bg-white/20"
        >
            <span class="material-symbols-outlined mr-2 text-base">library_books</span>
            {{ __('Formations') }}
        </a>
        <a
            href="{{ route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name]) }}"
            class="inline-flex items-center rounded-lg bg-emerald-500 px-4 py-2 text-white transition-colors hover:bg-emerald-600 shadow-sm"
        >
            <span class="material-symbols-outlined mr-2 text-base">tune</span>
            {{ __('Configuration') }}
        </a>
    </x-slot:headerActions>

    <div class="relative pb-16">
        <div class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-900 opacity-80 blur-3xl"></div>
        <x-admin.admin-menu-fast :team="$team" />
    </div>

    <div class="mt-12 grid gap-8 xl:grid-cols-2">
        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm dark:border-slate-700/70 dark:bg-slate-900/60">
            <header class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Formations à administrer') }}
                    </p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Dernières mises à jour') }}
                    </h2>
                </div>
                <a
                    href="{{ route('application.admin.formations.index', $team) }}"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-300"
                >
                    {{ __('Tout gérer') }}
                    <span class="material-symbols-outlined ml-1 text-base">arrow_outward</span>
                </a>
            </header>

            <ul class="space-y-4">
                @forelse ($recentFormations as $formation)
                    <li class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200/70 bg-white/90 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-700/80 dark:bg-slate-900/70">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-base font-semibold text-slate-900 dark:text-white">
                                    {{ $formation->title }}
                                </span>
                                @if ($formation->is_visible)
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-200">
                                        {{ __('Active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-500/20 dark:text-amber-200">
                                        {{ __('Non visible') }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ $formation->subtitle ?? __('Aucune description pour le moment.') }}
                            </p>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                {{ __('Mis à jour le :date', ['date' => optional($formation->updated_at ?? $formation->created_at)->translatedFormat('d MMMM yyyy')]) }}
                            </p>
                        </div>

                        <a
                            href="{{ route('application.admin.formations.index', $team) }}#formation-{{ $formation->id }}"
                            class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-200"
                        >
                            {{ __('Administrer') }}
                            <span class="material-symbols-outlined ml-1 text-sm">open_in_new</span>
                        </a>
                    </li>
                @empty
                    <li class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                        {{ __('Aucune formation disponible pour le moment. Ajoutez votre première formation pour commencer.') }}
                    </li>
                @endforelse
            </ul>
        </section>

        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-lg backdrop-blur-sm dark:border-slate-700/70 dark:bg-slate-900/60">
            <header class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Utilisateurs à administrer') }}
                    </p>
                    <h2 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Vos membres & invitations') }}
                    </h2>
                </div>
                <a
                    href="{{ route('application.admin.users.index', $team) }}"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-300"
                >
                    {{ __('Gérer l’équipe') }}
                    <span class="material-symbols-outlined ml-1 text-base">arrow_outward</span>
                </a>
            </header>

            <div class="space-y-6">
                <ul class="space-y-3">
                    @forelse ($managedUsers as $user)
                        <li class="flex items-center justify-between rounded-2xl border border-slate-200/70 bg-white/90 px-4 py-3 shadow-sm dark:border-slate-700/80 dark:bg-slate-900/70">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ __('Depuis :date', ['date' => optional(optional($user->pivot)->created_at ?? $user->created_at)->translatedFormat('d MMM yyyy')]) }}
                            </p>
                        </li>
                    @empty
                        <li class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                            {{ __('Aucun membre n’est encore associé à cette équipe.') }}
                        </li>
                    @endforelse
                </ul>

                <div class="rounded-2xl border border-indigo-200/70 bg-indigo-50/80 p-4 shadow-sm dark:border-indigo-500/30 dark:bg-indigo-500/10">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-200">
                                {{ __('Invitations en attente') }}
                            </p>
                            <p class="mt-1 text-xs text-indigo-600/80 dark:text-indigo-200/80">
                                {{ __('Suivez les invitations en cours et relancez vos prospects.') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center justify-center rounded-full bg-white px-3 py-1 text-xs font-semibold text-indigo-600 shadow-sm dark:bg-indigo-500/20 dark:text-indigo-200">
                            {{ $pendingInvitations->count() }}
                        </span>
                    </div>

                    <ul class="mt-3 space-y-2">
                        @forelse ($pendingInvitations as $invitation)
                            <li class="flex items-center justify-between rounded-xl bg-white/80 px-3 py-2 text-xs text-indigo-700 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-200">
                                <span>{{ $invitation->email }}</span>
                                <span>{{ optional($invitation->created_at)->diffForHumans() }}</span>
                            </li>
                        @empty
                            <li class="rounded-xl bg-white/70 px-3 py-2 text-center text-xs text-indigo-600/70 shadow-sm dark:bg-indigo-500/10 dark:text-indigo-200/70">
                                {{ __('Aucune invitation en attente actuellement.') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </section>
    </div>
</x-admin.layout>
