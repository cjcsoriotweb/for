<div class="min-h-screen w-full bg-slate-950 text-slate-100" wire:poll.1s="fetchMessage">
    <div class="mx-auto flex h-screen max-w-4xl flex-col px-4 py-6 sm:px-6 lg:px-0">
        <div
            class="flex h-full flex-col overflow-hidden rounded-3xl border border-white/10 bg-slate-900/70 shadow-2xl shadow-slate-900/40 backdrop-blur">
            <header
                class="flex items-center gap-4 border-b border-white/5 bg-gradient-to-r from-slate-900/80 via-slate-900/30 to-transparent px-6 py-5">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-xl font-semibold text-emerald-300">
                    EB
                </div>
                <div class="flex-1">
                    <p class="text-lg font-semibold text-white">
                        EvoBot Assistant
                    </p>
                    <p class="text-sm text-white/70">
                        Disponible 7j/7 pour repondre a vos questions instantanement.
                    </p>
                </div>
                <button type="button" wire:click="clearConversation"
                    class="inline-flex items-center gap-2 rounded-2xl border border-white/10 px-4 py-2 text-sm font-medium text-white transition hover:border-rose-400 hover:text-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-400/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7h12" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m10 11 .01 6M14 11l-.01 6" />
                    </svg>
                    Effacer
                </button>
            </header>

            <div class="flex-1 overflow-hidden">
                <div id="chatMessages" class="flex h-full flex-col gap-4 overflow-y-auto px-6 py-6">
                    @forelse ($messages as $index => $message)
                        <div class="flex {{ $message['sender'] === 'user' ? 'justify-end' : 'justify-start' }}"
                            wire:key="message-{{ $message->id ?? $index }}">
                            <div
                                class="max-w-[80%] rounded-3xl px-5 py-4 text-sm leading-relaxed shadow-lg shadow-slate-900/40 {{ $message['sender'] === 'user' ? 'bg-emerald-500 text-slate-900' : 'bg-white/10 text-white' }}">
                                <div
                                    class="flex items-center gap-2 text-[11px] uppercase tracking-wide {{ $message['sender'] === 'user' ? 'text-emerald-900/70' : 'text-emerald-300' }}">
                                    <span>{{ $message['sender'] === 'user' ? 'Vous' : 'EvoBot' }}</span>
                                    <span class="text-white/60">{{ $message['time'] }}</span>
                                </div>
                                <p
                                    class="mt-2 text-base {{ $message['sender'] === 'user' ? 'text-emerald-950' : 'text-white/90' }}">
                                    {{ $message['text'] }}
                                </p>

                                <div wire:stream="reply-{{ $message->id }}"
                                    class="mt-3 text-base font-semibold leading-relaxed text-white">
                                    @if ($message->reply)
                                        {!! nl2br(e($message->reply)) !!}
                                    @else
                                        <div class="flex items-center gap-2 text-sm text-white/70"
                                            x-intersect.once="$wire.look({{ $message->id }})">
                                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                                            </svg>
                                            EvoBot est en train d'ecrire...
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-sm text-white/60">
                            Soyez le premier a ecrire un message.
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="border-t border-white/10 bg-slate-900/80 px-6 py-5">
                <form wire:submit.prevent="sendMessage" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:gap-4">
                    <div class="w-full">
                        <label for="chat-message" class="sr-only">Votre message</label>
                        <textarea id="chat-message" wire:model.defer="body" rows="2"
                            placeholder="Decrivez votre besoin, posez une question, demandez un devis..."
                            class="w-full resize-none rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white placeholder:text-white/50 focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-400/40"></textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-300 disabled:cursor-not-allowed disabled:opacity-60"
                            wire:loading.attr="disabled" wire:target="sendMessage">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Envoyer
                        </button>
                    </div>
                </form>
     
            </div>
        </div>
    </div>

    @once
        <script>
            document.addEventListener('livewire:init', () => {
                const scrollToBottom = () => {
                    const container = document.getElementById('chatMessages');
                    if (!container) return;
                    requestAnimationFrame(() => {
                        container.scrollTop = container.scrollHeight;
                    });
                };

                scrollToBottom();

                Livewire.on('chat-scrolled', () => scrollToBottom());
            });
        </script>
    @endonce
</div>
