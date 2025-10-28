<x-admin.global-layout
    icon="smart_toy"
    :title="__('Gestion des formateurs IA')"
    :subtitle="__('Creez, testez et ajustez les assistants relies a vos formations.')"
>
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="lg:col-span-2 space-y-6">
            <div class="rounded-3xl border border-indigo-200/60 bg-indigo-50/70 p-6 shadow-sm dark:border-indigo-500/30 dark:bg-indigo-900/30">
                <h2 class="text-base font-semibold text-indigo-700 dark:text-indigo-200">
                    {{ __('Creer et gerer vos formateurs IA') }}
                </h2>
                <p class="mt-2 text-sm text-indigo-600/90 dark:text-indigo-200/70">
                    {{ __('Ajoutez des profils, definissez le formateur par defaut et adaptez chaque prompt a vos formations.') }}
                </p>
            </div>

            <livewire:ai.trainer-manager />

            <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
                <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100">
                    {{ __('Tester un formateur IA') }}
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Validez les reponses avant de mettre le formateur a disposition des apprenants.') }}
                </p>
                <div class="mt-4">
                    <livewire:ai.trainer-tester />
                </div>
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

            <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ __('Configuration requise') }}
                </h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ __('Assurez-vous que la variable OPENAI_API_KEY est definie et que les migrations IA sont appliquees.') }}
                </p>
            </div>
        </aside>
    </div>
</x-admin.global-layout>
