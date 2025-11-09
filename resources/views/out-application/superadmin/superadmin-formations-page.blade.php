<x-admin.global-layout
    icon="library_books"
    :title="__('Gestion des formations')"
    :subtitle="__('Observez et administrez le catalogue global mis Ã  disposition des Ã©quipes.')"
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
                    placeholder="{{ __('Rechercher une formation (titre ou description)â€¦') }}"
                    class="w-full bg-transparent text-sm text-slate-600 placeholder:text-slate-400 focus:outline-none dark:text-slate-200"
                />
            </form>
            <div class="flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('superadmin.formation-categories.index') }}"
                    class="inline-flex items-center gap-2 rounded-full border border-indigo-200 px-4 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-50"
                >
                    <span class="material-symbols-outlined text-base">category</span>
                    {{ __('GÃ©rer les catÃ©gories de formation') }}
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($formations as $formation)
                <a
                    href="{{ route('superadmin.formations.show', $formation) }}"
                    class="group block h-full"
                >
                <article class="flex h-full flex-col justify-between rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg transition group-hover:-translate-y-0.5 group-hover:border-indigo-200 group-hover:shadow-xl dark:border-slate-700/70 dark:bg-slate-900 dark:group-hover:border-indigo-500/40">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ $formation->title }}
                                </h3>
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($formation->description ?? ''), 180) ?: __('Aucune description renseignÃ©e pour cette formation.') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-200">
                                <span class="material-symbols-outlined mr-1 text-sm">diversity_3</span>
                                {{ $formation->teams_count }} {{ \Illuminate\Support\Str::plural(__('Ã©quipe'), $formation->teams_count) }}
                            </span>
                        </div>

                        @if ($formation->teams_count > 0)
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                                    {{ __('Ã‰quipes concernÃ©es') }}
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
                            {{ __('Mis Ã  jour :date', ['date' => optional($formation->updated_at ?? $formation->created_at)->diffForHumans()]) }}
                        </span>
                        <span>ID #{{ $formation->id }}</span>
                    </div>
                </article>
                </a>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900/40 dark:text-slate-400">
                    {{ __("Aucune formation ne correspond Ã  votre recherche.") }}
                </div>
            @endforelse
        </div>

        <div>
            {{ $formations->links() }}
        </div>


        <section id="suivis" class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-lg dark:border-slate-700/70 dark:bg-slate-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Suivis') }}
                    </p>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Activations & parcours') }}
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Consultez en un coup d’œil les activations totales et les progrès enregistrés ce mois-ci.') }}
                    </p>
                </div>
                <a
                    href="{{ route('superadmin.completion-requests.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-indigo-200 bg-indigo-50 px-5 py-2 text-sm font-semibold text-indigo-600 transition hover:border-indigo-300 hover:bg-indigo-100 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-200"
                >
                    {{ __('Voir les suivis') }}
                </a>
            </div>

            @php
                $followCards = [
                    [
                        'label' => __('Activations totales'),
                        'value' => $followStats['activation_total'] ?? 0,
                        'subtext' => __('Formations activées auprès des équipes.'),
                        'icon' => 'bolt',
                        'iconColor' => 'text-emerald-500 dark:text-emerald-300',
                    ],
                    [
                        'label' => __('Activations ce mois-ci'),
                        'value' => $followStats['activated_this_month'] ?? 0,
                        'subtext' => __('Nouvelles activations depuis le début du mois.'),
                        'icon' => 'bolt',
                        'iconColor' => 'text-indigo-500 dark:text-indigo-300',
                    ],
                    [
                        'label' => __('Formations commencées ce mois-ci'),
                        'value' => $followStats['started_this_month'] ?? 0,
                        'subtext' => __('Inscriptions ou premiers accès enregistrés.'),
                        'icon' => 'play_circle',
                        'iconColor' => 'text-sky-500 dark:text-sky-300',
                    ],
                    [
                        'label' => __('Formations terminées ce mois-ci'),
                        'value' => $followStats['completed_this_month'] ?? 0,
                        'subtext' => __('Parcours déclarés comme terminés.'),
                        'icon' => 'check_circle',
                        'iconColor' => 'text-fuchsia-500 dark:text-fuchsia-300',
                    ],
                ];
            @endphp

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($followCards as $card)
                    <div class="rounded-2xl border border-slate-100 bg-white/80 p-4 shadow-sm ring-1 ring-slate-200 dark:border-slate-800 dark:bg-slate-900/60 dark:ring-slate-900/40">
                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.4em] text-slate-400 dark:text-slate-500">
                            <span class="material-symbols-outlined text-base {{ $card['iconColor'] }}">
                                {{ $card['icon'] }}
                            </span>
                            {{ $card['label'] }}
                        </div>
                        <p class="mt-3 text-3xl font-semibold text-slate-900 dark:text-white">
                            {{ number_format($card['value']) }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $card['subtext'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-admin.global-layout>
