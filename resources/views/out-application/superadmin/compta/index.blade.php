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
        <section class="rounded-3xl border border-slate-100 bg-white/70 p-8 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Mois en cours') }}</p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Tableau de bord comptable') }}</h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Un condensé des consommations et des formations actives sur la période en cours.') }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                        {{ now()->translatedFormat('F Y') }}
                    </div>
                    <button
                        type="button"
                        class="flex h-10 items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600"
                    >
                        <span class="material-symbols-outlined text-base">refresh</span>
                        {{ __('Actualiser les données') }}
                    </button>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="flex flex-col gap-2 rounded-2xl border border-slate-100 bg-slate-50/70 p-6 shadow-inner dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Formations actives ce mois-ci') }}</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['active_formations'] ?? 0) }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Formations publiées et suivies depuis le début du mois.') }}</p>
                </div>
                <div class="flex flex-col gap-2 rounded-2xl border border-slate-100 bg-white p-6 shadow-lg dark:border-slate-800 dark:bg-slate-950/60">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Élèves ayant commencé') }}</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['students_started'] ?? 0) }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Inscriptions dont la date de début est dans la période actuelle.') }}</p>
                </div>
                <div class="flex flex-col gap-2 rounded-2xl border border-slate-100 bg-slate-50/70 p-6 shadow-inner dark:border-slate-800 dark:bg-slate-900/60">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Formations restantes non utilisées') }}</p>
                    <p class="text-4xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['unused_formations'] ?? 0) }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Formations avec licences disponibles encore non consommées.') }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-100 bg-white/70 p-6 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">{{ __('Synthèse') }}</p>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ __('Formations suivies en priorité') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Les formations qui concentrent le plus d’élèves inscrits et leur statut de consommation.') }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="flex h-9 items-center justify-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600">
                        <span class="material-symbols-outlined text-base">calendar_month</span>
                        {{ __('Filtrer par mois') }}
                    </button>
                    <button class="flex h-9 items-center justify-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600">
                        <span class="material-symbols-outlined text-base">groups</span>
                        {{ __('Filtrer par équipe') }}
                    </button>
                    <button class="flex h-9 items-center justify-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-white dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600">
                        <span class="material-symbols-outlined text-base">auto_graph</span>
                        {{ __('Filtrer par formation') }}
                    </button>
                </div>
            </div>

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
