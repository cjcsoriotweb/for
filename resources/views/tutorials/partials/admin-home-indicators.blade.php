<div class="w-full max-w-5xl space-y-8">
    <div class="rounded-2xl border border-slate-700/40 bg-slate-900/60 p-8 text-center shadow-lg shadow-slate-900/30">
        <h2 class="text-3xl font-semibold text-white">
            {{ __("Suivre vos indicateurs complementaires") }}
        </h2>
        <p class="mt-4 text-base text-slate-300">
            {{ __("Ces cartes vous redirigent vers la configuration et le suivi de vos credits. Elles sont identiques a celles du tableau de bord.") }}
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        @include('in-application.admin.partials.menu-fast.stats.credit-team', ['team' => $team])
        @include('in-application.admin.partials.menu-fast.stats.configuration-team', ['team' => $team])

        @if (auth()->user()?->superadmin)
            <a
                href="{{ route('superadmin.tests.index') }}"
                class="flex items-center justify-between gap-4 rounded-2xl border border-emerald-500/40 bg-slate-900/40 p-6 transition hover:-translate-y-0.5 hover:border-emerald-400/60 hover:bg-slate-900/60"
            >
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-200">
                        {{ __('Superadmin') }}
                    </p>
                    <h3 class="mt-3 text-2xl font-semibold text-white">
                        {{ __('Tests de régression') }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-300">
                        {{ __('Lancez la batterie de tests unitaires dédiée pour vérifier le bon fonctionnement du site.') }}
                    </p>
                    <span class="mt-4 inline-flex items-center gap-2 rounded-full border border-emerald-400/50 bg-emerald-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-emerald-200">
                        {{ __('Tout joué') }}
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                    </span>
                </div>
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-200">
                    <span class="material-symbols-outlined text-2xl">science</span>
                </span>
            </a>
        @endif
    </div>
</div>
