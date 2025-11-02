<div class="w-full h-full flex flex-col bg-gray-50 relative">
    <!-- Formulaire d'envoi - Position absolue en haut pour être toujours visible -->
    <div class="bg-white border-b p-3 shadow-lg z-[100] flex-shrink-0" wire:ignore> 
        <form wire:submit.prevent="sendMessage">
            <input
                type="text"
                wire:model="message"
                placeholder="Tapez votre message et appuyez sur Entrée..."
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
                maxlength="1000"
                data-chat-input="{{ $this->getId() }}"
            >
        </form>
    </div>

    <script>
        document.addEventListener('livewire:loaded', () => {
            // Scroll vers le bas quand un nouveau message est ajouté
            window.addEventListener('scroll-to-bottom', () => {
                setTimeout(() => {
                    const messagesContainer = document.getElementById('messages-container');
                    if (messagesContainer) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                }, 100);
            });

            // Scroll vers le haut pour montrer les messages chargés
            window.addEventListener('scroll-to-top', () => {
                setTimeout(() => {
                    const messagesContainer = document.getElementById('messages-container');
                    if (messagesContainer) {
                        messagesContainer.scrollTop = 0;
                    }
                }, 100);
            });
        });
    </script>
    <script>
        document.addEventListener('livewire:load', () => {
            if (window.__chatInputFocusHooked) {
                return;
            }

            window.__chatInputFocusHooked = true;
            window.__chatFocusedInputs = {};

            Livewire.hook('message.sent', (component) => {
                const input = document.querySelector(`input[data-chat-input="${component.id}"]`);
                if (input && document.activeElement === input) {
                    window.__chatFocusedInputs[component.id] = true;
                }
            });

            Livewire.hook('message.processed', (component) => {
                if (window.__chatFocusedInputs[component.id]) {
                    const input = document.querySelector(`input[data-chat-input="${component.id}"]`);
                    if (input) {
                        const length = input.value.length;
                        input.focus({ preventScroll: true });
                        input.setSelectionRange(length, length);
                    }
                    delete window.__chatFocusedInputs[component.id];
                }
            });
        });
    </script>

    <!-- Zone des messages -->
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 pt-4" @if($isActive) wire:poll.5s="refreshMessages" @endif>
        @forelse ($messages as $index => $message)
            <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] rounded-lg px-3 py-2 {{ $message['is_mine'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-800 border' }} {{ ($index === 0 || $index === count($messages) - 1) ? 'opacity-75' : '' }}">
                    @if(!$message['is_mine'] && $message['sender'])
                        <div class="text-xs text-gray-500 mb-1">{{ $message['sender']->name }}</div>
                    @endif
                    <div class="text-sm whitespace-pre-wrap">{{ $message['content'] }}</div>
                    <div class="text-xs opacity-70 mt-1">
                        {{ $message['created_at']->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center">
                    <p class="text-lg mb-2">💬</p>
                    <p>Aucun message pour le moment</p>
                </div>
            </div>
        @endforelse

        @if ($isSending)
            <div class="flex justify-center">
                <div class="text-xs text-gray-500">Envoi en cours...</div>
            </div>
        @endif
    </div>

    @if($contactType === 'ai' && $awaitingAiResponse)
        <div class="px-4 py-2 text-sm text-gray-500 flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            Assistant en train d'écrire...
        </div>
    @endif
</div>
