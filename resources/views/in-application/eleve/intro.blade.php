<x-guest-layout>
    @push('head')
        <title>{{ $team->name ?? config('app.name') }} · Accès apprenant</title>
    @endpush

    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-900/80"></div>

        <div class="relative flex min-h-screen flex-col items-center justify-center gap-4 px-6 text-center">
            <p class="text-xs uppercase tracking-[0.4em] text-white/40">Chargement</p>
            <h1 class="text-4xl font-semibold text-white sm:text-5xl">{{ $team->name }}</h1>
            <p class="max-w-xl text-sm text-white/60">
                Préparation de votre espace <span class="font-semibold">apprenant</span>. L'animation se termine juste avant l'ouverture complète.
            </p>
        </div>

        @include('components.eleve.home.identity-panel', ['team' => $team])
    </div>

    @push('scripts')
        <script>
            setTimeout(() => {
                window.location.href = "{{ route('eleve.index', $team) }}";
            }, 6000);
        </script>
    @endpush
</x-guest-layout>
