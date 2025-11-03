<a
    href="{{ route('application.admin.users.index', $team) }}"
    class="group relative overflow-hidden rounded-2xl border border-slate-200/60 bg-white/80 p-6 shadow-lg transition-all hover:-translate-y-1 hover:border-blue-300/80 hover:shadow-xl dark:border-slate-700/60 dark:bg-slate-800/70"
>
    <div class="absolute -right-6 -top-12 h-32 w-32 rounded-full bg-blue-500/15 blur-3xl transition group-hover:bg-blue-400/20"></div>

    <div class="relative flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                {{ __('Utilisateurs') }}
            </p>
            <span class="mt-3 block text-3xl font-bold text-slate-900 dark:text-white">
                {{ $totalUsers }}
            </span>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Actifs sur cette application') }}
            </p>
        </div>
        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white shadow-inner">
            <span class="material-symbols-outlined text-3xl">group</span>
        </div>
    </div>

    <div class="relative mt-6 h-2 rounded-full bg-slate-200/80 dark:bg-slate-700/70">
        <div
            class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500"
            style="width: {{ $usersProgressWidth }}%;"
        ></div>
    </div>

  
</a>
