@php
    use Carbon\Carbon;
@endphp

<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">token</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    {{ $formation->title }}
                </h2>
                <p class="text-blue-100 text-sm">
                    Revenus en jetons - {{ Carbon::createFromFormat('Y-m', $selectedMonth)->isoFormat('MMMM YYYY') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        <section class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="p-6 md:p-8 space-y-6">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">
                            Revenus du mois sélectionné
                        </h1>
                        <p class="text-sm text-gray-600">
                            Visualisez les jetons générés par formation, par équipe et par utilisateur.
                        </p>
                    </div>
                    <form
                        method="GET"
                        action="{{ route('application.admin.formations.revenue', ['team' => $team, 'formation' => $formation]) }}"
                        class="flex flex-wrap items-end gap-4"
                    >
                        <div>
                            <label for="month" class="block text-xs font-semibold uppercase text-gray-500 tracking-wider">
                                Mois
                            </label>
                            <select
                                id="month"
                                name="month"
                                class="mt-1 w-40 rounded-lg border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                            >
                                @foreach($availableMonths as $monthOption)
                                    <option value="{{ $monthOption }}" {{ $monthOption === $selectedMonth ? 'selected' : '' }}>
                                        {{ Carbon::createFromFormat('Y-m', $monthOption)->isoFormat('MMMM YYYY') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($teams->isNotEmpty())
                        <div>
                            <label for="team" class="block text-xs font-semibold uppercase text-gray-500 tracking-wider">
                                Équipe
                            </label>
                            <select
                                id="team"
                                name="team"
                                class="mt-1 w-48 rounded-lg border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500"
                            >
                                <option value="all" {{ empty($selectedTeamId) || $selectedTeamId === 'all' ? 'selected' : '' }}>
                                    Toutes les équipes
                                </option>
                                @foreach($teams as $teamOption)
                                    <option value="{{ $teamOption->id }}" {{ (string) $teamOption->id === (string) $selectedTeamId ? 'selected' : '' }}>
                                        {{ $teamOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-purple-700"
                        >
                            <span class="material-symbols-outlined text-base">refresh</span>
                            Mettre à jour
                        </button>
                    </form>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-purple-100 bg-purple-50 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-purple-500 tracking-widest">
                                Jetons générés
                            </span>
                            <span class="material-symbols-outlined text-purple-400">token</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-purple-700">
                            {{ number_format($totalRevenue, 0, ',', ' ') }}
                        </p>
                        <p class="text-xs text-purple-500">
                            Mois courant
                        </p>
                    </div>
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-emerald-500 tracking-widest">
                                Inscriptions
                            </span>
                            <span class="material-symbols-outlined text-emerald-400">group_add</span>
                        </div>
                        <p class="mt-3 text-2xl font-bold text-emerald-700">
                            {{ $enrollments->count() }}
                        </p>
                        <p class="text-xs text-emerald-500">
                            Utilisateurs inscrits sur la période
                        </p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-blue-500 tracking-widest">
                                Début de période
                            </span>
                            <span class="material-symbols-outlined text-blue-400">calendar_month</span>
                        </div>
                        <p class="mt-3 text-lg font-semibold text-blue-700">
                            {{ $periodStart->isoFormat('DD MMM YYYY') }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase text-blue-500 tracking-widest">
                                Fin de période
                            </span>
                            <span class="material-symbols-outlined text-blue-400">event</span>
                        </div>
                        <p class="mt-3 text-lg font-semibold text-blue-700">
                            {{ $periodEnd->isoFormat('DD MMM YYYY') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-8 lg:grid-cols-3">
            <section class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Détail des inscriptions
                        </h2>
                        <p class="text-sm text-gray-600">
                            Liste des utilisateurs inscrits, avec le montant de jetons consommés.
                        </p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                <th scope="col" class="px-4 py-3">Utilisateur</th>
                                <th scope="col" class="px-4 py-3">Équipe</th>
                                <th scope="col" class="px-4 py-3">Date d'inscription</th>
                                <th scope="col" class="px-4 py-3 text-right">Jetons</th>
                                <th scope="col" class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                            @forelse($enrollments as $enrollment)
                                @php
                                    $enrollmentDate = Carbon::make($enrollment->enrolled_at ?? $enrollment->created_at);
                                    $tokenAmount = (int) ($enrollment->enrollment_cost ?? $formation->money_amount ?? 0);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ optional($enrollment->user)->name ?? 'Utilisateur inconnu' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ optional($enrollment->team)->name ?? 'Non renseignée' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $enrollmentDate?->isoFormat('DD MMM YYYY HH:mm') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                        {{ number_format($tokenAmount, 0, ',', ' ') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($enrollment->user)
                                            <a
                                                href="{{ route('application.admin.formations.students.show', [$team, $formation, $enrollment->user_id]) }}"
                                                class="inline-flex items-center gap-2 rounded-lg bg-purple-600 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-white transition hover:bg-purple-700"
                                            >
                                                <span class="material-symbols-outlined text-base">visibility</span>
                                                Suivre
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">
                                        Aucune inscription sur la période sélectionnée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-600">
                            Répartition par équipe
                        </h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($teamSummaries as $summary)
                            <div class="flex items-center justify-between px-5 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ optional($summary['team'])->name ?? 'Non renseignée' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $summary['enrollments'] }} inscription(s)
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-purple-700">
                                        {{ number_format($summary['total_tokens'], 0, ',', ' ') }} jetons
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-6 text-sm text-gray-500">
                                Aucune donnée pour la période sélectionnée.
                            </div>
                        @endforelse
                    </div>
                </div>

                <a
                    href="{{ route('application.admin.formations.index', $team) }}"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Retourner à la liste des formations
                </a>
            </aside>
        </div>
    </div>
</x-application-layout>
