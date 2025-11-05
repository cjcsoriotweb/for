@php
    $inactiveTeamsCount = max($formation->teams_count - $formation->active_teams_count, 0);
    $completionRate = $enrollmentStats['total'] > 0
        ? round(($enrollmentStats['completed'] / max($enrollmentStats['total'], 1)) * 100)
        : 0;

    $teamStatus = static function ($team) {
        if ($team->pivot?->visible) {
            return [
                'label' => __('Activée'),
                'classes' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-400/10 dark:text-emerald-200',
            ];
        }

        if ($team->pivot?->approved_at) {
            return [
                'label' => __('Masquée'),
                'classes' => 'bg-slate-100 text-slate-600 dark:bg-slate-800/70 dark:text-slate-200',
            ];
        }

        return [
            'label' => __('En attente'),
            'classes' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-200',
        ];
    };
@endphp

<x-admin.global-layout
    icon="menu_book"
    :title="$formation->title"
    :subtitle="__('Vue détaillée de la formation :title', ['title' => $formation->title])"
>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                <a
                    href="{{ route('superadmin.formations.index') }}"
                    class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-4 py-2 font-semibold text-slate-600 transition hover:border-indigo-200 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-300"
                >
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    {{ __('Retour aux formations') }}
                </a>
                <span class="inline-flex items-center gap-1 text-xs uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">
                    #{{ $formation->id }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em]">
                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                    <span class="material-symbols-outlined text-base">groups</span>
                    {{ trans_choice(':count équipe|:count équipes', $formation->teams_count, ['count' => number_format($formation->teams_count)]) }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-2 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-200">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    {{ trans_choice(':count activée|:count activées', $formation->active_teams_count, ['count' => number_format($formation->active_teams_count)]) }}
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-slate-600 dark:bg-slate-800/70 dark:text-slate-200">
                    <span class="material-symbols-outlined text-base">person</span>
                    {{ trans_choice(':count inscrit|:count inscrits', $formation->learners_count, ['count' => number_format($formation->learners_count)]) }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ $formation->title }}</h2>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                {{ $formation->description ? strip_tags($formation->description) : __('Aucune description détaillée pour le moment.') }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if ($formation->category)
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/70 dark:text-slate-200">
                                    <span class="material-symbols-outlined text-base">category</span>
                                    {{ $formation->category?->name }}
                                </span>
                            @endif
                            @if ($formation->level)
                                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                    <span class="material-symbols-outlined text-base">stairs</span>
                                    {{ $formation->level }}
                                </span>
                            @endif
                            <span class="inline-flex items-center gap-2 rounded-full {{ $formation->active ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-200' : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-200' }} px-4 py-1 text-xs font-semibold">
                                <span class="material-symbols-outlined text-base">{{ $formation->active ? 'bolt' : 'block' }}</span>
                                {{ $formation->active ? __('Formation active') : __('Formation désactivée') }}
                            </span>
                        </div>
                    </div>

                    <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60">
                            <dt class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                                {{ __('Dernière mise à jour') }}
                            </dt>
                            <dd class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                                {{ optional($formation->updated_at ?? $formation->created_at)->diffForHumans() ?? __('Inconnu') }}
                            </dd>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60">
                            <dt class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                                {{ __('Tarif catalogue') }}
                            </dt>
                            <dd class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">
                                {{ number_format((int) $formation->money_amount, 0, ',', ' ') }} €
                            </dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                                {{ __('Équipes connectées') }}
                            </p>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                {{ trans_choice(':count équipe utilise cette formation|:count équipes utilisent cette formation', $formation->teams_count, ['count' => number_format($formation->teams_count)]) }}
                            </h3>
                        </div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __(':active activées, :pending en attente', ['active' => number_format($formation->active_teams_count), 'pending' => number_format($inactiveTeamsCount)]) }}
                        </div>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-700">
                            <thead class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <tr>
                                    <th class="px-4 py-3">{{ __('Équipe') }}</th>
                                    <th class="px-4 py-3">{{ __('Propriétaire') }}</th>
                                    <th class="px-4 py-3">{{ __('Utilisateurs') }}</th>
                                    <th class="px-4 py-3">{{ __('Statut') }}</th>
                                    <th class="px-4 py-3">{{ __('Ajoutée le') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($teamRows as $team)
                                    @php($status = $teamStatus($team))
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $team->name }}</div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500">
                                                {{ __('ID #:id', ['id' => $team->id]) }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-slate-600 dark:text-slate-300">
                                                {{ $team->owner?->name ?? __('N/A') }}
                                            </div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500">
                                                {{ $team->owner?->email }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">
                                            {{ number_format((int) $team->users_count) }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $status['classes'] }}">
                                                {{ $status['label'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                            {{ optional($team->pivot?->created_at)->diffForHumans() ?? __('Inconnu') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                            {{ __('Aucune équipe n’a encore activé cette formation.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                                {{ __('Utilisateurs inscrits') }}
                            </p>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                {{ trans_choice(':count inscription récente|:count inscriptions récentes', count($recentLearners), ['count' => count($recentLearners)]) }}
                            </h3>
                        </div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Total :total apprenants', ['total' => number_format($formation->learners_count)]) }}
                        </div>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-700">
                            <thead class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <tr>
                                    <th class="px-4 py-3">{{ __('Utilisateur') }}</th>
                                    <th class="px-4 py-3">{{ __('Equipe') }}</th>
                                    <th class="px-4 py-3">{{ __('Statut') }}</th>
                                    <th class="px-4 py-3">{{ __('Depuis') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @forelse ($recentLearners as $learner)
                                    @php($isCompleted = !is_null($learner->pivot?->completed_at))
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $learner->name }}</div>
                                            <div class="text-xs text-slate-400 dark:text-slate-500">{{ $learner->email }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                            {{ $learner->currentTeam?->name ?? __('Non défini') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isCompleted ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-200' : 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-200' }}">
                                                {{ $isCompleted ? __('Terminé') : __('En cours') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                            {{ optional($learner->pivot?->enrolled_at ?? $learner->pivot?->created_at)->diffForHumans() ?? __('Inconnu') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                            {{ __('Aucun inscrit récent à afficher.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                        {{ __('Performance') }}
                    </h3>
                    <div class="mt-4 grid gap-4">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Inscriptions totales') }}</p>
                            <p class="text-3xl font-semibold text-slate-900 dark:text-white">
                                {{ number_format($enrollmentStats['total']) }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/60">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Taux de complétion') }}</p>
                                <p class="text-xl font-semibold text-emerald-600 dark:text-emerald-200">{{ $completionRate }}%</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-emerald-600 dark:text-emerald-200">{{ __('Terminées') }}</p>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ number_format($enrollmentStats['completed']) }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('En cours') }}</p>
                            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-200">
                                {{ number_format($enrollmentStats['in_progress']) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Revenu estimé') }}</p>
                            <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                                {{ number_format($enrollmentStats['revenue_sum'], 0, ',', ' ') }} €
                            </p>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
                    <h3 class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                        {{ __('Structure pédagogique') }}
                    </h3>
                    <dl class="mt-4 space-y-4 text-sm text-slate-600 dark:text-slate-300">
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Chapitres') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ number_format($formation->chapters_count) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Leçons') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ number_format($formation->lessons_count) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Équipes actives') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ number_format($formation->active_teams_count) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt>{{ __('Équipes en attente') }}</dt>
                            <dd class="font-semibold text-slate-900 dark:text-white">{{ number_format($inactiveTeamsCount) }}</dd>
                        </div>
                    </dl>
                </section>
            </div>
        </div>
    </div>
</x-admin.global-layout>
