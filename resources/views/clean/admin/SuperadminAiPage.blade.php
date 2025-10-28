<x-admin.global-layout
    icon="smart_toy"
    :title="__('Gestion des formateurs IA')"
    :subtitle="__('Creez, testez et ajustez les assistants relies a vos formations.')">
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="lg:col-span-2 space-y-6">

            <livewire:ai.trainer-manager />


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

        </aside>
    </div>
</x-admin.global-layout>