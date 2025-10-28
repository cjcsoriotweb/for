<div class="w-full max-w-5xl space-y-8">
    <div class="rounded-2xl border border-slate-700/40 bg-slate-900/60 p-8 text-center shadow-lg shadow-slate-900/30">
        <h2 class="text-3xl font-semibold text-white">
            {{ __("Basculer rapidement entre vos espaces") }}
        </h2>
        <p class="mt-4 text-base text-slate-300">
            {{ __("Ce bloc correspond exactement au bandeau d'actions contextuelles. Il propose des liens directs vers l'espace eleve et l'espace organisateur.") }}
        </p>
    </div>

    @include('clean.admin.partials.configuration', ['team' => $team])
</div>
