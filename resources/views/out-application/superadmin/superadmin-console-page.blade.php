<x-admin.global-layout
    icon="terminal"
    :title="__('Console Superadmin')"
    :subtitle="__('Exécutez des commandes Artisan sélectionnées sans quitter ce tableau de bord sécurisé.')"
>
    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-100 bg-white/80 p-8 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/70 dark:ring-white/5">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                        {{ __('Outils Superadmin') }}
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Console rapide') }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Une interface Livewire pour déclencher les commandes d’entretien autorisées depuis votre session superadmin.') }}
                    </p>
                </div>
            </div>
        </section>

        <section>
            <livewire:superadmin.console />
        </section>
    </div>
</x-admin.global-layout>
