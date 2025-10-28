<div class="w-full max-w-5xl space-y-8">
    <div class="rounded-2xl border border-slate-700/40 bg-slate-900/60 p-8 text-center shadow-lg shadow-slate-900/30">
        <h2 class="text-3xl font-semibold text-white">
            {{ __("Bienvenue sur le tableau de bord administrateur") }}
        </h2>
        <p class="mt-4 text-base text-slate-300">
            {{ __("Cette section reprend l'en-tete principal de la page. Elle met en avant ton nom d'administrateur ainsi que la volumetrie actuelle de ton equipe.") }}
        </p>
    </div>

    <section class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-950 text-white shadow-2xl">
        <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-28 -right-20 h-72 w-72 rounded-full bg-emerald-500/25 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.08),_transparent_55%)]"></div>
        <div class="relative p-8 lg:p-12">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm uppercase tracking-[0.4em] text-white/50">{{ __('Espace administrateur') }}</p>
                    <h3 class="mt-4 text-2xl font-semibold text-white">
                        {{ __('Bonjour :name', ['name' => $adminName ?? __('Administrateur')]) }}
                    </h3>
                    <p class="mt-2 text-sm text-white/70">
                        {{ __("Ici, tu retrouves les chiffres clefs de ton organisme pour valider d'un coup d'oeil la dynamique actuelle.") }}
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">groups</span>
                            {{ $totalUsers }} {{ $usersLabel ?? '' }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">workspace_premium</span>
                            {{ __(':active formations actives', ['active' => $activeCount ?? 0]) }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">inventory_2</span>
                            {{ __('Catalogue :total', ['total' => $totalCount ?? 0]) }}
                        </span>
                    </div>
                </div>

                <div class="flex-shrink-0 lg:w-72">
                    <div class="relative aspect-square w-full overflow-hidden rounded-3xl border border-white/15 bg-white/5 backdrop-blur">
                        <div class="absolute inset-6 rounded-2xl bg-gradient-to-br from-white/10 to-white/5"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            @if (($hasTeamLogo ?? false) && ($teamLogoUrl ?? false))
                                <img
                                    src="{{ $teamLogoUrl }}"
                                    alt="{{ __('Logo de l\'equipe :name', ['name' => $team?->name]) }}"
                                    class="h-32 w-32 rounded-2xl object-contain"
                                />
                            @else
                                <span class="material-symbols-outlined text-6xl text-white/70">auto_awesome</span>
                            @endif
                        </div>
                        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-white/15 px-4 py-2 text-xs font-medium uppercase tracking-[0.3em] text-white/70">
                            {{ $team?->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

