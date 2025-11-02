@php
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Str;

    $defaultTeam = Auth::user()?->currentTeam ?? Auth::user()?->allTeams()->first();
    $sort = $sort ?? request()->get('sort', 'revenue_desc');
    $sortKey = Str::beforeLast($sort, '_');
    $sortDirection = Str::endsWith($sort, '_asc') ? 'asc' : 'desc';
    $nextSort = function (string $column) use ($sortKey, $sortDirection): string {
        return $sortKey === $column && $sortDirection === 'asc'
            ? "{$column}_desc"
            : "{$column}_asc";
    };
    $sortIcon = function (string $column) use ($sortKey, $sortDirection): ?string {
        if ($sortKey !== $column) {
            return null;
        }

        return $sortDirection === 'asc' ? 'stat_1' : 'south';
    };
@endphp

<x-admin.global-layout
    icon="library_books"
    :title="__('Gestion des formations')"
    :subtitle="__('Observez et administrez le catalogue global mis à disposition des équipes.')"
>
    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <form
                method="GET"
                action="{{ route('superadmin.formations.index') }}"
                class="flex w-full max-w-lg items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 shadow-sm dark:border-slate-700 dark:bg-slate-900/70"
            >
                <span class="material-symbols-outlined text-slate-400">search</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="{{ __('Rechercher une formation (titre ou description)…') }}"
                    class="w-full bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:outline-none dark:text-slate-200"
                />
            </form>
            <div class="flex flex-wrap items-center gap-3">
                @if ($defaultTeam)
                    <a
                        href="{{ route('application.admin.formations.index', ['team' => $defaultTeam]) }}"
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600"
                    >
                        <span class="material-symbols-outlined text-base">open_in_new</span>
                        {{ __('Gérer les formations') }}
                    </a>
                @endif
                <a
                    href="{{ route('superadmin.formation-categories.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-indigo-200 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-50"
                >
                    <span class="material-symbols-outlined text-base">category</span>
                    {{ __('Gérer les catégories de formation') }}
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($formations as $formation)
                <article class="flex h-full flex-col justify-between rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg transition hover:-translate-y-0.5 hover:shadow-xl dark:border-slate-700/70 dark:bg-slate-900">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ $formation->title }}
                                </h3>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($formation->description ?? ''), 180) ?: __('Aucune description renseignée pour cette formation.') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                <span class="material-symbols-outlined mr-1 text-sm">diversity_3</span>
                                {{ $formation->teams_count }} {{ \Illuminate\Support\Str::plural(__('équipe'), $formation->teams_count) }}
                            </span>
                        </div>

                        @if ($formation->teams_count > 0)
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                                    {{ __('Équipes concernées') }}
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($formation->teams->take(5) as $team)
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800/70 dark:text-slate-200">
                                            {{ $team->name }}
                                        </span>
                                    @endforeach
                                    @if ($formation->teams_count > 5)
                                        <span class="inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-700/70 dark:text-slate-200">
                                            +{{ $formation->teams_count - 5 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>
                            {{ __('Mis à jour :date', ['date' => optional($formation->updated_at ?? $formation->created_at)->diffForHumans()]) }}
                        </span>
                        <span>ID #{{ $formation->id }}</span>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                    {{ __("Aucune formation ne correspond à votre recherche.") }}
                </div>
            @endforelse
        </div>

        <div>
            {{ $formations->links() }}
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Revenus par formation') }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Analysez les revenus bruts estimés sur la base des coûts d’inscription ou du tarif catalogue.') }}
                    </p>
                </div>
                <div class="text-xs text-slate-400">
                    {{ __('Tri actuel : :label', ['label' => match($sortKey) {
                        'enrollments' => __('inscriptions'),
                        'title' => __('nom'),
                        'updated_at' => __('dernière activité'),
                        default => __('revenu total'),
                    } . ' ' . ($sortDirection === 'asc' ? __('croissant') : __('décroissant'))]) }}
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm dark:divide-slate-700">
                    <thead class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <tr>
                            @foreach ([
                                'title' => __('Formation'),
                                'enrollments' => __('Inscriptions'),
                                'revenue' => __('Revenu estimé'),
                                'updated_at' => __('Dernière inscription'),
                            ] as $column => $label)
                                @php($isSortable = in_array($column, ['title', 'enrollments', 'revenue', 'updated_at'], true))
                                <th scope="col" class="px-4 py-3">
                                    @if ($isSortable)
                                        <a
                                            href="{{ route('superadmin.formations.index', array_merge(request()->only('search'), ['sort' => $nextSort($column)])) }}"
                                            class="inline-flex items-center gap-1 text-slate-600 transition hover:text-indigo-600 dark:text-slate-300 dark:hover:text-indigo-300"
                                        >
                                            <span>{{ $label }}</span>
                                            @if ($icon = $sortIcon($column))
                                                <span class="material-symbols-outlined text-sm">{{ $icon }}</span>
                                            @endif
                                        </a>
                                    @else
                                        {{ $label }}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-600 dark:divide-slate-700 dark:text-slate-200">
                        @forelse ($revenueRows as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                <td class="px-4 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-900 dark:text-white">{{ $row->title }}</span>
                                        <span class="text-xs text-slate-400 dark:text-slate-500">
                                            {{ trans_choice(':count équipe|:count équipes', $row->teams_count, ['count' => number_format((int) $row->teams_count)]) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold">{{ number_format((int) $row->enrollments_count) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format((int) $row->revenue_sum, 0, ',', ' ') }} €
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    {{ optional(Carbon::make($row->last_enrollment_at))->diffForHumans() ?? __('Jamais') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Aucune donnée de revenu à afficher pour le moment.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin.global-layout>
