@php
    $summary = collect($summary);
@endphp

<x-admin.global-layout
    icon="note_stack"
    :title="__('Notes développeur')"
    :subtitle="__('Suivez, priorisez et résolvez les annotations laissées sur chaque page de la plateforme.')"
>
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-md dark:border-slate-700/70 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                {{ __('Notes en attente') }}
            </p>
            <p class="mt-4 text-4xl font-bold text-slate-900 dark:text-white">
                {{ number_format($summary->get('pending', 0)) }}
            </p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ __('à traiter sur l’ensemble des pages') }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-md dark:border-slate-700/70 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                {{ __('Total des notes') }}
            </p>
            <p class="mt-4 text-4xl font-bold text-slate-900 dark:text-white">
                {{ number_format($summary->get('total', 0)) }}
            </p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ __('historique complet des retours') }}
            </p>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-md dark:border-slate-700/70 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                {{ __('Pages concernées') }}
            </p>
            <p class="mt-4 text-4xl font-bold text-slate-900 dark:text-white">
                {{ number_format($summary->get('paths', 0)) }}
            </p>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ __('chemins différents ont reçu au moins une note') }}
            </p>
        </div>
    </div>

    <div class="mt-10 grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-md dark:border-slate-700/70 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Pages avec notes') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Analysez la répartition des retours par URL.') }}
                    </p>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800/60">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('Chemin') }}
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('En attente') }}
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('Total') }}
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('Activité') }}
                            </th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">{{ __('Actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse ($paths as $path)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    <span class="font-mono text-xs uppercase text-slate-500 dark:text-slate-400">{{ $path->path }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-amber-600 dark:text-amber-300">
                                    {{ number_format((int) $path->pending_notes) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                    {{ number_format((int) $path->total_notes) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">
                                    @if ($path->latest_activity)
                                        {{ \Illuminate\Support\Carbon::parse($path->latest_activity)->diffForHumans() }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ url($path->path) }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-blue-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-200"
                                        >
                                            <span class="material-symbols-outlined text-base">link</span>
                                            {{ __('Ouvrir') }}
                                        </a>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-full border border-indigo-300 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-600 transition hover:border-indigo-400 hover:bg-indigo-100 dark:border-indigo-500/50 dark:bg-indigo-500/10 dark:text-indigo-200 dark:hover:bg-indigo-500/20"
                                            data-open-page-notes
                                            data-open-page-notes-path="{{ $path->path }}"
                                        >
                                            <span class="material-symbols-outlined text-base">note_alt</span>
                                            {{ __('Widget') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Aucune note n’a encore été enregistrée via le widget superadmin.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-md dark:border-slate-700/70 dark:bg-slate-900">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                        {{ __('Dernières notes') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Historique des 25 dernières soumissions.') }}
                    </p>
                </div>
            </div>

            <div class="mt-4 space-y-4">
                @forelse ($recentNotes as $note)
                    <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-indigo-200 hover:shadow-md dark:border-slate-700 dark:bg-slate-800/70 dark:hover:border-indigo-500/40">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                    {{ $note->title ?: __('Sans titre') }}
                                </h3>
                                <p class="mt-1 text-xs font-mono uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                    {{ $note->path }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                {{ $note->is_resolved ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200' }}">
                                {{ $note->is_resolved ? __('Résolu') : __('À traiter') }}
                            </span>
                        </div>

                        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">
                            {{ \Illuminate\Support\Str::limit($note->content, 220) }}
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-400 dark:text-slate-500">
                            <span class="inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-base">schedule</span>
                                {{ $note->updated_at?->diffForHumans() ?? $note->created_at?->diffForHumans() }}
                            </span>
                            @if ($note->user)
                                <span class="inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-base">person</span>
                                    {{ $note->user->name }}
                                </span>
                            @endif
                            <button
                                type="button"
                                class="inline-flex items-center gap-1 rounded-full border border-slate-200 px-2 py-1 font-medium text-slate-500 transition hover:border-indigo-200 hover:text-indigo-600 dark:border-slate-600 dark:text-slate-300 dark:hover:border-indigo-500/70 dark:hover:text-indigo-200"
                                data-open-page-notes
                                data-open-page-notes-path="{{ $note->path }}"
                            >
                                <span class="material-symbols-outlined text-base">note_alt</span>
                                {{ __('Ouvrir dans le widget') }}
                            </button>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 p-8 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                        {{ __('Aucune note n’a encore été créée. Utilisez le bouton “Notes de page” sur une page pour commencer.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin.global-layout>

