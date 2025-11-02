<div class="w-full h-full flex flex-col bg-gray-50 relative" @if($isActive) wire:poll.3s="loadMessages" @endif>
    <!-- Formulaire d'envoi - Position absolue en haut pour être toujours visible -->
    <div class="bg-white border-b p-3 shadow-lg z-[100] flex-shrink-0">
        <form wire:submit.prevent="sendMessage">
            <input
                type="text"
                wire:model="message"
                placeholder="Tapez votre message et appuyez sur Entrée..."
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
                maxlength="1000"
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

    <!-- Zone des messages -->
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3 pt-4">
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
</div>
