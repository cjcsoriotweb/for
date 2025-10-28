<div class="space-y-12">
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-950 text-white shadow-2xl border border-white/10">
        <div class="absolute -top-24 -left-24 h-64 w-64 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute -bottom-28 -right-20 h-72 w-72 rounded-full bg-emerald-500/25 blur-3xl"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.08),_transparent_55%)]"></div>

        <div class="relative p-8 lg:p-12">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm uppercase tracking-[0.4em] text-white/50">{{ __('Espace administrateur') }}</p>
                    <h1 class="mt-2 text-3xl font-bold lg:text-4xl">
                        {{ __('Bonjour :name', ['name' => $adminName]) }}
                    </h1>
                    <p class="mt-4 text-lg text-white/80">
                        {{ __('Gardez le cap sur la réussite de votre équipe :team en suivant vos indicateurs clés et vos actions prioritaires.', ['team' => $team->name]) }}
                    </p>

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

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a
                    href="{{ route('application.admin.users.index', $team) }}"
                    class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 transition-all hover:-translate-y-1 hover:border-white/30 hover:bg-white/10"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/60">{{ __('Gestion') }}</p>
                            <span class="mt-3 block text-lg font-semibold">{{ __('Utilisateurs & rôles') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-3xl text-white/60 transition group-hover:text-white">group_work</span>
                    </div>
                    <p class="mt-3 text-sm text-white/70">
                        {{ __('Invitez de nouvelles personnes, attribuez des rôles et suivez leur activité.') }}
                    </p>
                    <span class="mt-5 inline-flex items-center text-sm font-medium text-emerald-200 group-hover:text-emerald-100">
                        {{ __('Accéder') }}
                        <span class="material-symbols-outlined ml-2 text-base">arrow_outward</span>
                    </span>
                </a>

                <a
                    href="{{ route('application.admin.formations.index', $team) }}"
                    class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 transition-all hover:-translate-y-1 hover:border-white/30 hover:bg-white/10"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/60">{{ __('Catalogue') }}</p>
                            <span class="mt-3 block text-lg font-semibold">{{ __('Formations & visibilité') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-3xl text-white/60 transition group-hover:text-white">library_books</span>
                    </div>
                    <p class="mt-3 text-sm text-white/70">
                        {{ __('Activez ou mettez en avant les parcours qui comptent pour votre équipe.') }}
                    </p>
                    <span class="mt-5 inline-flex items-center text-sm font-medium text-emerald-200 group-hover:text-emerald-100">
                        {{ __('Organiser') }}
                        <span class="material-symbols-outlined ml-2 text-base">arrow_outward</span>
                    </span>
                </a>

                <a
                    href="{{ route('application.admin.configuration.index', ['team' => $team, 'team_name' => $team->name]) }}"
                    class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-6 transition-all hover:-translate-y-1 hover:border-white/30 hover:bg-white/10"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/60">{{ __('Paramètres') }}</p>
                            <span class="mt-3 block text-lg font-semibold">{{ __('Identité & crédits') }}</span>
                        </div>
                        <span class="material-symbols-outlined text-3xl text-white/60 transition group-hover:text-white">tune</span>
                    </div>
                    <p class="mt-3 text-sm text-white/70">
                        {{ __('Ajustez le branding de l’équipe, gérez le budget et les accès avancés.') }}
                    </p>
                    <span class="mt-5 inline-flex items-center text-sm font-medium text-emerald-200 group-hover:text-emerald-100">
                        {{ __('Configurer') }}
                        <span class="material-symbols-outlined ml-2 text-base">arrow_outward</span>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section>
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Vos indicateurs') }}
        </h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('clean.admin.partials.menu-fast.stats.usersStats', ['team' => $team])
            @include('clean.admin.partials.menu-fast.stats.formationsStats', ['team' => $team])
            @include('clean.admin.partials.menu-fast.stats.configurationTeam', ['team' => $team])
        </div>
    </section>

    <section>
        <h2 class="mb-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
            {{ __('Actions contextuelles') }}
        </h2>
        @include('clean.admin.partials.configuration')
    </section>
</div>
