<div id="chat" class="fixed bottom-6 right-6 z-40" x-data="{ focusMessage() { $nextTick(() => document.getElementById('support-message-input')?.focus()); } }">
    @if ($showLauncher)
        <button
            wire:click="toggle"
            type="button"
            class="flex items-center justify-center rounded-full bg-blue-600 text-white shadow-lg transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 h-14 w-14"
            aria-haspopup="dialog"
            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
            aria-controls="support-chat-panel"
        >
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="9" r="2.75" />
                <path d="M19.5 12V9a7.5 7.5 0 0 0-15 0v3" />
                <path d="M4.5 12v3a2.25 2.25 0 0 0 2.25 2.25H8.1" />
                <path d="M19.5 12v3a2.25 2.25 0 0 1-2.25 2.25H15.9" />
                <path d="M8.25 19.5a3.75 3.75 0 0 1 7.5 0" />
            </svg>
        </button>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

    @if ($isOpen)
        <div
            id="support-chat-panel"
            class="mt-4 w-96 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-900"
        >
            <div class="flex items-start justify-between border-b border-slate-200 bg-slate-50 px-4 py-3 dark:border-slate-800 dark:bg-slate-800">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ __('Support Formation') }}
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ __('Besoin daide ? Discutez avec notre equipe.') }}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <button
                        type="button"
                        wire:click="loadTickets"
                        class="rounded-full p-1 text-slate-500 transition hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:text-slate-300 dark:hover:text-white"
                        title="{{ __('Actualiser') }}"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4.5 12a7.5 7.5 0 0 1 12.223-5.657" />
                            <path d="M16.5 6.343V3.75h2.593" />
                            <path d="M19.5 12a7.5 7.5 0 0 1-12.223 5.657" />
                            <path d="M7.5 17.657V20.25H4.907" />
                        </svg>
                    </button>
                    <button
                        type="button"
                        wire:click="toggle"
                        class="rounded-full p-1 text-slate-500 transition hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:text-slate-300 dark:hover:text-white"
                        title="{{ __('Fermer la fenetre de support') }}"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 18 18 6" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex h-[28rem] flex-col">
                <div class="border-b border-slate-200 px-4 py-2 dark:border-slate-800">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                            {{ __('Mes tickets') }}
                        </h2>
                        <div class="flex items-center space-x-2">
                            @if (! $showNewTicketForm)
                                <button
                                    type="button"
                                    wire:click="showNewTicket"
                                    class="flex items-center rounded-full bg-blue-600 px-3 py-1 text-xs font-medium text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                >
                                    <svg class="mr-1 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                        aria-hidden="true">
                                        <path d="M12 5v14" />
                                        <path d="M5 12h14" />
                                    </svg>
                                    {{ __('Nouveau ticket') }}
                                </button>
                            @else
                                <button
                                    type="button"
                                    wire:click="cancelNewTicket"
                                    class="text-xs font-medium text-blue-600 transition hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                >
                                    {{ __('Annuler') }}
                                </button>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const dispatchToLivewire = () => {
                document.querySelectorAll('[wire\\:id]').forEach((el) => {
                    try { window.Livewire.find(el.getAttribute('wire:id')).call('toggle'); } catch (e) {}
                });
            };

            window.addEventListener('support-toggle', dispatchToLivewire);
        })();
    </script>

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</div>
                    </div>

                    @if (! $showNewTicketForm && ! $activeTicket)
                        <div class="mt-2 flex flex-col space-y-2 max-h-32 overflow-y-auto pr-1">
                            @forelse ($tickets as $ticket)
                                <button
                                    type="button"
                                    wire:click="selectTicket({{ $ticket['id'] }})"
                                    class="flex w-full flex-col rounded-lg border border-transparent bg-slate-100 px-3 py-2 text-left text-xs transition hover:border-blue-400 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-slate-800 dark:hover:bg-slate-700"
                                >
                                    <span class="font-semibold text-slate-700 dark:text-slate-100">
                                        {{ $ticket['subject'] }}
                                    </span>
                                    @if (!empty($ticket['origin_label']))
                                        <span class="mt-1 inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-600 dark:bg-blue-900/40 dark:text-blue-200">
                                            {{ $ticket['origin_label'] }}
                                        </span>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

<span class="mt-1 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                                        <span class="rounded-full bg-slate-200 px-2 py-0.5 text-[10px] font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                                            {{ $ticket['status_label'] }}
                                        </span>
                                        <span>{{ $ticket['last_message_human'] }}</span>
                                    </span>
                                </button>
                            @empty
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Aucun ticket pour le moment. Creez votre premier message de support.') }}
                                </p>
                            @endforelse
                        </div>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</div>

                <div class="flex-1 overflow-y-auto px-4 py-3 bg-white dark:bg-slate-900">
                    @if ($showNewTicketForm)
                        <form wire:submit.prevent="createTicket" class="flex h-full flex-col space-y-3">
                            <div>
                                <label for="support-subject" class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    {{ __('Sujet') }}
                                </label>
                                <input
                                    id="support-subject"
                                    type="text"
                                    wire:model.defer="subject"
                                    class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                    placeholder="{{ __('Ex: Probleme avec mon acces') }}"
                                    maxlength="120"
                                />
                                @error('subject')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex-1">
                                <label for="support-message" class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                    {{ __('Message') }}
                                </label>
                                <textarea
                                    id="support-message"
                                    wire:model.defer="message"
                                    rows="5"
                                    class="mt-1 w-full flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                    placeholder="{{ __('Decrivez votre probleme le plus precisement possible...') }}"
                                ></textarea>
                                @error('message')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end space-x-2">
                                <button
                                    type="button"
                                    wire:click="cancelNewTicket"
                                    class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-300 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                                >
                                    {{ __('Annuler') }}
                                </button>
                                <button
                                    type="submit"
                                    class="flex items-center rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                >
                                    <svg class="mr-1 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                        aria-hidden="true">
                                        <path d="m4.5 5.25 15 6.75-15 6.75L7.5 12z" />
                                        <path d="M7.5 12h6" />
                                    </svg>
                                    {{ __('Envoyer au support') }}
                                </button>
                            </div>
                        </form>
                    @elseif ($activeTicket)
                        <div class="flex h-full flex-col space-y-3">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                        {{ $activeTicket['subject'] }}
                                    </h3>
                                    @if (!empty($activeTicket['origin']['label']))
                                        <p class="mt-1 text-[11px] text-slate-400 dark:text-slate-500">
                                            {{ __('Contexte') }} :
                                            @if (!empty($activeTicket['origin']['path']))
                                                <a
                                                    href="{{ $activeTicket['origin']['path'] }}"
                                                    class="underline hover:text-blue-600"
                                                    target="_blank"
                                                    rel="noopener"
                                                >
                                                    {{ $activeTicket['origin']['label'] }}
                                                </a>
                                            @else
                                                {{ $activeTicket['origin']['label'] }}
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</p>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

<span class="mt-1 inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600 dark:bg-blue-900/40 dark:text-blue-200">
                                        {{ $activeTicket['status_label'] }}
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    wire:click="backToList"
                                    class="text-xs font-medium text-blue-600 transition hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                >
                                    {{ __('Retour aux tickets') }}
                                </button>
                            </div>

                            <div class="flex-1 space-y-3 overflow-y-auto pr-1">
                                @foreach ($activeTicket['messages'] as $messageItem)
                                    <div class="flex flex-col {{ $messageItem['is_support'] ? 'items-start' : 'items-end' }} space-y-1">
                                        <div
                                            class="{{ $messageItem['is_support'] ? 'rounded-xl rounded-tl-sm bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-100' : 'rounded-xl rounded-tr-sm bg-blue-600 text-white' }} max-w-[85%] px-3 py-2 text-xs leading-relaxed shadow-sm"
                                        >
                                            <p>{{ $messageItem['content'] }}</p>
                                        </div>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500">
                                            {{ $messageItem['author'] }} - {{ $messageItem['created_at_human'] }}
                                        </span>
                                        @if (!empty($messageItem['context_label']))
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500">
                                                {{ __('Page') }} :
                                                @if (!empty($messageItem['context_path']))
                                                    <a
                                                        href="{{ $messageItem['context_path'] }}"
                                                        class="underline hover:text-blue-600"
                                                        target="_blank"
                                                        rel="noopener"
                                                    >
                                                        {{ $messageItem['context_label'] }}
                                                    </a>
                                                @else
                                                    {{ $messageItem['context_label'] }}
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</span>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</div>
                                @endforeach
                            </div>

                            <form wire:submit.prevent="sendMessage" class="space-y-2">
                                <label for="support-message-input" class="sr-only">{{ __('Votre message') }}</label>
                                <textarea
                                    id="support-message-input"
                                    wire:model.defer="message"
                                    rows="2"
                                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200"
                                    placeholder="{{ __('Ecrivez votre reponse ici...') }}"
                                    x-on:focus="focusMessage"
                                ></textarea>
                                @error('message')
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <div class="flex items-center justify-between">
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        {{ __('Les reponses du support arrivent sur votre email et ici.') }}
                                    </p>
                                    <button
                                        type="submit"
                                        class="flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                    >
                                        <svg class="mr-1 h-4 w-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" aria-hidden="true">
                                            <path d="m4.5 5.25 15 6.75-15 6.75L7.5 12z" />
                                            <path d="M7.5 12h6" />
                                        </svg>
                                        {{ __('Envoyer') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="flex h-full flex-col items-center justify-center space-y-3 text-center text-slate-500 dark:text-slate-400">
                            <svg class="h-10 w-10 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path
                                    d="M4.5 6.75A2.25 2.25 0 0 1 6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v7.5A2.25 2.25 0 0 1 17.25 16.5H9l-4.5 3v-3H6.75A2.25 2.25 0 0 1 4.5 14.25z" />
                            </svg>
                            <p class="text-sm font-medium">
                                {{ __('Selectionnez un ticket pour voir la conversation ou creez un nouveau message.') }}
                            </p>
                            <button
                                type="button"
                                wire:click="showNewTicket"
                                class="rounded-full border border-blue-500 px-4 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                                {{ __('Creer un ticket') }}
                            </button>
                        </div>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</div>
            </div>
        </div>
    @endif

    <script>
        (function () {
            if (window.__supportWidgetBound) { return; }
            window.__supportWidgetBound = true;

            const toggleViaEvent = () => {
                try { window.Livewire.find(@this.__instance.id).call('toggle'); } catch (e) {}
            };

            window.addEventListener('support-toggle', toggleViaEvent);
        })();
    </script>

</div>

