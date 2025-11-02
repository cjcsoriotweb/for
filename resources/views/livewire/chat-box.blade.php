<div data-trainer="{{ $trainer }}" class="w-full h-full flex flex-col">
    <script>
        // Définir le trainer actuel pour le JavaScript
        window.currentTrainer = '{{ $trainer }}';

        document.addEventListener('livewire:loaded', () => {
            console.log('ChatBox JavaScript loaded for trainer:', window.currentTrainer);

            // Scroll handler - listen for browser events dispatched by PHP
            window.addEventListener('chatbox-scroll', () => {
                console.log('chatbox-scroll event received');
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

            // AI response handler - appelle la méthode avec un petit délai pour la fluidité
            window.addEventListener('trigger-ai-response', (e) => {
                const detail = e.detail || {};
                const text = detail.text || '';

                // Trouver le composant Livewire pour ce trainer
                const trainerNode = document.querySelector(`[data-trainer="${window.currentTrainer || ''}"]`);
                if (!trainerNode) {
                    return;
                }

                const livewireRoot = trainerNode.closest('[wire\\:id]');
                const wireId = livewireRoot ? livewireRoot.getAttribute('wire:id') : null;

                if (wireId && window.Livewire && Livewire.find(wireId)) {
                    // Petit délai pour permettre à l'interface de se mettre à jour
                    setTimeout(() => {
                        Livewire.find(wireId).call('processAiResponse', text);
                    }, 300);
                }
            });

            // Load more messages handler
            window.addEventListener('load-more-messages', (e) => {
                const detail = e.detail || {};
                const scrollToTop = detail.scrollToTop || false;

                if (scrollToTop) {
                    // Scroll vers le haut pour montrer les nouveaux messages
                    setTimeout(() => {
                        const containers = document.querySelectorAll('[data-trainer]');
                        containers.forEach(c => {
                            const messages = c.querySelector('#chatbox-messages');
                            if (messages) {
                                messages.scrollTop = 0;
                            }
                        });
                    }, 100);
                }
            });
        });
    </script>

    <!-- Messages Area -->
    <div id="chatbox-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
        <!-- Bouton Charger plus -->
        @if ($canLoadMore)
            <div class="flex justify-center pb-4">
                <button wire:click="loadMoreMessages"
                    wire:loading.attr="disabled" wire:target="loadMoreMessages"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50">
                    <span wire:loading wire:target="loadMoreMessages" class="h-4 w-4 border-2 border-gray-400 border-t-transparent rounded-full animate-spin"></span>
                    <span wire:loading.remove wire:target="loadMoreMessages">Charger +15 messages</span>
                    <span wire:loading wire:target="loadMoreMessages">Chargement...</span>
                </button>
            </div>
        @endif

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

        @if ($isLoading || $isSending)
            <div class="flex justify-center py-2">
                <span class="text-xs text-gray-500">
                    @if ($isSending && !$isLoading)
                        {{ __('Envoi en cours...') }}
                    @else
                        {{ __('L\'assistant réfléchit...') }}
                    @endif
                </span>
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
