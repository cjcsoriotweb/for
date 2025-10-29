<div class="space-y-14">
    <section class="relative overflow-hidden rounded-3xl border border-slate-800/70 bg-slate-950 text-white shadow-2xl">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(148,163,255,0.25),_transparent_55%)]"></div>
        <div class="absolute -top-24 -right-28 h-80 w-80 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-emerald-500/20 blur-3xl"></div>

        <div class="relative px-8 py-10 lg:px-12 lg:py-12">
            <div class="flex flex-col gap-12 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl space-y-8">
                    <div class="space-y-4">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-white/70">
                            <span class="material-symbols-outlined text-base">admin_panel_settings</span>
                            {{ __('Espace administrateur') }}
                        </span>

                        <div class="space-y-3">
                            <h1 class="text-3xl font-semibold leading-tight text-white lg:text-[2.65rem]">
                                {{ __('Bonjour :name', ['name' => $adminName]) }}
                            </h1>
                            <p class="text-sm text-white/70 lg:text-base">
                                {{ __('Pilotez votre plateforme avec une vision claire de vos actions clés et des résultats de votre équipe.') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.35em] text-white/60">
                                <span>{{ __('Utilisateurs') }}</span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2 py-1 text-[11px] tracking-[0.25em] text-white/70">
                                    <span class="material-symbols-outlined text-[13px]">groups</span>
                                    {{ $totalUsers }}
                                </span>
                            </div>
                            <p class="mt-3 text-lg font-semibold text-white">
                                {{ $totalUsers }} {{ $usersLabel }}
                            </p>
                            <p class="mt-1 text-xs text-white/60">
                                {{ __('Collaborateurs connectés à votre organisation') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.35em] text-white/60">
                                <span>{{ __('Formations actives') }}</span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2 py-1 text-[11px] tracking-[0.25em] text-white/70">
                                    <span class="material-symbols-outlined text-[13px]">workspace_premium</span>
                                    {{ $activeCount }}
                                </span>
                            </div>
                            <p class="mt-3 text-lg font-semibold text-white">
                                {{ __(':active formations disponibles', ['active' => $activeCount]) }}
                            </p>
                            <p class="mt-1 text-xs text-white/60">
                                {{ __('Sur un catalogue total de :total contenus', ['total' => $totalCount]) }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.35em] text-white/60">
                                <span>{{ __('Catalogue') }}</span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-2 py-1 text-[11px] tracking-[0.25em] text-white/70">
                                    <span class="material-symbols-outlined text-[13px]">inventory_2</span>
                                    {{ $totalCount }}
                                </span>
                            </div>
                            <p class="mt-3 text-lg font-semibold text-white">
                                {{ __('Catalogue complet :total', ['total' => $totalCount]) }}
                            </p>
                            <p class="mt-1 text-xs text-white/60">
                                {{ __('Contenus prêts à être déployés aux équipes') }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-white/60">
                            {{ __('Navigation rapide') }}
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <a
                                href="#bascule"
                                class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/80 transition hover:bg-white/20 hover:text-white"
                            >
                                <span class="material-symbols-outlined text-base">tune</span>
                                {{ __('Actions') }}
                            </a>
                            <a
                                href="#fonctionnement"
                                class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/80 transition hover:bg-white/20 hover:text-white"
                            >
                                <span class="material-symbols-outlined text-base">monitoring</span>
                                {{ __('Statistiques') }}
                            </a>
                            <a
                                href="#configuration"
                                class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-white/80 transition hover:bg-white/20 hover:text-white"
                            >
                                <span class="material-symbols-outlined text-base">settings</span>
                                {{ __('Configuration') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-md rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur lg:max-w-sm">
                    <div class="space-y-6">
                        <div class="flex flex-col items-center gap-3 text-center">
                            <div class="relative flex h-24 w-24 items-center justify-center overflow-hidden rounded-3xl border border-white/20 bg-white/10">
                                @if ($hasTeamLogo && $teamLogoUrl)
                                    <img
                                        src="{{ $teamLogoUrl }}"
                                        alt="{{ __('Logo de l\'équipe :name', ['name' => $team->name]) }}"
                                        class="h-full w-full object-contain"
                                    />
                                @else
                                    <span class="material-symbols-outlined text-4xl text-white/70">shield_person</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/60">
                                    {{ __('Équipe') }}
                                </p>
                                <p class="text-lg font-semibold text-white">
                                    {{ $team->name }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.35em] text-white/50">
                                    <span>{{ __('Adoption') }}</span>
                                    <span>{{ $totalUsers }}</span>
                                </div>
                                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-white/10">
                                    <div
                                        class="h-full rounded-full bg-emerald-400"
                                        style="width: {{ $usersProgressWidth }}%;"
                                    ></div>
                                </div>
                                <p class="mt-1 text-[11px] text-white/60">
                                    {{ __('Progression des membres connectés') }}
                                </p>
                            </div>

                            <div>
                                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.35em] text-white/50">
                                    <span>{{ __('Formations visibles') }}</span>
                                    <span>{{ $activeCount }}/{{ $totalCount }}</span>
                                </div>
                                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-white/10">
                                    <div
                                        class="h-full rounded-full bg-indigo-400"
                                        style="width: {{ $visiblePercentage }}%;"
                                    ></div>
                                </div>
                                <p class="mt-1 text-[11px] text-white/60">
                                    {{ __('Part des contenus déployés au catalogue') }}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-white/10 p-4 text-sm text-white/80">
                            <p class="font-semibold uppercase tracking-[0.3em] text-white/60">
                                {{ __('Suivi rapide') }}
                            </p>
                            <ul class="mt-3 space-y-2 text-[13px] text-white/75">
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-emerald-300">bolt</span>
                                    {{ __('Retrouvez vos leviers prioritaires dans la section Actions.') }}
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-indigo-300">hub</span>
                                    {{ __('Visualisez l\'impact des formations depuis Statistiques.') }}
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-sky-300">tune</span>
                                    {{ __('Pilotez les paramètres essentiels via Configuration.') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="bascule" class="space-y-6">
        <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400">
            <span class="material-symbols-outlined text-lg">tune</span>
            <h2 class="text-xs font-semibold uppercase tracking-[0.35em]">
                {{ __('Actions contextuelles') }}
            </h2>
        </div>
        <div class="rounded-3xl border border-slate-200/70 bg-white/70 p-6 shadow-sm shadow-slate-200/60 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/70 dark:shadow-none">
            @include('in-application.admin.partials.configuration')
        </div>
    </section>

    <section id="fonctionnement" class="space-y-6">
        <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400">
            <span class="material-symbols-outlined text-lg">monitoring</span>
            <h2 class="text-xs font-semibold uppercase tracking-[0.35em]">
                {{ __('Basique') }}
            </h2>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('in-application.admin.partials.menu-fast.stats.users-stats', ['team' => $team])
            @include('in-application.admin.partials.menu-fast.stats.formations-stats', ['team' => $team])
        </div>
    </section>

    <section id="configuration" class="space-y-6">
        <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400">
            <span class="material-symbols-outlined text-lg">settings</span>
            <h2 class="text-xs font-semibold uppercase tracking-[0.35em]">
                {{ __('Important') }}
            </h2>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('in-application.admin.partials.menu-fast.stats.credit-team', ['team' => $team])
            @include('in-application.admin.partials.menu-fast.stats.configuration-team', ['team' => $team])
        </div>
    </section>
</div>
