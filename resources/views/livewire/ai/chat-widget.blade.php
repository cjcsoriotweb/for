<div id="ai-chat" class="fixed bottom-6 right-24 z-40">
    @if ($showLauncher)
        <button wire:click="toggle" type="button"
            class="flex items-center justify-center rounded-full bg-emerald-600 text-white shadow-lg transition hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 h-14 w-14"
            aria-haspopup="dialog" aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="ai-chat-panel">
            <span class="material-symbols-outlined">smart_toy</span>
        </button>
    @endif

    @if ($isOpen)
        <div id="ai-chat-panel"
            class="mt-4 w-[28rem] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-start justify-between border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-800">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $mode === 'tutor' ? __('Professeur IA') : __('Assistant IA') }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $mode === 'tutor' ? __('Posez vos questions sur le cours.') : __('Besoin d\'aide ? Discutez avec l\'assistant.') }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" wire:click="toggle" class="rounded-full p-1 text-slate-500 transition hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 dark:text-slate-300 dark:hover:text-white" title="{{ __('Fermer') }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 18 18 6" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-4 text-sm text-slate-600 dark:text-slate-300">
                <p class="mb-3">{{ __('Modèle:') }} {{ config('ai.drivers.'.config('ai.default_driver').'.model') }}</p>
                <p class="mb-4">{{ __('Zone de chat à implémenter: messages, entrée et appels au service IA.') }}</p>
                <p class="italic">{{ __('Ce composant est prêt pour intégrer votre service IA existant (Ollama par défaut).') }}</p>
            </div>
        </div>
    @endif

    <script>
        (function () {
            if (window.__aiWidgetBound) { return; }
            window.__aiWidgetBound = true;

            const ensureLivewire = () => typeof window.Livewire !== 'undefined' && window.Livewire.find;
            const getId = () => {
                try { return @this.__instance.id; } catch (e) { return null; }
            };

            const callOn = (method) => {
                const id = getId();
                if (!id || !ensureLivewire()) return;
                try { window.Livewire.find(id).call(method); } catch (e) {}
            };

            window.addEventListener('assistant-toggle', () => callOn('onAssistantToggle'));
            window.addEventListener('tutor-toggle', () => callOn('onTutorToggle'));
        })();
    </script>
</div>
