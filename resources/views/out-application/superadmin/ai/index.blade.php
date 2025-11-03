<x-admin.global-layout
    icon="smart_toy"
    :title="__('Gestion des assistants IA')"
    :subtitle="__('Choisissez un espace pour configurer les profils IA et leurs usages dans la plateforme.')"
>
    <div class="space-y-10">
        <div class="rounded-3xl border border-slate-200/70 bg-white p-8 shadow-xl dark:border-slate-700/60 dark:bg-slate-900">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Apercu global') }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ __('Retrouvez ici l\'ensemble des outils lies aux assistants IA. Accedez rapidement aux profils et aux categories pour organiser vos formations.') }}
                    </p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                    <span class="material-symbols-outlined text-sm">smart_toy</span>
                    {{ __('Console IA') }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <a
                href="{{ route('superadmin.ai.trainers') }}"
                class="group flex h-full flex-col justify-between rounded-3xl border border-slate-200/70 bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-2xl dark:border-slate-700/70 dark:bg-slate-900 dark:hover:border-indigo-500/50"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 transition group-hover:text-indigo-600 dark:text-slate-400 dark:group-hover:text-indigo-300">
                            {{ __('Profils IA') }}
                        </p>
                        <h3 class="mt-6 text-2xl font-semibold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-200">
                            {{ __('Formateurs & assistants') }}
                        </h3>
                        <p class="mt-3 text-sm text-slate-500 transition group-hover:text-slate-600 dark:text-slate-400 dark:group-hover:text-slate-300">
                            {{ __('Creez, organisez et pilotez chaque assistant IA disponible pour les equipes et formations.') }}
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-3xl text-indigo-500 transition group-hover:scale-110">
                        psychology
                    </span>
                </div>
                <span class="mt-8 inline-flex items-center justify-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition group-hover:border-indigo-300 group-hover:bg-indigo-100 group-hover:text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-200 dark:group-hover:border-indigo-400/40 dark:group-hover:bg-indigo-500/20">
                    {{ __('Gerer les assistants IA') }}
                    <span class="material-symbols-outlined text-sm">arrow_outward</span>
                </span>
            </a>

            <a
                href="{{ route('superadmin.ai.categories') }}"
                class="group flex h-full flex-col justify-between rounded-3xl border border-slate-200/70 bg-white p-8 shadow-lg transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-2xl dark:border-slate-700/70 dark:bg-slate-900 dark:hover:border-indigo-500/50"
            >
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 transition group-hover:text-indigo-600 dark:text-slate-400 dark:group-hover:text-indigo-300">
                            {{ __('Categories IA') }}
                        </p>
                        <h3 class="mt-6 text-2xl font-semibold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-200">
                            {{ __('Organiser les formations') }}
                        </h3>
                        <p class="mt-3 text-sm text-slate-500 transition group-hover:text-slate-600 dark:text-slate-400 dark:group-hover:text-slate-300">
                            {{ __('Definissez des categories et reliez-les aux assistants IA adaptes pour guider les formateurs.') }}
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-3xl text-indigo-500 transition group-hover:scale-110">
                        category
                    </span>
                </div>
                <span class="mt-8 inline-flex items-center justify-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition group-hover:border-indigo-300 group-hover:bg-indigo-100 group-hover:text-indigo-700 dark:border-indigo-500/30 dark:bg-indigo-500/10 dark:text-indigo-200 dark:group-hover:border-indigo-400/40 dark:group-hover:bg-indigo-500/20">
                    {{ __('Gerer les categories IA') }}
                    <span class="material-symbols-outlined text-sm">arrow_outward</span>
                </span>
            </a>
        </div>
    </div>
</x-admin.global-layout>
