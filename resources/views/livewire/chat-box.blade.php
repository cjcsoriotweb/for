<div 
    x-data="chatBox()" 
    x-init="init(); this.isOpen = false;" 
    x-on:keydown.window.escape="isOpen && toggle()"
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end"
>
    <!-- Widget réduit -->
    <button
        type="button"
        x-show="!isOpen"
        x-transition
        x-cloak
        @click="toggle()"
        class="text-left bg-blue-600 text-white rounded-2xl shadow-2xl px-4 py-3 flex items-start gap-3 w-64 sm:w-72 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2"
    >
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-white shadow-inner">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 13h5m1 8l-4-4H7a3 3 0 01-3-3V7a3 3 0 013-3h10a3 3 0 013 3v6a3 3 0 01-3 3h-1v6z" />
            </svg>
        </div>
        <div class="flex flex-col">
            <span class="inline-flex w-fit items-center rounded-full bg-white/20 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide">
                {{ __('Assistant') }}
            </span>
            <span class="mt-2 text-sm font-semibold">{{ $title }}</span>
            <p class="mt-1 text-xs text-white/90">
                {{ __('Discutez avec votre assistant Evolubat pour obtenir de l\'aide immediatement.') }}
            </p>
        </div>
    </button>

    <!-- Fenêtre de chat -->
    <div 
        x-show="isOpen"
        x-transition
        x-cloak
        class="bg-white rounded-2xl shadow-2xl flex flex-col w-72 sm:w-96 h-[32rem] overflow-hidden"
    >
        <!-- Header -->
        <div class="bg-blue-600 text-white px-4 py-3 flex items-center justify-between">
            <div>
                <h3 class="font-semibold" x-text="trainerMeta?.name ?? 'Assistant'"></h3>
                <p class="text-xs text-blue-100" x-text="trainerMeta?.description ?? ''"></p>
            </div>
            <button 
                type="button" 
                class="rounded-full p-1 hover:bg-blue-500 transition"
                @click="toggle()"
                aria-label="{{ __('Fermer le chat') }}"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <template x-if="hasTrainerChoice">
            <div class="bg-blue-50 px-4 py-2 text-xs text-blue-900 flex items-center gap-2">
                <span class="font-semibold">{{ __('Assistant :') }}</span>
                <select
                    x-ref="trainerSelector"
                    x-model="selectedTrainer"
                    @change="changeTrainer(selectedTrainer)"
                    class="flex-1 rounded-lg border border-blue-200 bg-white/80 px-2 py-1 text-xs text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    <template x-for="slug in Object.keys(trainers || {})" :key="slug">
                        <option :value="slug" x-text="trainers[slug]?.name ?? slug"></option>
                    </template>
                </select>
            </div>
        </template>

        <!-- Messages -->
        <div 
            x-ref="messagesContainer"
            class="flex-1 overflow-y-auto p-4 space-y-4"
        >
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex flex-col gap-2" :class="message.role === 'user' ? 'items-end' : 'items-start'">
                    <div 
                        class="max-w-[80%] rounded-lg px-4 py-2"
                        :class="message.role === 'user' 
                            ? 'bg-blue-600 text-white' 
                            : 'bg-gray-100 text-gray-900'"
                    >
                        <div x-html="formatMessage(message.content)"></div>
                    </div>
                    <!-- Boutons suggérés par l'IA -->
                    <template x-if="message.role === 'assistant' && message.buttons && message.buttons.length > 0">
                        <div class="flex flex-wrap gap-2 max-w-[80%]">
                            <template x-for="(button, btnIndex) in message.buttons" :key="btnIndex">
                                <button
                                    @click="handleButtonClick(button, message)"
                                    type="button"
                                    class="bg-white hover:bg-blue-50 text-blue-600 border border-blue-300 rounded-lg px-3 py-1.5 text-sm transition-colors"
                                    x-text="button"
                                ></button>
                            </template>
                        </div>
                    </template>
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

            <!-- Suggestions de messages quand la conversation est vide -->
            <template x-if="messages.length === 0 && !isStreaming && !isLoadingConversation && !error">
                <div class="space-y-3">
                    <p class="text-sm text-gray-500 text-center mb-4">{{ __('Comment puis-je vous aider ?') }}</p>
                    <button 
                        @click="sendSuggestedMessage('Voir mes tickets en cours')"
                        type="button"
                        class="w-full text-left bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg px-4 py-3 text-sm transition-colors border border-blue-200"
                    >
                        📋 {{ __('Voir mes tickets en cours') }}
                    </button>
                    <button 
                        @click="sendSuggestedMessage('Comment rejoindre une application')"
                        type="button"
                        class="w-full text-left bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg px-4 py-3 text-sm transition-colors border border-blue-200"
                    >
                        📱 {{ __('Comment rejoindre une application') }}
                    </button>
                    <button 
                        @click="sendSuggestedMessage('Comment contacter un administrateur')"
                        type="button"
                        class="w-full text-left bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg px-4 py-3 text-sm transition-colors border border-blue-200"
                    >
                        👤 {{ __('Comment contacter un administrateur') }}
                    </button>
                    <button 
                        @click="sendSuggestedMessage('Demander à être rappelé par téléphone')"
                        type="button"
                        class="w-full text-left bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg px-4 py-3 text-sm transition-colors border border-blue-200"
                    >
                        📞 {{ __('Demander à être rappelé par téléphone') }}
                    </button>
                    <template x-if="hasTrainerChoice">
                        <div class="mt-4 border-t border-blue-100 pt-3">
                            <p class="text-xs font-semibold text-blue-700 mb-2">{{ __("Changer d'assistant IA") }}</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="slug in Object.keys(trainers || {})" :key="'trainer-switch-' + slug">
                                    <button
                                        x-show="slug !== trainer"
                                        type="button"
                                        class="flex items-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2 text-xs text-blue-700 transition hover:bg-blue-50"
                                        @click="changeTrainer(slug)"
                                        :title="trainers[slug]?.description || ''"
                                    >
                                        <span class="inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span x-text="trainers[slug]?.name ?? slug"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
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
                    class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 text-black"
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

        selectedTrainer: @js($trainer),

        trainers: @js($trainerOptions),

        trainerMeta: @js($currentTrainerMeta),

        conversationId: @js($conversationId),

        selectedConversationId: @js($conversationId),

        messages: [],

        message: '',

        currentResponse: '',

        isStreaming: false,

        error: null,

        isLoadingConversation: false,

        isLoadingConversations: false,

        isLoadingUsers: false,

        currentToolResults: [],

        hasTrainerChoice: false,

        shortcodeTemplates: @js($shortcodeTemplates),

        isSuperAdmin: @js($isSuperAdmin),

        currentUser: @js($currentUser),

        currentUserId: @js($currentUserId),

        users: [],

        userSearch: '',

        selectedUserId: @js($currentUserId),

        impersonateUserId: @js($currentUserId),

        selectedUser: @js($currentUser),

        conversations: [],

        async init() {
            this.trainerMeta = this.trainers?.[this.trainer] ?? this.trainerMeta ?? { name: 'Assistant', description: '' };
            this.selectedTrainer = this.trainer;
            this.hasTrainerChoice = Object.keys(this.trainers || {}).length > 1;

            if (!this.isSuperAdmin && this.currentUser) {
                this.users = [this.currentUser];
            }

            if (this.isSuperAdmin) {
                await this.refreshUsers();
            }

            await this.fetchConversations();

            if (this.conversationId) {
                await this.loadConversation(this.conversationId);
            } else if (this.isOpen && !this.isSuperAdmin) {
                await this.createConversation();
            }
        },

        async toggle() {
            this.isOpen = !this.isOpen;

            if (!this.isOpen) {
                return;
            }

            if (this.isSuperAdmin && this.users.length === 0) {
                await this.refreshUsers();
            }

            if (this.isSuperAdmin && this.conversations.length === 0) {
                await this.fetchConversations();
            }

            if (this.conversationId) {
                await this.loadConversation(this.conversationId);
            } else if (!this.isSuperAdmin) {
                await this.createConversation();
            }
        },

        ensureUserInList(user) {
            if (!user || !user.id) {
                return;
            }

            if (!(this.users || []).some(existing => existing.id === user.id)) {
                this.users.push(user);
                this.users.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
            }
        },

        setSelectedUser(userId) {
            if (userId === null || userId === undefined || userId === '') {
                this.selectedUserId = null;
                this.selectedUser = null;
                this.impersonateUserId = null;
                return;
            }

            const parsedId = Number(userId);
            if (Number.isNaN(parsedId)) {
                this.selectedUserId = null;
                this.selectedUser = null;
                this.impersonateUserId = null;
                return;
            }

            this.selectedUserId = parsedId;
            this.impersonateUserId = parsedId;
            this.selectedUser = (this.users || []).find(user => user.id === parsedId) || null;
        },

        async refreshUsers() {
            if (!this.isSuperAdmin) {
                return;
            }

            this.isLoadingUsers = true;

            try {
                const params = new URLSearchParams();
                if (this.userSearch) {
                    params.set('search', this.userSearch);
                }

                const response = await fetch(`/mon-compte/ai/users?${params.toString()}`);
                const data = await response.json();

                if (data.success) {
                    this.users = Array.isArray(data.users) ? data.users : [];
                    if (this.currentUser) {
                        this.ensureUserInList(this.currentUser);
                    }

                    if (this.selectedUserId) {
                        this.setSelectedUser(this.selectedUserId);
                    } else if (this.users.length > 0) {
                        this.setSelectedUser(this.users[0].id);
                    }
                }
            } catch (error) {
                console.error('Failed to load users', error);
            } finally {
                this.isLoadingUsers = false;
            }
        },

        async fetchConversations() {
            if (this.isSuperAdmin && !this.selectedUserId) {
                this.conversations = [];
                return;
            }

            this.isLoadingConversations = true;

            try {
                const params = new URLSearchParams();
                if (this.isSuperAdmin && this.selectedUserId) {
                    params.set('user_id', this.selectedUserId);
                }

                const response = await fetch(`/mon-compte/ai/conversations?${params.toString()}`);
                const data = await response.json();

                if (data.success) {
                    this.conversations = Array.isArray(data.conversations) ? data.conversations : [];

                    if (!this.conversationId && this.conversations.length > 0) {
                        const firstConversation = this.conversations[0];
                        this.selectedConversationId = firstConversation.id;
                        await this.loadConversation(firstConversation.id);
                    } else if (this.conversationId) {
                        this.selectedConversationId = this.conversationId;
                    }
                }
            } catch (error) {
                console.error('Failed to load conversations', error);
            } finally {
                this.isLoadingConversations = false;
            }
        },

        formatConversationLabel(conversation) {
            if (!conversation) {
                return '';
            }

            const owner = conversation.user;
            const ownerLabel = owner ? `${owner.name || 'Utilisateur'}${owner.superadmin ? ' (Superadmin)' : ''}` : 'Utilisateur';
            const trainer = conversation.trainer ? conversation.trainer.toUpperCase() : 'DEFAULT';
            return `#${conversation.id} - ${ownerLabel} - ${trainer}`;
        },

        async loadConversation(conversationId) {
            if (!conversationId) {
                this.messages = [];
                this.conversationId = null;
                this.selectedConversationId = null;
                return;
            }

            this.error = null;

            try {
                const response = await fetch(`/mon-compte/ai/conversations/${conversationId}`);
                const data = await response.json();

                if (!data.success) {
                    this.error = data.message || 'Impossible de charger la conversation';
                    return;
                }

                const conversation = data.conversation || {};
                this.conversationId = conversation.id;
                this.selectedConversationId = conversation.id;
                this.trainer = conversation.trainer || this.trainer;
                this.selectedTrainer = this.trainer;

                if (this.isSuperAdmin && conversation.user) {
                    this.ensureUserInList(conversation.user);
                    this.setSelectedUser(conversation.user.id);
                }

                this.messages = Array.isArray(data.messages)
                    ? data.messages.map(message => ({
                        role: message.role,
                        content: message.content,
                        created_at: message.created_at,
                        user: message.user || null,
                        buttons: message.buttons || [],
                    }))
                    : [];

                this.scrollToBottom();
            } catch (error) {
                console.error('Failed to load conversation', error);
                this.error = 'Impossible de charger la conversation s�lectionn�e';
            }
        },

        async selectConversation(conversationId) {
            if (!conversationId) {
                return;
            }

            await this.loadConversation(conversationId);
            if (!this.isOpen) {
                this.isOpen = true;
            }
        },

        async onUserSelect(event) {
            const value = event?.target?.value ?? null;
            this.setSelectedUser(value);
            this.conversationId = null;
            this.selectedConversationId = null;
            this.messages = [];
            await this.fetchConversations();
        },

        async onConversationSelect(event) {
            const value = Number(event?.target?.value ?? 0);
            if (!value || Number.isNaN(value)) {
                return;
            }

            await this.selectConversation(value);
        },

        async startNewConversation() {
            this.conversationId = null;
            this.selectedConversationId = null;
            this.messages = [];
            await this.createConversation();
        },

        async createConversation() {
            if (this.isLoadingConversation) {
                return;
            }

            if (this.isSuperAdmin && !this.selectedUserId) {
                this.error = 'Veuillez s�lectionner un utilisateur pour cr�er une conversation.';
                return;
            }

            this.isLoadingConversation = true;
            this.error = null;

            const payload = {
                trainer: this.trainer,
            };

            if (this.isSuperAdmin && this.selectedUserId) {
                payload.user_id = this.selectedUserId;
            }

            try {
                const response = await fetch('/mon-compte/ai/conversations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json();

                if (data.success && data.conversation) {
                    this.conversationId = data.conversation.id;
                    this.selectedConversationId = data.conversation.id;

                    if (this.isSuperAdmin && data.conversation.user) {
                        this.ensureUserInList(data.conversation.user);
                        this.setSelectedUser(data.conversation.user.id);
                    }

                    await this.loadConversation(data.conversation.id);
                    await this.fetchConversations();
                } else {
                    this.error = data.message || 'Impossible de creer la conversation';
                }
            } catch (e) {
                this.error = 'Erreur lors de la creation de la conversation : ' + e.message;
            } finally {
                this.isLoadingConversation = false;
            }
        },

        async changeTrainer(slug) {
            if (!slug || slug === this.trainer || !(this.trainers || {})[slug]) {
                return;
            }

            this.trainer = slug;
            this.selectedTrainer = slug;
            this.trainerMeta = this.trainers[slug];
            this.messages = [];
            this.currentResponse = '';
            this.currentToolResults = [];
            this.conversationId = null;
            this.error = null;

            if (this.$refs.trainerSelector) {
                this.$refs.trainerSelector.value = slug;
            }

            if (this.isOpen) {
                await this.createConversation();
            }
        },

        sendSuggestedMessage(text) {
            this.message = text;
            this.sendMessage();
        },

        async sendMessage() {
            if (!this.message.trim() || this.isStreaming) {
                return;
            }

            if (!this.conversationId) {
                this.error = 'Conversation non initialisee. Veuillez reessayer.';
                await this.createConversation();
                if (!this.conversationId) {
                    return;
                }
            }

            const userMessage = this.message.trim();
            this.message = '';
            this.error = null;

            const actorUser = this.isSuperAdmin
                ? (this.selectedUser || this.currentUser)
                : this.currentUser;

            this.messages.push({
                role: 'user',
                content: userMessage,
                user: actorUser,
            });

            this.scrollToBottom();

            this.isStreaming = true;
            this.currentResponse = '';
            this.currentToolResults = [];

            const payload = {
                message: userMessage,
                trainer: this.trainer,
                conversation_id: this.conversationId,
            };

            if (this.isSuperAdmin && this.impersonateUserId) {
                payload.acting_user_id = this.impersonateUserId;
            }

            try {
                const response = await fetch('/mon-compte/ai/stream', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify(payload),
                });

                if (!response.ok) {
                    if (response.status === 404 || response.status === 422) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Erreur de validation');
                    }
                    throw new Error('Erreur reseau');
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder('utf-8', { stream: true });

                while (true) {
                    const { done, value } = await reader.read();

                    if (done) {
                        break;
                    }

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
                                } else if (data.type === 'tool_result') {
                                    this.currentResponse = data.content;
                                    this.currentToolResults = Array.isArray(data.tool_results) ? data.tool_results : [];
                                    this.scrollToBottom();
                                } else if (data.type === 'done') {
                                    const { content, buttons } = this.extractButtons(this.currentResponse);
                                    const ticketUrl = this.getTicketUrlFromToolResults();
                                    const finalButtons = ticketUrl && !buttons.includes('Consulter le ticket')
                                        ? [...buttons, 'Consulter le ticket']
                                        : buttons;

                                    this.messages.push({
                                        role: 'assistant',
                                        content: content,
                                        buttons: finalButtons,
                                        ticketUrl: ticketUrl,
                                        user: {
                                            name: this.trainerMeta?.name || 'Assistant',
                                            superadmin: false,
                                        },
                                    });
                                    this.currentResponse = '';
                                    this.currentToolResults = [];
                                } else if (data.type === 'error') {
                                    this.error = data.message;
                                }
                            } catch (parseError) {
                                console.error('JSON parse error:', parseError, 'Line:', line);
                            }
                        }
                    }
                }

                this.fetchConversations();
            } catch (e) {
                this.error = 'Une erreur est survenue : ' + e.message;
            } finally {
                this.isStreaming = false;
                this.scrollToBottom();
            }
        },

        getTicketUrlFromToolResults() {
            if (!Array.isArray(this.currentToolResults)) {
                return null;
            }

            for (const entry of this.currentToolResults) {
                if (!entry || typeof entry !== 'object') {
                    continue;
                }

                const result = entry.result || {};

                if (typeof result.ticket_url === 'string' && result.ticket_url.length > 0) {
                    return result.ticket_url;
                }

                if (result.ticket && typeof result.ticket.ticket_url === 'string' && result.ticket.ticket_url.length > 0) {
                    return result.ticket.ticket_url;
                }
            }

            return null;
        },

        handleButtonClick(label, message) {
            if (label === 'Consulter le ticket' && message?.ticketUrl) {
                window.open(message.ticketUrl, '_blank');
                return;
            }

            this.sendSuggestedMessage(label);
        },
        extractButtons(content) {
            // Extraire les boutons du format [BUTTONS]...[/BUTTONS]
            // Utilise un regex strict pour éviter les injections
            const buttonRegex = /\[BUTTONS\]\s*((?:- [^\n<>]+\n?)+)\s*\[\/BUTTONS\]/i;
            const match = content.match(buttonRegex);
            
            if (!match) {
                // Debug: log when no buttons are found
                if (content.includes('[BUTTONS]')) {
                    console.warn('BUTTONS tag found but regex did not match. Content:', content);
                }
                return { content: content, buttons: [] };
            }
            
            // Extraire les boutons (lignes commençant par -)
            const buttonsText = match[1];
            const buttons = buttonsText
                .split('\n')
                .map(line => line.trim())
                .filter(line => line.startsWith('-'))
                .map(line => {
                    // Nettoyer et sanitiser le texte du bouton
                    const text = line.substring(1).trim();
                    // Limiter la longueur et encoder les caractères potentiellement dangereux
                    return text.substring(0, 100)
                        .replace(/[<>'"&]/g, char => {
                            const entities = {'<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;', '&': '&amp;'};
                            return entities[char] || char;
                        });
                })
                .filter(button => button.length > 0 && button.length <= 100);
            
            // Debug: log extracted buttons
            console.log('Extracted buttons:', buttons);
            
            // Retirer la section [BUTTONS] du contenu
            const cleanContent = content.replace(buttonRegex, '').trim();
            
            return { content: cleanContent, buttons: buttons };
        },

        applyShortcodes(content) {
            if (typeof content !== 'string' || !content.includes('[')) {
                return content;
            }

            const templates = this.shortcodeTemplates || {};

            return content.replace(/\[([A-Z0-9_]+)\]/gi, (match, key) => {
                const templateKey = key.toUpperCase();
                const template = templates[templateKey];

                return typeof template === 'string' && template.length > 0 ? template : match;
            });
        },

        formatMessage(content) {
            if (typeof content !== 'string') {
                return '';
            }

            const withShortcodes = this.applyShortcodes(content);

            // Simple conversion Markdown -> HTML
            let html = withShortcodes
                // Links [text](url)
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-blue-600 underline hover:text-blue-800">$1</a>')
                // Bold **text**
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                // Italic *text*
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                // Inline code `text`
                .replace(/`(.+?)`/g, '<code class="bg-gray-200 px-1 rounded">$1</code>')
                // Line breaks
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
