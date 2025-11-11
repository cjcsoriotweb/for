
<div class="relative min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 px-4 py-12 text-white">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute inset-y-0 left-1/2 h-[420px] w-[420px] -translate-x-1/2 rounded-full bg-primary/25 blur-[160px]"></div>
    </div>

    <div class="relative mx-auto flex w-full max-w-3xl flex-col gap-6">
        <section class="rounded-[32px] border border-white/10 bg-white/5 p-8 text-center shadow-2xl backdrop-blur-2xl">
            <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-primary/20 text-primary">
                <span class="material-symbols-outlined text-3xl">military_tech</span>
            </div>
            <p class="mt-4 text-xs font-semibold uppercase tracking-[0.4em] text-white/60">Vidéo terminée</p>
            <h1 class="mt-3 text-3xl font-bold leading-tight text-white md:text-4xl">Bravo, mission accomplie !</h1>
        
            <div class="mt-6 rounded-2xl border border-white/10 bg-black/25 p-6 text-left">
         
                <div class="mt-3 h-2 rounded-full bg-white/10">
                    <div class="h-full rounded-full bg-primary" style="width: {{ $currentTime }}%;"></div>
                </div>
                <div class="mt-4 grid gap-4 text-left sm:grid-cols-3">
                    <div class="rounded-xl bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-widest text-white/50">Durée</p>
                        <p class="mt-1 text-xl font-semibold text-white">{{ $currentTimePlayer }}s</p>
                    </div>
                    <div class="rounded-xl bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-widest text-white/50">Statut</p>
                        <p class="mt-1 inline-flex items-center gap-1 text-xl font-semibold text-emerald-400">
                            <span class="material-symbols-outlined text-2xl">check_circle</span>
                            Terminé
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <button type="button" wire:click="replay"
                    class="flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-2xl border border-primary/50 bg-transparent text-base font-semibold text-primary transition hover:bg-primary/10">
                    <span class="material-symbols-outlined">replay</span>
                    Revoir la vidéo
                </button>
                <button wire:click="completed" type="button"
                    class="flex h-12 w-full flex-1 items-center justify-center gap-2 rounded-2xl bg-primary text-base font-semibold text-white transition hover:bg-primary/90">
                    Continuer
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </section>
    </div>
</div>
