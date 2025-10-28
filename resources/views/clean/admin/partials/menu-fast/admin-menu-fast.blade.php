<div class="space-y-12">
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-950 text-white shadow-2xl border border-white/10">
        <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-28 -right-20 h-72 w-72 rounded-full bg-emerald-500/25 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.08),_transparent_55%)]"></div>
        <div class="relative p-8 lg:p-12">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm uppercase tracking-[0.4em] text-white/50">{{ __('Espace administrateur') }}</p>
           
         

                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">groups</span>
                            {{ $totalUsers }} {{ $usersLabel }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">workspace_premium</span>
                            {{ __(':active formations actives', ['active' => $activeCount]) }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-sm font-medium backdrop-blur">
                            <span class="material-symbols-outlined mr-2 text-base">inventory_2</span>
                            {{ __('Catalogue :total', ['total' => $totalCount]) }}
                        </span>
                    </div>
                </div>

                <div class="flex-shrink-0 lg:w-72">
                    <div class="relative aspect-square w-full overflow-hidden rounded-3xl border border-white/15 bg-white/5 backdrop-blur">
                        <div class="absolute inset-6 rounded-2xl bg-gradient-to-br from-white/10 to-white/5"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            @if ($hasTeamLogo && $teamLogoUrl)
                                <img
                                    src="{{ $teamLogoUrl }}"
                                    alt="{{ __('Logo de l’équipe :name', ['name' => $team->name]) }}"
                                    class="h-32 w-32 rounded-2xl object-contain "
                                />
                            @else
                                <span class="material-symbols-outlined text-6xl text-white/70">auto_awesome</span>
                            @endif
                        </div>
                        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-white/15 px-4 py-2 text-xs font-medium uppercase tracking-[0.3em] text-white/70">
                            {{ $team->name }}
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </section>



        <section id="bascule">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Actions contextuelles') }}
        </h2>
        @include('clean.admin.partials.configuration')
    </section>

    <section id="fonctionnement">
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Basique') }}
        </h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('clean.admin.partials.menu-fast.stats.usersStats', ['team' => $team])
            @include('clean.admin.partials.menu-fast.stats.formationsStats', ['team' => $team])
        </div>
    </section>


    
        <section id="configuration">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Important') }}
        </h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('clean.admin.partials.menu-fast.stats.creditTeam', ['team' => $team])


            @include('clean.admin.partials.menu-fast.stats.configurationTeam', ['team' => $team])
        </div>
    </section>
    
</div>
