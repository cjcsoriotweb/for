@php($componentId = $this->getId())

<div
    class="w-full h-full flex flex-col bg-gray-50 relative"
    data-chatbox="{{ $componentId }}"
    data-contact-type="{{ $contactType }}"
>
    <div class="bg-white border-b p-3 shadow-lg z-[100] flex-shrink-0">
        <form wire:submit.prevent="sendMessage">
            <input
                type="text"
                wire:model.live="message"
                placeholder="Tapez votre message et appuyez sur Entrée..."
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
                maxlength="1000"
                data-chat-input="{{ $componentId }}"
            >
        </form>
    </div>

    <script>
        document.addEventListener('livewire:load', () => {
            const selector = '[data-chat-input]';

            const findInput = (componentId) =>
                document.querySelector(`[data-chat-input="${componentId}"]`);

            window.addEventListener('chat-message-sent', (event) => {
                const detail = event?.detail ?? event;
                const componentId =
                    detail?.componentId ??
                    detail?.id ??
                    (typeof detail === 'string' || typeof detail === 'number' ? detail : null);
                if (!componentId) {
                    return;
                }

                const input = findInput(componentId);
                if (!input) {
                    return;
                }

                input.value = '';

                try {
                    input.focus({ preventScroll: true });
                } catch (error) {
                    //
                }

                input.dispatchEvent(new Event('input', { bubbles: true }));
            });

            document.querySelectorAll(selector).forEach((input) => {
                input.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        setTimeout(() => {
                            const container = document.getElementById('messages-container-' + input.dataset.chatInput);
                            if (container) {
                                container.scrollTop = container.scrollHeight;
                            }
                        }, 100);
                    }
                });
            });
        });
    </script>

    <div
        id="messages-container-{{ $componentId }}"
        class="flex-1 overflow-y-auto p-4 space-y-3 pt-4"
        @if($isActive) wire:poll.5s="refreshMessages" @endif
    >
        @forelse ($messages as $index => $message)
            <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] rounded-lg px-3 py-2 {{ $message['is_mine'] ? 'bg-green-500 text-white' : 'bg-white text-gray-800 border' }} {{ ($index === 0 || $index === count($messages) - 1) ? 'opacity-75' : '' }}">
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
