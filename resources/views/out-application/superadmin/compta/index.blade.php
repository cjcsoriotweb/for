<x-admin.global-layout
    icon="account_balance"
    :title="__('Comptabilité')"
    :subtitle="__('Suivez l’usage des formations et la consommation des licences sur la plateforme.')"
>
    <x-slot name="headerActions">
        <a
            href="{{ route('superadmin.db') }}"
            class="inline-flex items-center gap-2 rounded-2xl border border-white/40 bg-white/90 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
        >
            <span class="material-symbols-outlined text-base">storage</span>
            {{ __('Console DB') }}
        </a>
    </x-slot>

    <div class="space-y-8">
  
        <section class="rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Synthèse') }}</p>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ __('Formations suivies en priorité') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Les formations qui concentrent le plus d’étudiants inscrits et leur statut de consommation.') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                        {{ __('Période analysée') }} : {{ $selectedMonthLabel }}
                    </span>
                    <a
                        href="{{ request()->fullUrl() }}"
                        class="flex h-9 items-center justify-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600"
                    >
                        <span class="material-symbols-outlined text-base">refresh</span>
                        {{ __('Actualiser les données') }}
                    </a>
                </div>
            </div>

            <form method="get" class="mt-6 grid gap-3 lg:grid-cols-4">
                <label class="flex flex-col gap-2 rounded-3xl border border-slate-100 bg-slate-50/70 p-4 text-sm dark:border-slate-800 dark:bg-slate-950/60">
                    <span class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Filtrer par mois') }}</span>
                    <input
                        type="month"
                        name="filter_month"
                        value="{{ $filters['month'] }}"
                        class="h-11 w-full rounded-2xl border border-transparent bg-white px-3 text-sm font-medium text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-slate-900 dark:text-white"
                    />
                </label>

                <label class="flex flex-col gap-2 rounded-3xl border border-slate-100 bg-slate-50/70 p-4 text-sm dark:border-slate-800 dark:bg-slate-950/60">
                    <span class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Filtrer par équipe') }}</span>
                    <select
                        name="filter_team"
                        class="h-11 w-full rounded-2xl border border-transparent bg-white px-3 text-sm font-medium text-slate-900 transition focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="">{{ __('Toutes les équipes') }}</option>
                        @foreach ($filterTeams as $team)
                            <option value="{{ $team->id }}" @selected((int) $filters['team'] === $team->id)>{{ $team->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="flex flex-col gap-2 rounded-3xl border border-slate-100 bg-slate-50/70 p-4 text-sm dark:border-slate-800 dark:bg-slate-950/60">
                    <span class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Filtrer par formation') }}</span>
                    <select
                        name="filter_formation"
                        class="h-11 w-full rounded-2xl border border-transparent bg-white px-3 text-sm font-medium text-slate-900 transition focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:bg-slate-900 dark:text-white"
                    >
                        <option value="">{{ __('Toutes les formations') }}</option>
                        @foreach ($filterFormations as $formationOption)
                            <option value="{{ $formationOption->id }}" @selected((int) $filters['formation'] === $formationOption->id)>{{ $formationOption->title }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="flex items-end gap-3 lg:justify-end">
                    <button
                        type="submit"
                        class="flex h-11 items-center justify-center gap-2 rounded-2xl border border-indigo-500 bg-indigo-500 px-4 text-sm font-semibold text-white transition hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                    >
                        <span class="material-symbols-outlined text-base">filter_alt</span>
                        {{ __('Appliquer les filtres') }}
                    </button>
                    <a
                        href="{{ route('superadmin.compta.index') }}"
                        class="flex h-11 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600"
                    >
                        <span class="material-symbols-outlined text-base">refresh</span>
                        {{ __('Réinitialiser') }}
                    </a>
                </div>
            </form>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-500 dark:bg-slate-950/40 dark:text-slate-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">{{ __('Nom de la formation') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('Équipe') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('Élèves inscrits') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ __('Statut') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-border-dark">
                        @forelse ($formations as $formation)
                            <tr class="bg-white dark:bg-slate-900/60">
                                <td class="px-4 py-4">
                                    <a href="{{ $formation['route'] }}" class="text-sm font-medium text-slate-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
                                        {{ $formation['name'] }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $formation['team'] }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-400">
                                    {{ number_format($formation['students']) }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $formation['status_classes']['bg'] }} {{ $formation['status_classes']['text'] }}">
                                        {{ $formation['status_label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white dark:bg-slate-900/60">
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Aucune formation trouvée pour cette période.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-admin.global-layout>
