@php($defaultTeam = Auth::user()?->currentTeam ?? Auth::user()?->allTeams()->first())

<x-admin.global-layout
    icon="library_books"
    :title="__('Gestion des formations')"
    :subtitle="__('Observez et administrez le catalogue global mis à disposition des équipes.')"
>
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
            @if ($defaultTeam)
                <a
                    href="{{ route('application.admin.formations.index', ['team' => $defaultTeam]) }}"
                    class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600"
                >
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    {{ __('Accéder à un espace équipe') }}
                </a>
            @endif
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
    </div>
</x-admin.global-layout>
