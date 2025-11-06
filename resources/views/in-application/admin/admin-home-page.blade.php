<x-app-layout :team="$team" icon="shield_person" :title="__('Tableau de bord administrateur')" :subtitle="__('Pilotez votre plateforme de formation avec une vue claire sur vos actions clés.')">

    <div class="relative pb-16">
        <div
            class="absolute inset-x-0 top-0 -z-10 h-72 bg-gradient-to-br from-slate-950 via-indigo-900 to-slate-900 opacity-80 blur-3xl">
        </div>
        <x-admin.admin-menu-fast :team="$team" />
    </div>


    


    <section id="bascule" class="space-y-6">
        <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400">
            <span class="material-symbols-outlined text-lg">tune</span>
            <h2 class="text-xs font-semibold uppercase tracking-[0.35em]">
                {{ __('Actions contextuelles') }}
            </h2>
        </div>
        <div
            class="rounded-3xl border border-slate-200/70 bg-white/70 p-6 shadow-sm shadow-slate-200/60 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/70 dark:shadow-none">
            @include('in-application.admin.partials.configuration')
        </div>
    </section>



</x-app-layout>
