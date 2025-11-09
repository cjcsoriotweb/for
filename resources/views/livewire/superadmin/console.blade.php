<div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
    <div class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/80 dark:ring-white/5">
        <header class="space-y-2">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                {{ __('Console') }}
            </p>
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">
                {{ __('Exécuter une commande artisan') }}
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('Tapez une commande autorisée ou choisissez une option pour la remplir automatiquement.') }}
            </p>
        </header>

        <form wire:submit.prevent="runCommand" class="mt-6 space-y-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-200" for="command">
                    {{ __('Commande') }}
                </label>
                <input
                    id="command"
                    type="text"
                    wire:model.defer="command"
                    placeholder="{{ __('Exemple : cache:clear') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 disabled:cursor-not-allowed dark:border-slate-800 dark:bg-slate-900 dark:text-white dark:focus:border-indigo-400"
                />
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ __('Les commandes disponibles sont listées ci-dessous. Vous pouvez cliquer pour les insérer.') }}
                </p>
            </div>

            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                        {{ __('Commandes autorisées') }}
                    </span>
                    <span class="text-xs text-slate-400">
                        {{ __('Cliquez pour préremplir') }}
                    </span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach ($availableCommands as $value => $label)
                    <button
                        type="button"
                        wire:click="runNamedCommand('{{ $value }}')"
                            class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-indigo-300 hover:bg-white dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-100 dark:hover:border-indigo-400"
                        >
                            <span class="block text-[0.65rem] uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500">
                                {{ __('Cmd') }}
                            </span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ $value }}
                            </span>
                            <p class="text-[0.65rem] text-slate-500 dark:text-slate-400">
                                {{ $label }}
                            </p>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="runCommand"
                    class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:bg-indigo-400"
                >
                    {{ __('Exécuter la commande') }}
                </button>
                <span class="text-xs text-slate-500 dark:text-slate-400" wire:loading.remove>
                    {{ __('Dernière action prête à être exécutée.') }}
                </span>
                <span class="text-xs text-indigo-600 dark:text-indigo-300" wire:loading.delay>
                    {{ __('Exécution en cours…') }}
                </span>
            </div>
        </form>
    </div>

    <div class="rounded-3xl border border-slate-100 bg-white/80 p-6 shadow-lg ring-1 ring-black/5 transition dark:border-slate-800 dark:bg-slate-900/80 dark:ring-white/5">
        <header class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500 dark:text-slate-400">
                    {{ __('Historique') }}
                </p>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                    {{ __('Derniers résultats') }}
                </h3>
            </div>
            <span class="text-xs text-slate-400 dark:text-slate-500">
                {{ count($logs) }} / 12
            </span>
        </header>

        <div class="mt-6 space-y-4 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-900/70">
            @forelse ($logs as $index => $log)
                <article
                    wire:key="console-log-{{ $log['timestamp'] }}-{{ $index }}"
                    class="space-y-2 rounded-2xl border border-slate-200 bg-white/70 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/60"
                >
                    <div class="flex items-center justify-between text-xs uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">
                        <span class="font-semibold text-slate-700 dark:text-white">
                            {{ $log['command'] }}
                        </span>
                        <span>
                            {{ $log['timestamp'] }}
                        </span>
                    </div>
                    <pre class="overflow-x-auto text-[0.75rem] leading-relaxed text-slate-600 dark:text-slate-200">{{ $log['output'] }}</pre>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">
                        {{ $log['success'] ? __('Succès') : __('Échec') }}
                    </p>
                </article>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 bg-white/70 p-6 text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-400">
                    {{ __('Aucune commande exécutée pour le moment.') }}
                </p>
            @endforelse
        </div>
    </div>
</div>
