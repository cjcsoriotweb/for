<div class="grid grid-cols-1 gap-6 lg:grid-cols-3" wire:poll.8s="pollTickets">
    <div class="flex flex-col rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
        <div class="border-b border-slate-200 px-4 py-4 dark:border-slate-800">
            <h2 class="text-base font-semibold text-slate-800 dark:text-white">
                {{ __('Tickets support') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Suivez les demandes et repondez aux utilisateurs.') }}
            </p>
        </div>

        <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-800">
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                {{ __('Rechercher') }}
            </label>
            <div class="mt-2">
                <input
                    type="search"
                    wire:model.debounce.500ms="search"
                    placeholder="{{ __('Sujet, email ou nom') }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
                />
            </div>
        </div>

        <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-800">
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                {{ __('Filtrer par statut') }}
            </label>
            <select
                wire:model="statusFilter"
                class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
            >
                @foreach ($statusOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 overflow-y-auto px-2 py-3">
            <div class="space-y-2">
                @forelse ($tickets as $ticket)
                    <button
                        type="button"
                        wire:click="selectTicket({{ $ticket['id'] }})"
                        class="w-full rounded-xl border {{ $activeTicketId === $ticket['id'] ? 'border-blue-500 bg-blue-50 dark:border-blue-400 dark:bg-blue-900/30' : 'border-transparent bg-slate-100 dark:bg-slate-800' }} px-3 py-3 text-left transition hover:border-blue-300 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ $ticket['subject'] }}
                            </p>
                            <span class="rounded-full bg-white px-2 py-0.5 text-[11px] font-medium text-slate-600 dark:bg-slate-900 dark:text-slate-300">
                                {{ $ticket['status_label'] }}
                            </span>
                        </div>
                        @if (!empty($ticket['origin_label']))
                            <p class="mt-1 text-[11px] font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-300">
                                {{ $ticket['origin_label'] }}
                            </p>
                        @endif
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ $ticket['owner']['name'] ?? __('Utilisateur inconnu') }}
                        </p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500">
                            {{ $ticket['owner']['email'] }}
                        </p>
                        <p class="mt-2 text-[11px] text-slate-400 dark:text-slate-500">
                            {{ $ticket['last_message_human'] }}
                        </p>
                    </button>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 p-4 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                        {{ __('Aucun ticket ne correspond a cette selection.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            @if ($activeTicket)
                <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                    {{ $activeTicket['subject'] }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ $activeTicket['owner']['name'] ?? __('Utilisateur inconnu') }} - {{ $activeTicket['owner']['email'] }}
                                </p>
                                @if (!empty($activeTicket['origin']['label']))
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                        {{ __('Contexte') }} :
                                        @if (!empty($activeTicket['origin']['path']))
                                            <a
                                                href="{{ $activeTicket['origin']['path'] }}"
                                                target="_blank"
                                                rel="noopener"
                                                class="underline hover:text-blue-600"
                                            >
                                                {{ $activeTicket['origin']['label'] }}
                                            </a>
                                        @else
                                            {{ $activeTicket['origin']['label'] }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-600 dark:bg-blue-900/40 dark:text-blue-200">
                                    {{ $activeTicket['status_label'] }}
                                </span>
                            <button
                                type="button"
                                wire:click="reopenTicket"
                                class="rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800"
                            >
                                {{ __('Reouvrir') }}
                            </button>
                            <button
                                type="button"
                                wire:click="markResolved"
                                class="rounded-full border border-green-200 px-3 py-1 text-xs font-medium text-green-700 transition hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-200 dark:border-green-700 dark:text-green-300 dark:hover:bg-green-900/30"
                            >
                                {{ __('Marquer resolu') }}
                            </button>
                            <button
                                type="button"
                                wire:click="closeTicket"
                                class="rounded-full border border-red-200 px-3 py-1 text-xs font-medium text-red-600 transition hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-200 dark:border-red-700 dark:text-red-300 dark:hover:bg-red-900/30"
                            >
                                {{ __('Fermer') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-5">
                            <div class="space-y-4">
                                @foreach ($activeTicket['messages'] as $messageItem)
                                    <div class="flex flex-col {{ $messageItem['is_support'] ? 'items-end' : 'items-start' }} space-y-2">
                                        <div class="{{ $messageItem['is_support'] ? 'rounded-xl rounded-tr-sm bg-blue-600 text-white' : 'rounded-xl rounded-tl-sm bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-100' }} max-w-[80%] px-4 py-3 text-sm leading-relaxed shadow-sm">
                                            <p class="text-xs font-semibold uppercase tracking-wide">
                                                {{ $messageItem['author'] }}
                                            </p>
                                            <p class="mt-1 whitespace-pre-wrap text-sm">{{ $messageItem['content'] }}</p>
                                        </div>
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                            {{ $messageItem['created_at_human'] }}
                                        </span>
                                        @if (!empty($messageItem['context_label']))
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500">
                                                {{ __('Page') }} :
                                                @if (!empty($messageItem['context_path']))
                                                    <a
                                                        href="{{ $messageItem['context_path'] }}"
                                                        target="_blank"
                                                        rel="noopener"
                                                        class="underline hover:text-blue-500"
                                                    >
                                                        {{ $messageItem['context_label'] }}
                                                    </a>
                                                @else
                                                    {{ $messageItem['context_label'] }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                    </div>
                </div>

                <div class="border-t border-slate-200 px-6 py-5 dark:border-slate-800">
                    <form wire:submit.prevent="sendResponse" class="space-y-3">
                        <label for="support-admin-message" class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            {{ __('Repondre a lutilisateur') }}
                        </label>
                        <textarea
                            id="support-admin-message"
                            wire:model.defer="message"
                            rows="4"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
                            placeholder="{{ __('Tapez votre reponse ici...') }}"
                        ></textarea>
                        @error('message')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                {{ __('Les utilisateurs sont notifies par email et via le widget.') }}
                            </p>
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            >
                                <svg class="mr-1 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="m4.5 5.25 15 6.75-15 6.75L7.5 12z" />
                                    <path d="M7.5 12h6" />
                                </svg>
                                {{ __('Envoyer la reponse') }}
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="flex h-full flex-col items-center justify-center space-y-4 text-center">
                    <svg class="h-14 w-14 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="9" r="3" />
                        <path d="M19.5 12V9a7.5 7.5 0 0 0-15 0v3" />
                        <path d="M4.5 12v3a2.25 2.25 0 0 0 2.25 2.25H8.1" />
                        <path d="M19.5 12v3a2.25 2.25 0 0 1-2.25 2.25H15.9" />
                        <path d="M8.25 20.25a3.75 3.75 0 0 1 7.5 0" />
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                            {{ __('Selectionnez un ticket') }}
                        </h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Choisissez un ticket dans la liste pour afficher la conversation.') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
