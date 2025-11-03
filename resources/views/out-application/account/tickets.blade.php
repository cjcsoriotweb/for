<x-app-layout>
    <div class="space-y-8">
        <section
            class="relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white/80 px-8 py-10 shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/50 dark:shadow-none sm:px-12">
            <div class="absolute right-[-60px] top-[-60px] h-48 w-48 rounded-full bg-blue-500/20 blur-3xl dark:bg-blue-400/10"></div>
            <div class="relative grid gap-6 md:grid-cols-[auto,160px] md:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-blue-500 dark:text-blue-300">
                        {{ __('Assistance Evolubat') }}
                    </p>
                    <h1 class="mt-4 max-w-2xl text-3xl font-bold text-slate-900 dark:text-white">
                        {{ __('Gérez vos tickets de support') }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-base text-slate-600 dark:text-slate-300">
                        {{ __('Retrouvez vos demandes, suivez les réponses du support et ajoutez des informations complémentaires si nécessaire.') }}
                    </p>
                </div>
                <div class="hidden md:flex md:justify-end">
                    <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/40">
                        <span class="material-symbols-outlined text-4xl">confirmation_number</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200/80 shadow-2xl shadow-slate-200/60 dark:border-slate-700/50 dark:shadow-slate-900/50">
            <livewire:support.ticket-reporter />
        </section>
    </div>
</x-app-layout>
