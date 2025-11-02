<div class="flex flex-row items-end space-x-3 flex-wrap justify-end" data-trainer="{{ $trainer }}">
    @if (!$isOpen)
        <button type="button" wire:click="toggle"
            class="inline-flex items-center gap-3 rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-lg ring-1 ring-black/5 transition hover:-translate-y-0.5 hover:bg-gray-50">
            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-white text-xs font-bold">
                SU
            </span>
            <span>{{ $title }}</span>
        </button>
    @else
        <div
            class="bg-white rounded-2xl shadow-2xl flex flex-col w-72 sm:w-96 h-[32rem] overflow-hidden border border-slate-200">
            <div class="bg-blue-600 text-white px-4 py-3 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold">{{ $assistantMeta['name'] ?? 'Assistant' }}</h3>
                    @if (!empty($assistantMeta['description']))
                <script>
                    document.addEventListener('livewire:load', () => {
                        // Scroll handler
                        window.addEventListener('chatbox-scroll', () => {
                            setTimeout(() => {
                                // Find the visible chatbox messages container and scroll it
                                const containers = document.querySelectorAll('[data-trainer]');
                                containers.forEach(c => {
                                    const messages = c.querySelector('#chatbox-messages');
                                    if (messages) {
                                        messages.scrollTop = messages.scrollHeight;
                                    }
                                });
                            }, 50);
                        });

                        // Simulated IA reply: the Livewire PHP component dispatches this browser event with trainer and text
                        window.addEventListener('chatbox-ia-reply', (e) => {
                            const detail = e.detail || {};
                            const trainer = detail.trainer;
                            const text = detail.text || '';
                            if (!trainer) return;

                            // Find the chatbox DOM node for this trainer, then the Livewire component id
                            const trainerNode = document.querySelector(`[data-trainer="${trainer}"]`);
                            if (!trainerNode) return;

                            const livewireRoot = trainerNode.closest('[wire\\:id]');
                            const wireId = livewireRoot ? livewireRoot.getAttribute('wire:id') : null;
                            // Compose a simulated reply (replace with real AI response integration)
                            const reply = "Réponse IA simulée à : " + text;

                            if (wireId && window.Livewire && Livewire.find(wireId)) {
                                Livewire.find(wireId).call('receiveIaReply', reply);
                            }
                        });
                    });
                </script>
                        <p class="text-xs text-blue-100">{{ $assistantMeta['description'] }}</p>
                    @endif
                </div>
                <button type="button" wire:click="toggle" class="rounded-full p-1 hover:bg-blue-500 transition"
                    aria-label="{{ __('Fermer le chat') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="chatbox-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                @forelse ($messages as $message)
                    <div class="flex flex-col gap-2 {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div
                            class="max-w-[80%] rounded-lg px-4 py-2 text-sm {{ $message['role'] === 'user' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-slate-200' }}">
                            @php
                                echo $this->renderMessageHtml($message['content']);
                            @endphp
                        </div>
                    </div>
                @empty
                    <div
                        class="flex flex-col items-center justify-center gap-2 text-center text-sm text-gray-500 py-10">
                        <p class="font-medium text-gray-600">{{ __('Bienvenue !') }}</p>
                        <p>{{ __('Posez votre question pour démarrer la conversation.') }}</p>
                    </div>
                @endforelse

                @if ($isLoading)
                    <div class="flex justify-center py-2">
                        <span class="text-xs text-gray-500">{{ __('Chargement...') }}</span>
                    </div>
                @endif

                @if ($error)
                    <div class="bg-red-50 text-red-600 border border-red-200 rounded-lg px-4 py-3 text-sm">
                        {{ $error }}
                    </div>
                @endif
            </div>

            <form wire:submit.prevent="sendMessage" class="border-t border-gray-200 bg-white px-4 py-3">
                <div class="flex gap-2 items-end">
                    <textarea wire:model.defer="message" rows="2" placeholder="{{ __('Votre message...') }}"
                        class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 text-sm"
                        @disabled($isSending || $isLoading) maxlength="{{ (int) config('ai.max_message_length', 2000) }}"></textarea>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white px-3 py-2 rounded-lg transition-colors flex items-center justify-center"
                        wire:loading.attr="disabled" wire:target="sendMessage" @disabled($isSending || $isLoading)>
                        <span wire:loading wire:target="sendMessage"
                            class="h-4 w-4 border-2 border-white/60 border-t-transparent rounded-full animate-spin"></span>
                        <svg wire:loading.remove wire:target="sendMessage" class="w-5 h-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
