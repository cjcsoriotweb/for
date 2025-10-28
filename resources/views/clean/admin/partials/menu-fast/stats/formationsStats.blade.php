@php
    $totalVisible = $totalCount > 0 ? round(($activeCount / max(1, $totalCount)) * 100) : 0;
@endphp

<a
    href="{{ route('application.admin.formations.index', $team) }}"
    class="group relative overflow-hidden rounded-2xl border border-emerald-200/70 bg-white/80 p-6 shadow-lg transition-all hover:-translate-y-1 hover:border-emerald-300/80 hover:shadow-xl dark:border-emerald-700/40 dark:bg-slate-800/70"
>
    <div class="absolute -right-4 -top-16 h-32 w-32 rounded-full bg-emerald-400/20 blur-3xl transition group-hover:bg-emerald-300/25"></div>

    <div class="relative flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600/90 dark:text-emerald-300">
                {{ __('Catalogue') }}
            </p>
            <span class="mt-3 block text-3xl font-bold text-slate-900 dark:text-white">
                {{ $activeCount }} / {{ $totalCount }}
            </span>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Formations visibles par votre équipe') }}
            </p>
        </div>
        <div class="relative flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/15">
            <span class="material-symbols-outlined text-3xl text-emerald-500 dark:text-emerald-300">school</span>
            <span class="absolute -bottom-3 inline-flex items-center rounded-full bg-emerald-500 px-2 py-1 text-xs font-semibold text-white shadow-md">
                {{ $totalVisible }}%
            </span>
        </div>
    </div>

    <div class="mt-6 space-y-3">
        <div class="relative h-2 rounded-full bg-emerald-100/80 dark:bg-emerald-900/50">
            <div
                class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-emerald-400 to-teal-400"
                style="width: {{ min(100, max(8, $totalVisible)) }}%;"
            ></div>
        </div>
        <p class="flex items-center text-xs font-medium text-emerald-600 transition group-hover:text-emerald-500 dark:text-emerald-300 dark:group-hover:text-emerald-200">
            <span class="material-symbols-outlined mr-2 text-base">schedule_send</span>
            {{ __('Ajuster la visibilité ou publier de nouvelles sessions') }}
        </p>
    </div>
</a>
