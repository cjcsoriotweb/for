<a
    href="{{ route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name]) }}"
    class="group relative overflow-hidden rounded-2xl border border-indigo-200/70 bg-white/80 p-6 shadow-lg transition-all hover:-translate-y-1 hover:border-indigo-300/80 hover:shadow-xl dark:border-indigo-700/50 dark:bg-slate-800/70"
>
    <div class="absolute -left-10 top-10 h-28 w-28 rounded-full bg-indigo-400/20 blur-3xl transition group-hover:bg-indigo-300/25"></div>
    <div class="absolute -bottom-12 right-0 h-24 w-24 rounded-full bg-purple-400/20 blur-3xl transition group-hover:bg-purple-300/25"></div>

    <div class="relative flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-600/90 dark:text-indigo-300">
                {{ __('Configuration') }}
            </p>
            <span class="mt-3 block text-lg font-semibold text-slate-900 dark:text-white">
                {{ __('Identite de l equipe') }}
            </span>
        </div>
        <span class="material-symbols-outlined text-3xl text-indigo-500 transition group-hover:text-indigo-400 dark:text-indigo-300">
            settings_cinematic_blur
        </span>
    </div>

    <ul class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-400">
        <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-base text-indigo-400 dark:text-indigo-300">brush</span>
            {{ __('Logo, couleurs et presentation de l equipe') }}
        </li>
        <li class="flex items-center gap-2">
            <span class="material-symbols-outlined text-base text-indigo-400 dark:text-indigo-300">verified_user</span>
            {{ __('Gestion des acces avances') }}
        </li>
    </ul>

    <span class="mt-5 inline-flex items-center text-xs font-medium text-indigo-600 transition group-hover:text-indigo-500 dark:text-indigo-300 dark:group-hover:text-indigo-200">
        {{ __('Ouvrir la configuration') }}
        <span class="material-symbols-outlined ml-2 text-base">arrow_outward</span>
    </span>
</a>

