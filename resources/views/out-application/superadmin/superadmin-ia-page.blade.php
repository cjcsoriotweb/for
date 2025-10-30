<x-admin.global-layout
    icon="smart_toy"
    :title="__('Gestion des formateurs IA')"
    :subtitle="__('Creez, testez et ajustez les assistants relies a vos formations.')">
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="lg:col-span-2 space-y-6">

            <livewire:ai.trainer-manager />

            <div class="rounded-3xl border border-emerald-200/70 bg-emerald-50 p-4 text-emerald-900 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-100">
                <p class="text-sm">
                    {{ __('Configuration du modele par defaut:') }}
                    <strong>{{ config('ai.default_driver') }}</strong>
                    — {{ __('modele') }}
                    <strong>{{ config('ai.drivers.'.config('ai.default_driver').'.model') }}</strong>
                </p>
                <p class="mt-2 text-xs opacity-80">{{ __('Modifiez via config/ai.php ou .env (AI_DRIVER, AI_OLLAMA_MODEL).') }}</p>
            </div>


        </section>

        <aside class="space-y-4">
            <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ __('Bonnes pratiques') }}
                </h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                    <li>{{ __('Ciblez le prompt pour chaque formation afin de rester precis.') }}</li>
                    <li>{{ __('Sauvegardez un formateur actif par defaut pour couvrir les questions generales.') }}</li>
                    <li>{{ __('Controlez le ton et les regles pedagogiques pour maintenir la qualite.') }}</li>
                </ul>
            </div>

    
            <livewire:ai.trainer-tester />
            <livewire:ai.chat-widget :show-launcher="false" />

        </aside>
    </div>
</x-admin.global-layout>
