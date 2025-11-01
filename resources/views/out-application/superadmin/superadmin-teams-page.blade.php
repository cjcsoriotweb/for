<x-admin.global-layout
    icon="groups_2"
    :title="__('Gestion des équipes')"
    :subtitle="__('Consultez et administrez l’ensemble des organisations de la plateforme.')"
>
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form
                method="GET"
                action="{{ route('superadmin.teams.index') }}"
                class="flex w-full max-w-md items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm dark:border-slate-700 dark:bg-slate-900/70"
            >
                <span class="material-symbols-outlined text-slate-400">search</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('Rechercher une équipe ou un propriétaire…') }}"
                    class="w-full bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:outline-none dark:text-slate-200"
                />
            </form>
            @if (Route::has('teams.create'))
                <a
                    href="{{ route('teams.create') }}"
                    class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600"
                >
                    <span class="material-symbols-outlined text-base">add_business</span>
                    {{ __('Créer une équipe') }}
                </a>
            @endif
        </div>

        <div class="overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50/80 dark:bg-slate-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">{{ __('Équipe') }}</th>
                        <th class="px-6 py-4">{{ __('Propriétaire') }}</th>
                        <th class="px-6 py-4 text-center">{{ __('Membres') }}</th>
                        <th class="px-6 py-4 text-center">{{ __('Invitations') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Dernière activité') }}</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/60 dark:divide-slate-800/80">
                    @forelse ($teams as $team)
                        <tr class="transition hover:bg-slate-50/60 dark:hover:bg-slate-800/60">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                        <span class="material-symbols-outlined text-lg">domain</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $team->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                            ID #{{ $team->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $team->owner?->name ?? __('Non défini') }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $team->owner?->email ?? '—' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                    {{ ($team->users_count ?? 0) + 1 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                    {{ $team->team_invitations_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-slate-500 dark:text-slate-400">
                                {{ optional($team->updated_at)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a
                                    href="{{ route('application.admin.index', $team) }}"
                                    class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-100 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200"
                                >
                                    <span class="material-symbols-outlined text-sm">settings</span>
                                    {{ __('Administrer') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                {{ __("Aucune équipe ne correspond à votre recherche.") }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $teams->links() }}
        </div>
    </div>
</x-admin.global-layout>
