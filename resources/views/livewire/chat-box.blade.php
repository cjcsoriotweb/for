<div class="w-full h-full flex flex-col bg-gray-50" @if($isActive) wire:poll.3s="loadMessages" @endif>
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
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3">
        <!-- Bouton Charger plus -->
        @if ($canLoadMore)
            <div class="flex justify-center pb-4">
                <button wire:click="loadMoreMessages"
                    wire:loading.attr="disabled" wire:target="loadMoreMessages"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50">
                    <span wire:loading wire:target="loadMoreMessages" class="h-4 w-4 border-2 border-gray-400 border-t-transparent rounded-full animate-spin"></span>
                    <span wire:loading.remove wire:target="loadMoreMessages">Charger 5 messages arrière</span>
                    <span wire:loading wire:target="loadMoreMessages">Chargement...</span>
                </button>
            </div>
        @endif

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

        <!-- Bouton Charger suivants -->
        @if ($canLoadNewer)
            <div class="flex justify-center pt-4">
                <button wire:click="loadNewerMessages"
                    wire:loading.attr="disabled" wire:target="loadNewerMessages"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50">
                    <span wire:loading wire:target="loadNewerMessages" class="h-4 w-4 border-2 border-gray-400 border-t-transparent rounded-full animate-spin"></span>
                    <span wire:loading.remove wire:target="loadNewerMessages">Charger 5 messages suivants</span>
                    <span wire:loading wire:target="loadNewerMessages">Chargement...</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Formulaire d'envoi -->
    <div class="border-t bg-white p-3 flex-shrink-0">
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
