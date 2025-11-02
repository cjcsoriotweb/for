<div class="w-full h-full flex flex-col bg-gray-50">
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
        });
    </script>

    <!-- Zone des messages -->
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3">
        @forelse ($messages as $message)
            <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] rounded-lg px-3 py-2 {{ $message['is_mine'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-800 border' }}">
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

    <!-- Formulaire d'envoi -->
    <div class="border-t bg-white p-3">
        <form wire:submit.prevent="sendMessage" class="flex gap-2">
            <input
                type="text"
                wire:model="message"
                placeholder="Tapez votre message..."
                class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
                maxlength="1000"
            >
            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors disabled:opacity-50"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50"
            >
                <span wire:loading class="animate-spin">⏳</span>
                <span wire:loading.remove>Envoyer</span>
            </button>
        </form>
    </div>
</div>
