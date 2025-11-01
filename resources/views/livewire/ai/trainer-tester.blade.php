<div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('Tester un formateur IA') }}
        </h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __("Envoyez un message de test et verifiez la reponse generee par le modele selectionne.") }}
        </p>
    </header>

    <form wire:submit.prevent="testTrainer" class="space-y-4">
        <div class="space-y-2">
            <label for="trainerId" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                {{ __('Formateur IA') }}
            </label>
            <select
                id="trainerId"
                wire:model="trainerId"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
            >
                @foreach ($trainers as $trainer)
                    <option value="{{ $trainer['id'] }}">
                        {{ $trainer['name'] }} &mdash; {{ strtoupper($trainer['model']) }}
                    </option>
                @endforeach
            </select>
            @error('trainerId')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
            @if (!empty($trainers) && $trainerId)
                @php
                    $selected = collect($trainers)->firstWhere('id', (int) $trainerId);
                @endphp
                @if ($selected && $selected['description'])
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $selected['description'] }}
                    </p>
                @endif
            @endif
        </div>

        <div class="space-y-2">
            <label for="tester-message" class="text-sm font-medium text-slate-700 dark:text-slate-200">
                {{ __('Message de test') }}
            </label>
            <textarea
                id="tester-message"
                wire:model.defer="message"
                rows="4"
                class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                placeholder="{{ __('Posez une question pour ce formateur...') }}"
            ></textarea>
            @error('message')
                <p class="text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {{ __("Assurez-vous que le serveur Ollama indique dans OLLAMA_BASE_URL est joignable.") }}
            </p>
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200"
            >
                <span class="material-symbols-outlined text-base">bolt</span>
                {{ __('Lancer le test') }}
            </button>
        </div>
    </form>

    @if ($error)
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200">
            {{ $error }}
        </div>
    @endif

    @if ($response)
        <div class="space-y-2 rounded-xl border border-emerald-200 bg-emerald-50/70 px-4 py-3 text-sm text-slate-800 dark:border-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-100">
            <div class="text-xs uppercase tracking-wide text-emerald-500 dark:text-emerald-300">
                {{ __('Reponse du modele') }}
            </div>
            <div class="prose prose-sm max-w-none text-slate-700 dark:prose-invert dark:text-emerald-50">
                {!! nl2br(e($response)) !!}
            </div>
            @if ($usage)
                <div class="text-[11px] text-slate-400 dark:text-emerald-200/70">
                    {{ __('Tokens utilises : prompt :prompt, completion :completion', ['prompt' => $usage['prompt_tokens'] ?? '?', 'completion' => $usage['completion_tokens'] ?? '?']) }}
                </div>
            @endif
        </div>
    @endif
</div>
