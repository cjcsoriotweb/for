<div x-data="chatBox()" x-init="init()" class="fixed bottom-4 right-4 z-50">
    <!-- Bouton toggle -->
    <button 
        @click="toggle()"
        type="button"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full px-6 py-3 shadow-lg transition-all"
        x-show="!isOpen"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <span>{{ $title }}</span>
    </button>

    <!-- Fenêtre de chat -->
    <div 
        x-show="isOpen"
        x-transition
        class="bg-white rounded-lg shadow-2xl w-96 h-[600px] flex flex-col"
    >
        <!-- Header -->
        <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex items-center justify-between">
            <div>
                <h3 class="font-semibold">{{ $trainerName }}</h3>
                <p class="text-xs text-blue-100">{{ $trainerDescription }}</p>
            </div>
            <button @click="toggle()" type="button" class="text-white hover:text-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Messages -->
        <div 
            x-ref="messagesContainer"
            class="flex-1 overflow-y-auto p-4 space-y-4"
        >
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex" :class="message.role === 'user' ? 'justify-end' : 'justify-start'">
                    <div 
                        class="max-w-[80%] rounded-lg px-4 py-2"
                        :class="message.role === 'user' 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 text-gray-900'"
                    >
                        <div x-html="formatMessage(message.content)"></div>
                    </div>
                </div>
            </template>

            <!-- Message en cours de génération -->
            <template x-if="isStreaming && currentResponse">
                <div class="flex justify-start">
                    <div class="max-w-[80%] bg-gray-100 text-gray-900 rounded-lg px-4 py-2">
                        <div x-html="formatMessage(currentResponse)"></div>
                    </div>
                </div>
            </template>

            <!-- Indicateur de chargement conversation -->
            <template x-if="isLoadingConversation">
                <div class="flex justify-center">
                    <div class="bg-blue-100 rounded-lg px-4 py-2 text-blue-700 text-sm">
                        Création de la conversation...
                    </div>
                </div>
            </template>

            <!-- Indicateur de chargement -->
            <template x-if="isStreaming && !currentResponse">
                <div class="flex justify-start">
                    <div class="bg-gray-100 rounded-lg px-4 py-2">
                        <div class="flex gap-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Erreur -->
            <template x-if="error">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p class="text-sm" x-text="error"></p>
                </div>
            </template>
        </div>

        <!-- Input -->
        <div class="border-t p-4">
            <form @submit.prevent="sendMessage()" class="flex gap-2">
                <input 
                    type="text"
                    x-model="message"
                    :disabled="isStreaming || isLoadingConversation"
                    placeholder="Votre message..."
                    class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100"
                    maxlength="{{ config('ai.max_message_length', 2000) }}"
                />
                <button 
                    type="submit"
                    :disabled="!message.trim() || isStreaming || isLoadingConversation"
                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white px-4 py-2 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function chatBox() {
    return {
        isOpen: @js($isOpen),
        trainer: @js($trainer),
        conversationId: @js($conversationId),
        messages: [],
        message: '',
        currentResponse: '',
        isStreaming: false,
        error: null,
        isLoadingConversation: false,

        async init() {
            // Si pas de conversation, en créer une automatiquement lors de l'ouverture
            if (this.isOpen && !this.conversationId) {
                await this.createConversation();
            }
        },

        async toggle() {
            this.isOpen = !this.isOpen;
            
            // Créer une conversation si nécessaire lors de l'ouverture
            if (this.isOpen && !this.conversationId) {
                await this.createConversation();
            }
        },

        async createConversation() {
            if (this.isLoadingConversation) return;
            
            this.isLoadingConversation = true;
            this.error = null;

            try {
                const response = await fetch('/mon-compte/ai/conversations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        trainer: this.trainer,
                    }),
                });

                const data = await response.json();

                if (data.success && data.conversation) {
                    this.conversationId = data.conversation.id;
                } else {
                    this.error = data.message || 'Impossible de créer la conversation';
                }
            } catch (e) {
                this.error = 'Erreur lors de la création de la conversation : ' + e.message;
            } finally {
                this.isLoadingConversation = false;
            }
        },

        async sendMessage() {
            if (!this.message.trim() || this.isStreaming) {
                return;
            }

            // Vérifier qu'une conversation existe
            if (!this.conversationId) {
                this.error = 'Conversation non initialisée. Veuillez réessayer.';
                await this.createConversation();
                return;
            }

            const userMessage = this.message.trim();
            this.message = '';
            this.error = null;

            // Ajouter le message utilisateur
            this.messages.push({
                role: 'user',
                content: userMessage,
            });

            this.scrollToBottom();

            // Démarrer le streaming
            this.isStreaming = true;
            this.currentResponse = '';

            try {
                const response = await fetch('/mon-compte/ai/stream', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        message: userMessage,
                        trainer: this.trainer,
                        conversation_id: this.conversationId,
                    }),
                });

                if (!response.ok) {
                    // Gérer les erreurs spécifiques
                    if (response.status === 404 || response.status === 422) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Erreur de validation');
                    }
                    throw new Error('Erreur réseau');
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder('utf-8', { stream: true });

                while (true) {
                    const { done, value } = await reader.read();
                    
                    if (done) break;

                    const chunk = decoder.decode(value, { stream: true });
                    const lines = chunk.split('\n');

                    for (const line of lines) {
                        if (line.startsWith('data: ')) {
                            try {
                                const data = JSON.parse(line.substring(6));

                                if (data.type === 'conversation_id') {
                                    this.conversationId = data.conversation_id;
                                } else if (data.type === 'chunk') {
                                    this.currentResponse += data.content;
                                    this.scrollToBottom();
                                } else if (data.type === 'done') {
                                    // Ajouter le message complet
                                    this.messages.push({
                                        role: 'assistant',
                                        content: this.currentResponse,
                                    });
                                    this.currentResponse = '';
                                } else if (data.type === 'error') {
                                    this.error = data.message;
                                }
                            } catch (parseError) {
                                console.error('JSON parse error:', parseError, 'Line:', line);
                                // Continue avec la ligne suivante en cas d'erreur de parsing
                            }
                        }
                    }
                }
            } catch (e) {
                this.error = 'Une erreur est survenue : ' + e.message;
            } finally {
                this.isStreaming = false;
                this.scrollToBottom();
            }
        },

        formatMessage(content) {
            // Simple conversion Markdown -> HTML
            let html = content
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/`(.+?)`/g, '<code class="bg-gray-200 px-1 rounded">$1</code>')
                .replace(/\n/g, '<br>');
            
            return html;
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },
    };
}
</script>
