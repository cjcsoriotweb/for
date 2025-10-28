<div class="w-full max-w-5xl">
    <div class="rounded-2xl border border-slate-700/40 bg-slate-900/60 p-8 text-center shadow-lg shadow-slate-900/30">
        <h2 class="text-3xl font-semibold text-white">
            {{ __("Comprendre les indicateurs principaux") }}
        </h2>
        <p class="mt-4 text-base text-slate-300">
            {{ __("Ces deux cartes sont les memes que sur la page reelle. Utilise-les pour surveiller le volume d'utilisateurs et la visibilite de ton catalogue.") }}
        </p>
    </div>

    <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2">
        @include('clean.admin.partials.menu-fast.stats.usersStats', [
            'team' => $team,
            'totalUsers' => $totalUsers ?? 0,
            'usersProgressWidth' => $usersProgressWidth ?? 0,
        ])

        @include('clean.admin.partials.menu-fast.stats.formationsStats', [
            'team' => $team,
            'activeCount' => $activeCount ?? 0,
            'totalCount' => $totalCount ?? 0,
            'visiblePercentage' => $visiblePercentage ?? 0,
            'formationsProgressWidth' => $formationsProgressWidth ?? 0,
        ])
    </div>
</div>

