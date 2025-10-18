<a
    href="{{ route('application.admin.formations.index', $team) }}"
    class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6"
>
    <div class="flex items-center space-x-4">
        <div
            class="w-12 h-12 bg-green-100 dark:bg-green-900 dark:bg-opacity-30 rounded-xl flex items-center justify-center"
        >
            <span
                class="material-symbols-outlined text-green-600 dark:text-green-400"
                >school</span
            >
        </div>
        <div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ $activeCount }}/{{ $totalCount }}
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-400">
                Formations disponibles
            </div>
        </div>
    </div>
</a>
