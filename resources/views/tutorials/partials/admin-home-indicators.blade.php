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
        @include('in-application.admin.partials.menu-fast.stats.creditTeam', ['team' => $team])
        @include('in-application.admin.partials.menu-fast.stats.configurationTeam', ['team' => $team])
    </div>
</div>

