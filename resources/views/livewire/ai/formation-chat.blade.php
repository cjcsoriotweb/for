<div class="{{ $hasTrainer ? 'fixed bottom-6 right-28 z-40' : 'hidden' }}">
    @if ($hasTrainer)
        <button
            type="button"
            wire:click="toggle"
            class="flex items-center justify-center rounded-full bg-emerald-500 text-white shadow-xl transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300 h-14 w-14"
            aria-haspopup="dialog"
            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
            aria-controls="ai-formation-chat"
        >
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 3v2m6.36.64-1.42 1.42M21 12h-2m-.64 6.36-1.42-1.42M12 19v2m-6.36-.64 1.42-1.42M5 12H3m2.64-6.36 1.42 1.42M12 8a4 4 0 1 0 4 4 4 4 0 0 0-4-4Z" />
            </svg>
        </button>

        @if ($isOpen)
            <section
                id="ai-formation-chat"
                class="mt-4 w-[26rem] max-w-[calc(100vw-2rem)] overflow-hidden rounded-3xl border border-emerald-200 bg-white shadow-2xl dark:border-emerald-700 dark:bg-slate-900"
            >
                <header class="flex items-start justify-between border-b border-emerald-100 bg-emerald-50 px-4 py-3 dark:border-emerald-800/60 dark:bg-emerald-900/40">
                    <div>
                        <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-200">
                            {{ $trainerName ?: __('Formateur IA') }}
                        </p>
                        @if ($trainerDescription)
                            <p class="mt-0.5 text-xs text-emerald-500/90 dark:text-emerald-200/70">
                                {{ $trainerDescription }}
                            </p>
                        @else
                            <p class="mt-0.5 text-xs text-emerald-500/90 dark:text-emerald-200/70">
                                {{ __('Discutez avec votre formateur IA a propos de cette formation.') }}
                            </p>
                        @endif
                    </div>
                    <button
                        type="button"
                        wire:click="toggle"
                        class="rounded-full p-1 text-emerald-500 transition hover:text-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:text-emerald-200 dark:hover:text-emerald-100"
                        title="{{ __('Fermer') }}"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 18 18 6" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </header>

                <div class="flex h-[28rem] flex-col bg-white dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-4 py-2 text-xs text-slate-500 dark:border-slate-800 dark:text-slate-400">
                        @if ($formationTitle)
                            {{ __('Formation : :title', ['title' => $formationTitle]) }}
                        @else
                            {{ __('Session de discussion generique.') }}
                        @endif
                    </div>

                    <div class="flex-1 overflow-y-auto px-4 py-3">
                        <div class="flex flex-col-reverse gap-3">
                            @if ($awaitingResponse)
                                <div class="flex items-center gap-2 rounded-2xl border border-dashed border-emerald-200 bg-emerald-50/60 px-3 py-2 text-xs font-medium text-emerald-600 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">
                                    <svg class="h-4 w-4 animate-spin text-emerald-500 dark:text-emerald-200" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z"></path>
                                    </svg>
                                    <span>{{ __('Le formateur redige sa reponse...') }}</span>
                                </div>
                            @endif

                            @foreach ($messages as $message)
                                <article class="flex flex-col gap-1 rounded-2xl border border-slate-100 px-3 py-2 text-sm text-slate-700 dark:border-slate-700 dark:text-slate-200 {{ $message['role'] === 'assistant' ? 'bg-emerald-50/70 dark:bg-emerald-900/20' : 'bg-white dark:bg-slate-900/40' }}">
                                    <div class="flex items-center justify-between text-[11px] uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        <span>{{ $message['author'] }}</span>
                                        <span>{{ $message['created_at_human'] }}</span>
                                    </div>
                                    <div class="prose prose-sm max-w-none text-slate-700 dark:prose-invert dark:text-slate-100">
                                        {!! nl2br(e($message['content'])) !!}
                                    </div>
                                </article>
                            @endforeach

                            @if (empty($messages))
                                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/80 p-4 text-center text-xs text-slate-500 dark:border-slate-700 dark:bg-slate-800/30 dark:text-slate-400">
                                    {{ __('Posez votre premiere question pour commencer la discussion.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-slate-100 bg-slate-50/60 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/60">
                        @if ($error)
                            <p class="mb-2 rounded-xl bg-red-100 px-3 py-2 text-xs text-red-700 dark:bg-red-900/40 dark:text-red-200">
                                {{ $error }}
                            </p>
                        @endif

                        <form wire:submit.prevent="sendMessage" class="space-y-2">
                            <textarea
                                wire:model.defer="message"
                                rows="2"
                                class="w-full rounded-xl border border-emerald-200 px-3 py-2 text-sm text-slate-700 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 dark:border-emerald-800 dark:bg-slate-900 dark:text-slate-100 disabled:cursor-not-allowed disabled:opacity-70"
                                placeholder="{{ __('Ecrivez votre question ici...') }}"
                                @disabled($awaitingResponse)
                            ></textarea>
                            @error('message')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    {{ __('Les reponses sont generees automatiquement .') }} {{ config('app.name')}}
                                </p>
                                <button
                                    type="submit"
                                    class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200 disabled:cursor-not-allowed disabled:opacity-70"
                                    @disabled($awaitingResponse)
                                >
                                    @if ($awaitingResponse)
                                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M21 12a9 9 0 1 1-9-9" />
                                        </svg>
                                        {{ __('En cours...') }}
                                    @else
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="m3 10 18-7-7 18-2-7-7-4Z" />
                                        </svg>
                                        {{ __('Envoyer') }}
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        @endif
    @endif
</div>
