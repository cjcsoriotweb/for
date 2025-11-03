<x-app-layout>
    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-200/70 bg-white/80 px-8 py-10 shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/50 dark:shadow-none sm:px-12">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-blue-500 dark:text-blue-300">
                        {{ __('Assistance Evolubat') }}
                    </p>
                    <h1 class="mt-4 text-3xl font-bold text-slate-900 dark:text-white">
                        {{ __('Creer un nouveau ticket') }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-base text-slate-600 dark:text-slate-300">
                        {{ __('Expliquez votre probleme ou posez votre question. Notre equipe vous repondra des que possible.') }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('user.tickets') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-500 dark:hover:text-blue-300">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        {{ __('Retour a mes tickets') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200/80 shadow-2xl shadow-slate-200/60 dark:border-slate-700/50 dark:shadow-slate-900/50">
            <livewire:support.ticket-reporter mode="create" />
        </section>
    </div>
</x-app-layout>
