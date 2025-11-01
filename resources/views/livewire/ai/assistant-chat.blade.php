<div class="flex h-full flex-col text-white">

@if ($hasTrainer)
    <section class="flex flex-1 flex-col min-h-0" wire:poll.4s="pollMessages">
        <div class="flex flex-1 flex-col min-h-0 px-4 pt-6 pb-4 sm:px-6 sm:pt-8 sm:pb-6">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-100">{{ $trainer['name'] }}</p>
                    @if (! empty($trainer['description']))
                        <p class="mt-1 text-xs text-slate-400">{{ $trainer['description'] }}</p>
                    @endif
                </div>
                <button
                    type="button"
                    wire:click="startNewConversation"
                    wire:loading.attr="disabled"
                    wire:target="startNewConversation,sendMessage"
                    class="inline-flex items-center gap-2 rounded-3xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-100 transition hover:border-emerald-400/80 hover:bg-emerald-500/20 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:cursor-not-allowed disabled:opacity-60">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    <span>{{ __('Nouvelle conversation') }}</span>
                </button>
            </div>
            <div id="assistantChatMessages" class="flex flex-1 flex-col space-y-4 overflow-y-auto pb-4 pr-2">
                @if ($awaitingResponse)
                    <div class="flex items-center gap-2 self-start rounded-3xl bg-emerald-400/10 px-4 py-2 text-sm text-emerald-200 shadow-lg shadow-emerald-900/50 backdrop-blur">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v2a6 6 0 0 0-6 6H4z"></path>
                        </svg>
                        <span>{{ __("L'assistant prepare sa reponse...") }}</span>
                    </div>
                @endif

                @forelse ($messages as $msg)
                    <div class="flex {{ $msg['role'] === 'assistant' ? 'justify-start' : 'justify-end' }}" wire:key="message-{{ $msg['id'] }}">
                        <div class="max-w-2xl rounded-3xl px-4 py-3 shadow-lg shadow-slate-950/50 backdrop-blur {{ $msg['role'] === 'assistant' ? 'bg-slate-900/80 text-slate-100' : 'bg-emerald-500/90 text-white' }}">
                            <div class="mb-1 flex items-center justify-between text-[11px] uppercase tracking-wider {{ $msg['role'] === 'assistant' ? 'text-slate-300' : 'text-emerald-50/80' }}">
                                <span class="font-semibold">{{ $msg['author'] }}</span>
                                <span>{{ $msg['created_at_human'] }}</span>
                            </div>
                            <div class="prose prose-invert prose-sm max-w-none break-words">
                                {!! nl2br(e($msg['content'])) !!}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-1 flex-col items-center justify-center text-center text-slate-300">
                        <div class="py-10">
                            <svg class="mx-auto mb-4 h-12 w-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p class="mb-2 text-lg font-medium text-white">{{ __('Commencez une conversation') }}</p>
                            <p class="text-sm text-slate-300">{{ __('Posez votre premiere question pour lancer le dialogue.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 rounded-3xl border border-white/10 bg-slate-950/70 p-4 shadow-xl shadow-slate-950/60 backdrop-blur">
                @if ($error)
                    <div class="mb-3 rounded-2xl border border-rose-300/40 bg-rose-400/10 px-3 py-2 text-sm text-rose-100">
                        <p>{{ $error }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3 sm:flex-row">
                    <textarea
                        wire:model.defer="message"
                        rows="2"
                        class="flex-1 resize-none rounded-3xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:opacity-50"
                        placeholder="{{ __('Saisissez votre message ici...') }}"
                        autocomplete="off"
                        aria-label="{{ __('Votre message') }}"></textarea>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-3xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:opacity-60"
                            wire:loading.attr="disabled"
                            wire:target="sendMessage">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m3 10 18-7-7 18-2-7-7-4Z"></path>
                        </svg>
                        <span>{{ __('Envoyer') }}</span>
                    </button>
                </form>

                @error('message')
                    <p class="text-sm text-rose-200">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>
    @once
        @push('scripts')
            <script>
                document.addEventListener('livewire:load', () => {
                    const container = document.getElementById('assistantChatMessages');
                    if (!container) {
                        return;
                    }

                    const scrollToBottom = () => {
                        window.requestAnimationFrame(() => {
                            container.scrollTop = container.scrollHeight;
                        });
                    };

                    scrollToBottom();

                    Livewire.hook('message.processed', (message, component) => {
                        if (component.fingerprint?.name === 'ai.assistant-chat') {
                            scrollToBottom();
                        }
                    });
                });
            </script>
        @endpush
    @endonce
@else
    <div class="flex flex-1 items-center justify-center px-6 pb-6">
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-white/5 px-8 py-10 text-center shadow-xl shadow-slate-950/40 backdrop-blur">
            @if ($error)
                <svg class="mx-auto mb-4 h-16 w-16 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-semibold text-white">{{ __('Assistant IA indisponible') }}</h3>
                <p class="text-sm text-rose-200">{{ $error }}</p>
            @else
                <svg class="mx-auto mb-4 h-16 w-16 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17 9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-semibold text-white">{{ __('Assistant IA indisponible') }}</h3>
                <p class="text-sm text-slate-300">{{ __("L'assistant IA n'est pas configure ou n'est pas disponible pour le moment.") }}</p>
            @endif
        </div>
    </div>
@endif
</div>
