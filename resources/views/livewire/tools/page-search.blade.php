<div id="page-search" class="fixed bottom-6 right-6 z-40">
    @if ($isOpen)
        <div class="w-[34rem] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-start justify-between border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-800">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Recherche de page') }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Trouvez rapidement une page via l\'iframe de recherche.') }}</p>
                </div>
                <button type="button" wire:click="toggle" class="rounded-full p-1 text-slate-500 transition hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:text-slate-300 dark:hover:text-white" title="{{ __('Fermer') }}">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 18 18 6" />
                        <path d="M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="h-[28rem]">
                <iframe src="{{ $src }}" class="h-full w-full" loading="lazy"></iframe>
            </div>
        </div>
    @endif
</div>

