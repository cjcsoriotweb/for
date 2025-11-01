<div class="flex h-full flex-col text-white">

@if ($hasTrainer)
    <section class="flex flex-1 flex-col min-h-0 px-6 py-6 " wire:poll.4s="pollMessages">
        <div class="flex flex-1 flex-col min-h-0 overflow-hidden rounded-2xl border border-white/10 bg-white/5 shadow-xl shadow-slate-950/40 backdrop-blur">
            <div class="border-b border-white/10 bg-slate-950/40 px-6 py-4">
                @if ($error)
                    <div class="mb-3 rounded-lg border border-rose-300/40 bg-rose-400/10 px-3 py-2 text-sm text-rose-100">
                        <p>{{ $error }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3 sm:flex-row">
                    <textarea
                        wire:model.defer="message"
                        rows="2"
                        class="bg-slate-950 flex-1 resize-none rounded-xl border border-white/10 bg-slate-950/60 px-3 py-3 text-sm text-white placeholder:text-slate-400 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:opacity-50"
                        placeholder="{{ __('Saisissez votre message ici...') }}"
                        autocomplete="off"
                        aria-label="{{ __('Votre message') }}"></textarea>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 disabled:opacity-60"
                            wire:loading.attr="disabled"
                            wire:target="sendMessage">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="m3 10 18-7-7 18-2-7-7-4Z"></path>
                        </svg>
                        <span>{{ __('Envoyer') }}</span>
                    </button>
                </form>

                @error('message')
                    <p class="mt-2 text-sm text-rose-200">{{ $message }}</p>
                @enderror
            </div>

            <div id="assistantChatMessages" class="flex flex-1 flex-col space-y-4 overflow-y-auto px-6 py-6">
                @if ($awaitingResponse)
                    <div class="flex items-center gap-2 rounded-lg border border-emerald-300/40 bg-emerald-400/10 px-3 py-2 text-sm text-emerald-200">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v2a6 6 0 0 0-6 6H4z"></path>
                        </svg>
                        <span>{{ __("L'assistant prepare sa reponse...") }}</span>
                    </div>
                @endif

                @forelse ($messages as $msg)
                    <div class="bg-slate-950 flex {{ $msg['role'] === 'assistant' ? 'justify-start' : 'justify-end' }}" wire:key="message-{{ $msg['id'] }}">
                        <div class="max-w-2xl rounded-xl border px-4 py-3 shadow-sm {{ $msg['role'] === 'assistant' ? 'border-white/15 bg-white/10 text-slate-100' : 'border-emerald-400/50 bg-emerald-500/80 text-white' }}">
                            <div class="mb-1 flex items-center justify-between text-xs {{ $msg['role'] === 'assistant' ? 'text-slate-300' : 'text-white/80' }}">
                                <span class="font-medium">{{ $msg['author'] }}</span>
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
