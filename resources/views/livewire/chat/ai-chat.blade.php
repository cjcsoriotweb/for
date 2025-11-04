@php
    $componentId = $this->getId();
@endphp

<div
    class="w-full h-full flex flex-col bg-gray-50 relative"
    data-chatbox="{{ $componentId }}"
    data-contact-type="ai"
    data-ai-trainer="{{ $aiTrainerSlug }}"
>
    <div class="bg-white border-b p-3 shadow-lg z-[100] flex-shrink-0">
        <form wire:submit.prevent="sendMessage">
            <input
                type="text"
                wire:model.live="message"
                placeholder="Tapez votre message et appuyez sur Entrée..."
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
                maxlength="1000"
                data-chat-input="{{ $componentId }}"
            >
        </form>
    </div>

    <script>
        document.addEventListener('livewire:loaded', () => {
            window.addEventListener('scroll-to-bottom', () => {
                setTimeout(() => {
                    const messagesContainer = document.getElementById('messages-container-{{ $componentId }}');
                    if (messagesContainer) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
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

            const selector = '[data-chat-input]';

            const findInput = (componentId) =>
                document.querySelector(`[data-chat-input="${componentId}"]`);

            const rememberSelection = (input) => {
                if (!input || document.activeElement !== input) {
                    return;
                }

                const componentId = input.dataset.chatInput;
                if (!componentId) {
                    return;
                }

                window.__chatFocusedInputs[componentId] = {
                    start: input.selectionStart ?? input.value.length,
                    end: input.selectionEnd ?? input.value.length,
                };
            };

            const forgetSelection = (input) => {
                if (!input) {
                    return;
                }

                const componentId = input.dataset.chatInput;
                if (componentId) {
                    delete window.__chatFocusedInputs[componentId];
                }
            };

            const registerInput = (input) => {
                if (!input || input.__chatFocusRegistered) {
                    return;
                }

                input.__chatFocusRegistered = true;

                ['focus', 'keyup', 'click', 'input'].forEach((eventName) => {
                    input.addEventListener(eventName, () => rememberSelection(input));
                });

                input.addEventListener('blur', () => forgetSelection(input));
            };

            const registerAllInputs = () => {
                document.querySelectorAll(selector).forEach(registerInput);
            };

            registerAllInputs();

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

                try {
                    input.setSelectionRange(0, 0);
                } catch (error) {
                    //
                }

                window.__chatFocusedInputs[componentId] = { start: 0, end: 0 };

                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });
    </script>

    <div
        id="messages-container-{{ $componentId }}"
        class="flex-1 overflow-y-auto p-4 space-y-3 pt-4"
        data-messages-container="{{ $componentId }}"
        @if($isActive) wire:poll.5s="refreshMessages" @endif
    >
        @if (!empty($messages))
            @foreach ($messages as $index => $message)
                <div class="flex {{ $message['is_mine'] ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] rounded-lg px-3 py-2 {{ $message['is_mine'] ? 'bg-blue-500 text-white' : 'bg-white text-gray-800 border' }} {{ ($index === 0 || $index === count($messages) - 1) ? 'opacity-75' : '' }}">
                        @php
                            $displayName = null;
                            if (!$message['is_mine']) {
                                if (!empty($message['sender']) && is_object($message['sender'])) {
                                    $displayName = $message['sender']->name ?? null;
                                } elseif (!empty($message['sender_name'])) {
                                    $displayName = $message['sender_name'];
                                }
                            }
                        @endphp
                        @if($displayName)
                            <div class="text-xs text-gray-500 mb-1">{{ $displayName }}</div>
                        @endif
                        <div class="text-sm whitespace-pre-wrap">{{ $message['content'] }}</div>
                        <div class="text-xs opacity-70 mt-1">
                            {{ $message['created_at']->format('H:i') }}
                        </div>
                        @if (!empty($message['status_label']))
                            <div class="text-[10px] text-gray-400 mt-0.5">
                                {{ $message['status_label'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="flex items-center justify-center h-full text-gray-500">
                <div class="text-center">
                    <p class="text-lg mb-2">🤖</p>
                    <p>Aucun message pour le moment</p>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    document.addEventListener('livewire:load', () => {
        if (window.__aiChatStreamHooked) {
            return;
        }

        window.__aiChatStreamHooked = true;
        window.__aiActiveStreams = window.__aiActiveStreams || {};

        const csrfToken = () => {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        };

        const findMessagesContainer = (componentId) =>
            document.querySelector(`[data-messages-container="${componentId}"]`);

        const scrollToBottom = (componentId) => {
            const container = findMessagesContainer(componentId);
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        };

        const createMessageBubble = ({ componentId, content, isMine, senderName, streaming }) => {
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
            if (streaming) {
                wrapper.dataset.aiStreaming = componentId;
            }

            const bubble = document.createElement('div');
            bubble.className = `max-w-[75%] rounded-lg px-3 py-2 ${
                isMine ? 'bg-blue-500 text-white' : 'bg-white text-gray-800 border'
            }`;

            if (!isMine && senderName) {
                const sender = document.createElement('div');
                sender.className = 'text-xs text-gray-500 mb-1';
                sender.dataset.aiSender = 'true';
                sender.textContent = senderName;
                bubble.appendChild(sender);
            }

            const contentEl = document.createElement('div');
            contentEl.className = 'text-sm whitespace-pre-wrap';
            contentEl.dataset.aiContent = 'true';
            contentEl.textContent = content;
            bubble.appendChild(contentEl);

            const time = document.createElement('div');
            time.className = 'text-xs opacity-70 mt-1';
            time.dataset.aiTime = 'true';
            const now = new Date();
            time.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            bubble.appendChild(time);

            wrapper.appendChild(bubble);

            return wrapper;
        };

        const appendBubble = (componentId, options) => {
            const container = findMessagesContainer(componentId);
            if (!container) {
                return null;
            }

            const bubble = createMessageBubble({ componentId, ...options });
            container.appendChild(bubble);
            scrollToBottom(componentId);

            return bubble;
        };

        const appendDebug = () => {};

        const updateAssistantBubble = (bubble, senderName, content) => {
            if (!bubble) {
                return;
            }

            const sender = bubble.querySelector('[data-ai-sender]');
            if (sender && senderName) {
                sender.textContent = senderName;
            }

            const contentEl = bubble.querySelector('[data-ai-content]');
            if (contentEl) {
                contentEl.textContent = content;
            }
        };

        const handleAiStream = async (payload = {}) => {
            const {
                componentId,
                messageId,
                trainerSlug,
                endpoint,
                message,
                assistantName,
            } = payload;

            if (
                !componentId ||
                !messageId ||
                !trainerSlug ||
                !endpoint ||
                !message
            ) {
                return;
            }

            appendBubble(componentId, {
                content: message,
                isMine: true,
                senderName: 'Vous',
            });

            const assistantBubble = appendBubble(componentId, {
                content: '',
                isMine: false,
                senderName: assistantName || 'Assistant IA',
                streaming: true,
            });

            if (!assistantBubble) {
                return;
            }

            updateAssistantBubble(assistantBubble, assistantName, '');

            const controller = new AbortController();
            if (window.__aiActiveStreams[componentId]) {
                window.__aiActiveStreams[componentId].controller.abort();
            }
            window.__aiActiveStreams[componentId] = { controller, assistantBubble };

            const headers = {
                'Content-Type': 'application/json',
                Accept: 'text/event-stream',
                'X-Requested-With': 'XMLHttpRequest',
            };
            const token = csrfToken();
            if (token) {
                headers['X-CSRF-TOKEN'] = token;
            }

            let assistantText = '';
            let hasStartedWriting = false;
            let chunkCount = 0;

            const processLine = (line) => {
                if (!line.startsWith('data:')) {
                    return;
                }

                const payload = line.slice(5).trim();
                if (!payload) {
                    return;
                }

                let data;
                try {
                    data = JSON.parse(payload);
                } catch (error) {
                    return;
                }

                if (data.type === 'chunk') {
                    if (!hasStartedWriting) {
                        hasStartedWriting = true;
                        updateStatus(componentId, 'Assistant rédige sa réponse…', 'typing');
                        appendDebug(componentId, 'info', 'Réponse en cours…');
                    }
                    chunkCount += 1;
                    if (chunkCount <= 5 || chunkCount % 10 === 0) {
                        const preview = (data.content ?? '').slice(0, 80);
                        appendDebug(
                            componentId,
                            'chunk',
                            `#${chunkCount} (${preview.length} chars) ${preview}${preview.length === 80 ? '…' : ''}`
                        );
                    }
                    assistantText += data.content ?? '';
                    updateAssistantBubble(assistantBubble, assistantName, assistantText);
                } else if (data.type === 'tool_result' && Array.isArray(data.tool_results)) {
                    updateStatus(componentId, 'Assistant finalise les informations…', 'typing');
                    appendDebug(componentId, 'tool', `${data.tool_results.length} résultat(s) d'outils`);
                    const extra = data.tool_results
                        .map((tool) => tool.content || '')
                        .join("\n")
                        .trim();
                    if (extra !== '') {
                        assistantText += (assistantText ? "\n" : '') + extra;
                        updateAssistantBubble(assistantBubble, assistantName, assistantText);
                    }
                } else if (data.type === 'done') {
                    if (typeof data.content === 'string' && data.content !== '') {
                        assistantText = data.content;
                        updateAssistantBubble(assistantBubble, assistantName, assistantText);
                    }
                    appendDebug(componentId, 'done', `Terminé (${chunkCount} chunks)`);
                    updateStatus(componentId, null);
                } else if (data.type === 'error') {
                    updateStatus(componentId, 'Assistant indisponible.', 'error');
                    appendDebug(componentId, 'error', data.message || 'Erreur inconnue');
                    throw new Error(data.message || 'Erreur IA');
                }
            };

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers,
                    body: JSON.stringify({
                        message,
                        trainer: trainerSlug,
                        message_id: messageId,
                    }),
                    credentials: 'same-origin',
                    signal: controller.signal,
                });

                if (!response.ok || !response.body) {
                    throw new Error(`Requête IA indisponible (${response.status})`);
                }
                appendDebug(componentId, 'response', `Statut ${response.status}`);

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';

                while (true) {
                    const { value, done } = await reader.read();
                    if (done) {
                        break;
                    }

                    buffer += decoder.decode(value, { stream: true });

                    let newlineIndex;
                    while ((newlineIndex = buffer.indexOf("\n")) >= 0) {
                        const line = buffer.slice(0, newlineIndex).trim();
                        buffer = buffer.slice(newlineIndex + 1);
                        if (line === '') {
                            continue;
                        }
                        processLine(line);
                    }
                }

                const remainder = buffer.trim();
                if (remainder !== '') {
                    processLine(remainder);
                }
                appendDebug(componentId, 'stream', 'Flux terminé');
                updateStatus(componentId, null);
            } catch (error) {
                updateAssistantBubble(
                    assistantBubble,
                    assistantName,
                    `Erreur IA : ${error.message || 'flux interrompu'}`
                );
                updateStatus(componentId, 'Assistant indisponible.', 'error');
                appendDebug(componentId, 'exception', error.message || String(error));
            } finally {
                delete window.__aiActiveStreams[componentId];
                scrollToBottom(componentId);

                try {
                    Livewire.find(componentId)?.call('refreshMessages');
                } catch (error) {
                    //
                }
            }
        };

        window.addEventListener('start-ai-stream', (event) => {
            handleAiStream(event?.detail ?? {});
        });
    });
</script>
