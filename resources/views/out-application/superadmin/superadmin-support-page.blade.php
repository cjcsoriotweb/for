<x-admin.global-layout
    icon="support_agent"
    :title="__('Support et tickets')"
    :subtitle="__('Traitez les demandes utilisateurs et centralisez les réponses.')"
>
    <section class="space-y-8">
        <div class="rounded-3xl border border-slate-100 bg-white/90 p-8 shadow-lg ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center">
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                        {{ __('Centre de support') }}
                    </p>
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white">
                        {{ __('Résolvez rapidement les demandes impactantes') }}
                    </h1>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Filtrez par priorités, collaborez avec les équipes pédagogiques et assurez un suivi humain avec chaque ticket ouvert.') }}
                    </p>
                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('superadmin.support.index') }}#inbox"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900">
                            {{ __('Revenir aux tickets') }}
                        </a>
                        <a href="#actions"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-slate-400 hover:text-slate-900 dark:border-slate-700 dark:text-slate-300 dark:hover:border-slate-500 dark:hover:text-white">
                            {{ __('Voir les recommandations') }}
                        </a>
                    </div>
                </div>
                <div class="flex-1 rounded-3xl bg-gradient-to-br from-indigo-900 to-slate-900 p-6 text-white shadow-lg shadow-indigo-900/50">
                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-200">
                        {{ __('En un coup d’œil') }}
                    </p>
                    <p class="mt-4 text-4xl font-semibold leading-tight">
                        {{ __('Flux humain et IA alignés') }}
                    </p>
                    <p class="mt-3 text-sm text-indigo-100">
                        {{ __('Chaque réponse peut enrichir la base de connaissances et déclencher des alertes vers les bons interlocuteurs.') }}
                    </p>
                </div>
            </div>
        </div>

        <div id="actions" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['title' => __('Tickets ouverts'), 'description' => __('Classez par urgence, équipe ou élève et ajoutez un rappel.'), 'icon' => 'priority_high'],
                ['title' => __('Réponses standardisées'), 'description' => __('Utilisez les modèles et adaptez-les en quelques secondes.'), 'icon' => 'text_snippet'],
                ['title' => __('Escalade rapide'), 'description' => __('Alertes support + expert, suivi transparent pour les usagers.'), 'icon' => 'support'],
            ] as $card)
                <div class="rounded-3xl border border-slate-200 bg-white/80 p-5 shadow-sm ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/70">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-xl text-indigo-500 dark:text-indigo-300">
                            {{ $card['icon'] }}
                        </span>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $card['title'] }}
                        </h3>
                    </div>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">
                        {{ $card['description'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50/60 p-6 dark:border-slate-700 dark:bg-slate-900/50">
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                {{ __('Processus de traitement') }}
            </p>
            <ol class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-400">
                <li>• {{ __('Validez la description, ajoutez les pièces jointes et reliez le ticket à une organisation ou un élève.') }}</li>
                <li>• {{ __('Assignez une priorité, ajoutez votre note interne et planifiez une relance si nécessaire.') }}</li>
                <li>• {{ __('Suivez le niveau de satisfaction et fermez le ticket seulement après validation du demandeur.') }}</li>
            </ol>
        </div>

        <div class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-lg ring-1 ring-black/5 dark:border-slate-800 dark:bg-slate-900/70">
            <livewire:support.ticket-inbox />
        </div>
    </section>
</x-admin.global-layout>
