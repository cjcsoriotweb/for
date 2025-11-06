@php
    $statusColors = [
        'open' => 'border-amber-300/50 bg-amber-400/15 text-amber-800',
        'pending' => 'border-amber-300/50 bg-amber-400/15 text-amber-800',
        'resolved' => 'border-emerald-300/60 bg-emerald-400/20 text-emerald-800',
        'closed' => 'border-slate-300/30 bg-slate-400/10 text-slate-700',
    ];

    $modeTitles = [
        'overview' => 'Centre support',
        'detail' => 'Suivre mon ticket',
        'create' => 'Creer un ticket',
    ];

    $modeDescriptions = [
        'overview' => 'Signalez un probleme, consultez vos tickets recents et suivez les reponses du support.',
        'detail' => 'Consultez la conversation de votre ticket et apportez des precisions si besoin.',
        'create' => 'Decrivez votre situation pour nous aider a comprendre et a traiter votre demande.',
    ];
@endphp

<div class="flex h-full w-full flex-col overflow-hidden bg-white text-slate-900">
    <header class="relative overflow-hidden border-b border-slate-200 px-6 py-6">
        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/5 via-transparent to-purple-500/5"></div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex-1 space-y-3">
                <div class="inline-flex items-center gap-2 rounded-full border border-sky-400/20 bg-sky-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.35em] text-sky-700">
                    <span class="material-symbols-outlined text-sm">support</span>
                    {{ __('Support') }}
                </div>
                <h1 class="text-2xl font-bold text-slate-900">
                    {{ __($modeTitles[$mode] ?? $modeTitles['overview']) }}
                </h1>
                <p class="text-sm leading-relaxed text-slate-600">
                    {{ __($modeDescriptions[$mode] ?? $modeDescriptions['overview']) }}
                </p>
            </div>
            <div class="hidden shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-gradient-to-br from-sky-500/10 to-purple-500/10 p-4 shadow-xl backdrop-blur-xl sm:flex">
                <span class="material-symbols-outlined text-4xl text-sky-700">confirmation_number</span>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 h-px w-full bg-gradient-to-r from-transparent via-sky-400/30 to-transparent"></div>
    </header>

    <div class="flex-1 overflow-x-hidden overflow-y-auto px-6 pb-6">
        <div class="space-y-6">
            @if ($mode !== 'create')
                <div class="grid gap-6 lg:grid-cols-[minmax(0,320px),1fr]">
                    <div class="space-y-4">
                        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                            <div class="relative flex items-center justify-between gap-3 border-b border-slate-200 px-6 py-5">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-lg text-sky-700">forum</span>
                                        <h2 class="text-lg font-semibold text-slate-900">{{ __('Mes tickets') }}</h2>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-600">
                                        {{ __('Suivez vos echanges recents avec l equipe support.') }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center gap-2 rounded-full border border-sky-400/30 bg-sky-500/20 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-sky-700">
                                    <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                    @choice(':count ticket|:count tickets', count($recentTickets), ['count' => count($recentTickets)])
                                </span>
                            </div>

                            <div class="relative space-y-4 px-6 py-5">
                                <div class="grid gap-10 overflow-x-auto pb-2" style="overflow-x:hidden">
                                    @forelse ($recentTickets as $ticket)
                                        <button
                                            type="button"
                                            wire:key="ticket-{{ $ticket['id'] }}"
                                            wire:click="selectTicket({{ $ticket['id'] }})"
                                            class="group relative flex min-w-[220px] flex-1 flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-left transition-all duration-300 hover:border-sky-400/50 hover:bg-sky-50 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-sky-400/50 {{ $activeTicketId === $ticket['id'] ? 'border-sky-400 bg-sky-50 shadow-lg' : '' }}"
                                        >
                                            @if ($activeTicketId === $ticket['id'])
                                                <div class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-sky-500 shadow-lg">
                                                    <span class="material-symbols-outlined text-[15px] text-white">check</span>
                                                </div>
                                            @endif

                                            <div class="flex items-start justify-between gap-2">
                                                <h3 class="text-sm font-semibold text-slate-900 leading-5 line-clamp-2">{{ $ticket['subject'] }}</h3>
                                                <span class="rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-wide {{ $statusColors[$ticket['status']] ?? 'border-slate-300/30 bg-slate-400/15 text-slate-700' }}">
                                                    {{ $ticket['status_label'] }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">
                                                {{ __('Dernier message : :date', ['date' => $ticket['last_message_human'] ?? '']) }}
                                            </p>

                                            <div class="absolute bottom-2 right-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                                <span class="material-symbols-outlined text-lg text-sky-700">arrow_forward</span>
                                            </div>
                                        </button>
                                    @empty
                                        <div class="flex min-h-[140px] w-full flex-col items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center">
                                            <div class="mb-3 rounded-full bg-sky-500/20 p-3">
                                                <span class="material-symbols-outlined text-2xl text-sky-700">forum</span>
                                            </div>
                                            <p class="text-sm font-medium text-slate-600">{{ __('Aucun ticket pour le moment') }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ __('Vos conversations apparaitront ici.') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            wire:loading.flex
                            wire:target="selectTicket,sendReply"
                            class="absolute inset-0 z-20 hidden items-center justify-center rounded-2xl border border-slate-200 bg-white/70 backdrop-blur"
                        >
                            <div class="flex items-center gap-3 text-sm text-slate-700">
                                <svg class="h-4 w-4 animate-spin text-sky-700" viewBox="0 0 24 24">
                                    <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none"></circle>
                                    <path class="opacity-80" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4z"></path>
                                </svg>
                                {{ __('Mise a jour...') }}
                            </div>
                        </div>

                        <div class="flex min-h-[260px] flex-col gap-4 rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-inner">
                            @if ($activeTicket)
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-base font-semibold text-slate-900">{{ $activeTicket['subject'] }}</h3>
                                        <p class="text-[11px] uppercase tracking-wide text-slate-500">
                                            {{ __('Cree :date', ['date' => $activeTicket['created_at_human'] ?? '']) }}
                                        </p>
                                    </div>
                                    <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusColors[$activeTicket['status']] ?? 'border-slate-300/30 bg-slate-400/15 text-slate-700' }}">
                                        {{ $activeTicket['status_label'] }}
                                    </span>
                                </div>

                                <div class="flex-1 space-y-3 overflow-y-auto pr-2 text-sm text-slate-700">
                                    @foreach ($activeTicket['messages'] as $message)
                                        <div class="flex {{ $message['is_support'] ? 'justify-start' : 'justify-end' }}">
                                            <div class="max-w-[85%] rounded-2xl px-4 py-3 shadow-lg {{ $message['is_support'] ? 'bg-slate-100 text-slate-900' : 'bg-sky-500 text-white' }}">
                                                <p class="whitespace-pre-line leading-relaxed">{!! nl2br(e($message['content'])) !!}</p>
                                                <p class="mt-2 text-[11px] font-semibold uppercase tracking-wide {{ $message['is_support'] ? 'text-slate-500' : 'text-sky-100' }}">
                                                    {{ $message['author'] }}<span class="mx-1 text-slate-400">&bull;</span>{{ $message['created_at_human'] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <form wire:submit.prevent="sendReply" class="space-y-3">
                                    <label for="reply" class="text-xs font-medium uppercase tracking-wide text-slate-600">{{ __('Votre reponse') }}</label>
                                    <textarea
                                        id="reply"
                                        rows="3"
                                        wire:model.defer="reply"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500/50"
                                        placeholder="{{ __('Ajoutez des informations pour aider le support...') }}"
                                    ></textarea>
                                    @error('reply')
                                        <p class="text-sm text-rose-500">{{ $message }}</p>
                                    @enderror

                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-[11px] text-slate-500">
                                            {{ __('Votre message sera ajoute au ticket et notifie au support.') }}
                                        </p>
                                        <button
                                            type="submit"
                                            wire:loading.attr="disabled"
                                            wire:target="sendReply"
                                            class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-lg transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 focus:ring-offset-white disabled:cursor-not-allowed disabled:bg-slate-400 disabled:text-slate-200"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H3m0 0 6 6m-6-6 6-6"></path>
                                            </svg>
                                            {{ __('Envoyer une reponse') }}
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-slate-500">
                                    {{ __('Selectionnez un ticket pour afficher la conversation.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if ($mode !== 'detail')
                <div class="mt-5 relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                    <div class="relative flex items-center gap-3 border-b border-slate-200 px-6 py-5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-amber-400/30 bg-gradient-to-br from-amber-500/20 to-red-500/20">
                            <span class="material-symbols-outlined text-xl text-amber-700">bug_report</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('Nouveau ticket') }}</h2>
                            <p class="text-xs text-slate-600">
                                {{ __('Expliquez la situation avec le plus de details possible.') }}
                            </p>
                        </div>
                    </div>

                    <form wire:submit.prevent="submit" class="relative space-y-5 px-6 py-6">
                        @if ($sent)
                            <div class="rounded-xl border border-emerald-300/40 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-inner">
                                {{ __('Merci ! Votre ticket est bien enregistre. Nous revenons vers vous des que possible.') }}
                            </div>
                        @endif

                        <div class="space-y-2">
                            <label for="subject" class="text-sm font-medium text-slate-700">{{ __('Sujet') }}</label>
                            <input
                                id="subject"
                                type="text"
                                wire:model.defer="subject"
                                autocomplete="off"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500/50"
                                placeholder="{{ __('Exemple : Erreur lors de la validation d un quiz') }}"
                            />
                            @error('subject')
                                <p class="text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="text-sm font-medium text-slate-700">{{ __('Description detaillee') }}</label>
                            <textarea
                                id="description"
                                rows="5"
                                wire:model.defer="description"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500/50"
                                placeholder="{{ __('Decrivez les etapes, les messages d erreur ou ajoutez des details utiles.') }}"
                            ></textarea>
                            @error('description')
                                <p class="text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-500/20 text-sky-700">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-3A2.25 2.25 0 0 0 8.25 5.25V9m-3 3h13.5m-11.25 3v3A2.25 2.25 0 0 0 9.75 20.25h4.5A2.25 2.25 0 0 0 16.5 18v-3"></path>
                                    </svg>
                                </span>
                                {{ __('Les informations sont partagees uniquement avec le support.') }}
                            </div>
                            <span wire:loading.delay.inline wire:target="submit" class="text-slate-500">{{ __('Envoi...') }}</span>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-5 py-2 text-sm font-semibold text-white shadow-lg transition hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:ring-offset-2 focus:ring-offset-white disabled:cursor-not-allowed disabled:bg-sky-300 disabled:text-slate-400"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0 5.25-5.25M12 19.5 6.75 14.25"></path>
                                </svg>
                                {{ __('Soumettre le ticket') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
