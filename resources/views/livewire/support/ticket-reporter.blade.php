<div class="flex h-full flex-col bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-white">
    <header class="border-b border-white/10 px-6 py-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Centre support</p>
                <h1 class="mt-2 text-xl font-semibold text-white">Signaler un bug</h1>
                <p class="mt-1 text-sm text-slate-300">
                    Faites-nous part d un dysfonctionnement et suivez vos echanges avec l equipe support directement depuis ce dock.
                </p>
            </div>
            <div class="hidden shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-3 shadow-lg backdrop-blur sm:flex">
                <svg class="h-9 w-9 text-sky-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m10.5 6.75 2.25 2.25-2.25 2.25m3-4.5 2.25 2.25-2.25 2.25M6 18h6m-6-3h6m2.24-.94 1.42 1.42a1.5 1.5 0 0 0 2.12 0l1.06-1.06a1.5 1.5 0 0 0 0-2.12l-7.07-7.07a3 3 0 0 0-4.24 0L6.56 7.94a3 3 0 0 0 0 4.24l1.42 1.42" />
                </svg>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="space-y-6">
            <div class="rounded-2xl border border-white/10 bg-white/5 shadow-xl shadow-slate-950/40 backdrop-blur">
                <div class="flex items-center gap-3 border-b border-white/10 px-6 py-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-sky-400/20 text-sky-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376 7.907-13.68c.564-.976 1.986-.97 2.543.01l7.763 13.68c.55.968-.147 2.19-1.27 2.19H3.02c-1.11 0-1.807-1.194-1.32-2.2Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75h.007v.007H12v-.007Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-base font-semibold text-white">Nouveau signalement</h2>
                        <p class="text-xs text-slate-300">Expliquez ce qui s est passe, nous analysons generalement sous 24h.</p>
                    </div>
                </div>

                <form wire:submit.prevent="submit" class="space-y-5 px-6 py-6">
                    @if ($sent)
                        <div class="rounded-xl border border-emerald-300/40 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200 shadow-inner shadow-emerald-500/20">
                            Merci ! Votre ticket est bien enregistre. Nous revenons vers vous des que possible.
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label for="subject" class="text-sm font-medium text-slate-200">Sujet</label>
                        <input
                            id="subject"
                            type="text"
                            wire:model.defer="subject"
                            autocomplete="off"
                            class="w-full rounded-lg border border-white/20 bg-white/5 px-3 py-2 text-sm text-white shadow-sm shadow-black/20 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500/50"
                            placeholder="Exemple : Erreur lors de la validation d un quiz"
                        />
                        @error('subject')
                            <p class="text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-medium text-slate-200">Description detaillee</label>
                        <textarea
                            id="description"
                            rows="5"
                            wire:model.defer="description"
                            class="w-full rounded-lg border border-white/20 bg-white/5 px-3 py-2 text-sm text-white shadow-sm shadow-black/20 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-500/50"
                            placeholder="Decrivez les etapes pour reproduire le bug, les messages d erreur, ou ajoutez des details utiles."
                        ></textarea>
                        @error('description')
                            <p class="text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-xs text-slate-300">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-500/20 text-sky-200">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-3A2.25 2.25 0 0 0 8.25 5.25V9m-3 3h13.5m-11.25 3v3A2.25 2.25 0 0 0 9.75 20.25h4.5A2.25 2.25 0 0 0 16.5 18v-3" />
                                </svg>
                            </span>
                            <span>Le lien de la page actuelle est joint pour aider l equipe.</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span wire:loading.delay.inline wire:target="submit" class="text-slate-400">Envoi...</span>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-300 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:cursor-not-allowed disabled:bg-sky-700/60"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 12 5-9m0 0 5 9m-5-9v13.5m0 0L9 21m2-3.5 2 3.5" />
                                </svg>
                                Envoyer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 shadow-xl shadow-slate-950/40 backdrop-blur">
                <div class="flex items-center justify-between gap-3 border-b border-white/10 px-6 py-4">
                    <div>
                        <h2 class="text-base font-semibold text-white">Mes tickets</h2>
                        <p class="text-xs text-slate-300">Suivez les reponses du support et fournissez des informations supplementaires.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-200">
                        @choice(':count ticket|:count tickets', count($recentTickets), ['count' => count($recentTickets)])
                    </span>
                </div>

                @php
                    $statusColors = [
                        'open' => 'border-amber-300/50 bg-amber-400/15 text-amber-100',
                        'pending' => 'border-amber-300/50 bg-amber-400/15 text-amber-100',
                        'resolved' => 'border-emerald-300/60 bg-emerald-400/20 text-emerald-100',
                        'closed' => 'border-slate-300/30 bg-slate-400/10 text-slate-200',
                    ];
                @endphp

                <div class="space-y-4 px-6 py-5">
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @forelse ($recentTickets as $ticket)
                            <button
                                type="button"
                                wire:key="ticket-{{ $ticket['id'] }}"
                                wire:click="selectTicket({{ $ticket['id'] }})"
                                class="group flex min-w-[220px] flex-1 cursor-pointer flex-col gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-left transition hover:border-sky-300/60 hover:bg-sky-400/10 {{ $activeTicketId === $ticket['id'] ? 'border-sky-400/70 bg-sky-500/15 shadow-lg shadow-sky-900/40' : '' }}"
                            >
                                <div class="flex items-center justify-between gap-2">
                                    <p class="line-clamp-1 text-sm font-semibold text-white">{{ $ticket['subject'] }}</p>
                                    <span class="shrink-0 rounded-full border {{ $statusColors[$ticket['status']] ?? 'border-slate-300/30 bg-slate-400/15 text-slate-200' }} px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide">
                                        {{ $ticket['status_label'] }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-300">
                                    Cree {{ $ticket['created_at_human'] ?? '' }}<span class="mx-1 text-slate-500">•</span>Derniere activite {{ $ticket['last_message_human'] ?? '' }}
                                </p>
                            </button>
                        @empty
                            <div class="flex min-h-[120px] w-full items-center justify-center rounded-2xl border border-dashed border-white/20 bg-white/5 px-4 py-6 text-sm text-slate-300">
                                Aucun ticket pour le moment. Vos conversations apparaitront ici.
                            </div>
                        @endforelse
                    </div>

                    <div class="relative">
                        <div
                            wire:loading.flex
                            wire:target="selectTicket,sendReply"
                            class="absolute inset-0 z-20 hidden items-center justify-center rounded-2xl border border-white/10 bg-slate-950/70 backdrop-blur"
                        >
                            <div class="flex items-center gap-3 text-sm text-slate-200">
                                <svg class="h-4 w-4 animate-spin text-sky-300" viewBox="0 0 24 24">
                                    <circle class="opacity-30" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none"/>
                                    <path class="opacity-80" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v3a5 5 0 0 0-5 5H4z"/>
                                </svg>
                                Mise a jour...
                            </div>
                        </div>

                        <div class="flex min-h-[240px] flex-col gap-4 rounded-2xl border border-white/10 bg-slate-950/40 px-5 py-5 shadow-inner shadow-black/40">
                            @if ($activeTicket)
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-base font-semibold text-white">{{ $activeTicket['subject'] }}</h3>
                                        <p class="text-[11px] uppercase tracking-wide text-slate-400">Cree {{ $activeTicket['created_at_human'] ?? '' }}</p>
                                    </div>
                                    <span class="rounded-full border {{ $statusColors[$activeTicket['status']] ?? 'border-slate-300/30 bg-slate-400/15 text-slate-200' }} px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                                        {{ $activeTicket['status_label'] }}
                                    </span>
                                </div>

                                <div class="flex-1 space-y-3 overflow-y-auto pr-2 text-sm text-slate-200">
                                    @foreach ($activeTicket['messages'] as $message)
                                        <div class="flex {{ $message['is_support'] ? 'justify-start' : 'justify-end' }}">
                                            <div class="max-w-[85%] rounded-2xl px-4 py-3 shadow-lg {{ $message['is_support'] ? 'bg-white/10 text-slate-50 shadow-sky-950/30' : 'bg-sky-500/90 text-white shadow-sky-900/40' }}">
                                                <p class="whitespace-pre-line leading-relaxed">{!! nl2br(e($message['content'])) !!}</p>
                                                <p class="mt-2 text-[11px] font-semibold uppercase tracking-wide {{ $message['is_support'] ? 'text-slate-300' : 'text-white/80' }}">
                                                    {{ $message['author'] }}<span class="mx-1 text-slate-500">•</span>{{ $message['created_at_human'] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <form wire:submit.prevent="sendReply" class="space-y-3">
                                    <label for="reply" class="text-xs font-medium uppercase tracking-wide text-slate-300">Votre reponse</label>
                                    <textarea
                                        id="reply"
                                        rows="3"
                                        wire:model.defer="reply"
                                        class="w-full rounded-lg border border-white/20 bg-white/10 px-3 py-2 text-sm text-white shadow-sm shadow-black/20 placeholder:text-slate-400 focus:border-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200/60"
                                        placeholder="Ajoutez des informations, repondez a l equipe support..."
                                    ></textarea>
                                    @error('reply')
                                        <p class="text-sm text-rose-300">{{ $message }}</p>
                                    @enderror

                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-[11px] text-slate-400">Votre message sera ajoute au ticket et notifie au support.</p>
                                        <button
                                            type="submit"
                                            wire:loading.attr="disabled"
                                            wire:target="sendReply"
                                            class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-slate-900/30 transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:cursor-not-allowed disabled:bg-slate-500/30 disabled:text-slate-300"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H3m0 0 6 6m-6-6 6-6" />
                                            </svg>
                                            Envoyer une reponse
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="flex h-full items-center justify-center text-sm text-slate-300">
                                    Selectionnez un ticket pour afficher la conversation.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
