<div data-trainer="{{ $trainer }}" class="w-full h-full flex flex-col">
    <script>
        document.addEventListener('livewire:loaded', () => {
            // Scroll handler
            Livewire.on('chatbox-scroll', () => {
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
            Livewire.on('chatbox-ia-reply', (data) => {
                const trainer = data.trainer;
                const text = data.text || '';
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

    <!-- Messages Area -->
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

    <!-- Input Form -->
    <form wire:submit.prevent="sendMessage" class="border-t border-gray-200 bg-white px-4 py-3">
        <div class="flex gap-2 items-end">
            <textarea wire:model.lazy="message" rows="2" placeholder="{{ __('Votre message...') }}"
                class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 text-sm resize-none"
                wire:loading.attr="disabled" wire:target="sendMessage" maxlength="{{ (int) config('ai.max_message_length', 2000) }}"></textarea>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white px-3 py-2 rounded-lg transition-colors flex items-center justify-center flex-shrink-0"
                wire:loading.attr="disabled" wire:target="sendMessage" wire:loading.class="opacity-50">
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
