<div class="relative overflow-hidden rounded-3xl border border-emerald-200/70 bg-gradient-to-br from-emerald-50/90 via-teal-50/80 to-white p-8 shadow-xl dark:border-emerald-800/60 dark:bg-slate-900/60">
    <div class="absolute -left-12 top-8 h-32 w-32 rounded-full bg-emerald-300/30 blur-3xl dark:bg-emerald-400/20"></div>
    <div class="absolute -right-16 bottom-0 h-40 w-40 rounded-full bg-teal-300/30 blur-3xl dark:bg-teal-400/20"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(16,185,129,0.15),_transparent_65%)]"></div>

    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-lg">
                <span class="material-symbols-outlined text-3xl">bolt</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600/90 dark:text-emerald-300">
                    {{ __('Changer de perspective') }}
                </p>
                <h3 class="mt-2 text-xl font-semibold text-emerald-900 dark:text-emerald-100">
                    {{ __('Basculez instantanément entre vos rôles clés') }}
                </h3>
                <p class="mt-2 max-w-xl text-sm text-emerald-800/80 dark:text-emerald-200/80">
                    {{ __('Prévisualisez les espaces Élève et Organisateur pour valider vos paramétrages avant publication.') }}
                </p>
            </div>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <a
                href="{{ route('eleve.index', ['team' => $team, 'team_name' => $team->name]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-4 py-2 text-white shadow-md transition hover:shadow-lg"
            >
                <span class="material-symbols-outlined text-base">school</span>
                {{ __('Espace Élève') }}
            </a>

            <a
                href="{{ route('organisateur.index', ['team' => $team]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-400/60 bg-white/70 px-4 py-2 text-emerald-700 transition hover:-translate-y-0.5 hover:border-emerald-400 hover:bg-white dark:border-emerald-500/50 dark:bg-slate-900/60 dark:text-emerald-200"
            >
                <span class="material-symbols-outlined text-base">dashboard_customize</span>
                {{ __('Espace Organisateur') }}
            </a>
        </div>
    </div>
</div>
