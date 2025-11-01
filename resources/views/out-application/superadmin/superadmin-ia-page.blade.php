<x-admin.global-layout
    icon="smart_toy"
    :title="__('Gestion des formateurs IA')"
    :subtitle="__('Les formateurs IA sont maintenant configurés dans config/ai.php')">
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="lg:col-span-2 space-y-6">

            {{-- Trainer management removed - trainers are now in config/ai.php --}}
            <div class="rounded-3xl border border-blue-200/70 bg-blue-50 p-6 text-blue-900 dark:border-blue-700/40 dark:bg-blue-900/20 dark:text-blue-100">
                <h3 class="text-lg font-semibold mb-2">{{ __('Nouvelle Architecture IA') }}</h3>
                <p class="text-sm mb-4">
                    {{ __('Les formateurs IA sont maintenant définis dans') }} <code class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">config/ai.php</code>
                </p>
                <p class="text-sm mb-2">{{ __('Formateurs disponibles:') }}</p>
                <ul class="list-disc list-inside text-sm space-y-1 ml-2">
                    @foreach(config('ai.trainers', []) as $slug => $trainer)
                        <li><strong>{{ $trainer['name'] ?? $slug }}</strong> - {{ $trainer['description'] ?? '' }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-3xl border border-emerald-200/70 bg-emerald-50 p-4 text-emerald-900 dark:border-emerald-700/40 dark:bg-emerald-900/20 dark:text-emerald-100">
                <p class="text-sm">
                    {{ __('Configuration du modèle par défaut:') }}
                    <strong>{{ config('ai.default_model') }}</strong>
                </p>
                <p class="mt-2 text-xs opacity-80">{{ __('Modifiez via config/ai.php ou .env (OLLAMA_DEFAULT_MODEL).') }}</p>
            </div>


        </section>

        <aside class="space-y-4">
            <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm dark:border-slate-700/70 dark:bg-slate-900">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ __('Bonnes pratiques') }}
                </h3>
                <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                    <li>{{ __('Modifiez les prompts système dans config/ai.php') }}</li>
                    <li>{{ __('Définissez des garde-fous pour chaque trainer') }}</li>
                    <li>{{ __('Utilisez le composant chat-box dans vos vues') }}</li>
                </ul>
            </div>

            {{-- Trainer tester component removed - trainers are config-based now --}}
            <div class="rounded-3xl border border-amber-200/70 bg-amber-50 p-4 text-amber-900 dark:border-amber-700/40 dark:bg-amber-900/20 dark:text-amber-100">
                <p class="text-sm">
                    {{ __('Pour tester un trainer, utilisez le composant chat-box dans vos vues:') }}
                </p>
                <code class="block mt-2 text-xs bg-white dark:bg-slate-800 p-2 rounded">
                    &lt;livewire:chat-box trainer="default" /&gt;
                </code>
            </div>

        </aside>
    </div>
</x-admin.global-layout>
