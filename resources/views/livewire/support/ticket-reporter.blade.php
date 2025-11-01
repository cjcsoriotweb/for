<div class="min-h-full bg-slate-100 py-6">
    <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6">
  

            <form wire:submit.prevent="submit" class="space-y-5 px-6 py-6">
                @if ($sent)
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        Merci ! Votre signalement est bien enregistre. Nous vous recontacterons par e-mail des que possible.
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="subject" class="text-sm font-medium text-slate-700">Sujet</label>
                    <input
                        id="subject"
                        type="text"
                        wire:model.defer="subject"
                        autocomplete="off"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        placeholder="Exemple : Erreur lors de la validation d un quiz"
                    />
                    @error('subject')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="text-sm font-medium text-slate-700">Description</label>
                    <textarea
                        id="description"
                        rows="6"
                        wire:model.defer="description"
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        placeholder="Expliquez ce qui se passe, comment reproduire le bug et le contexte dans lequel il survient."
                    ></textarea>
                    @error('description')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-500">
                    <div>
                        Le lien de la page actuelle sera joint automatiquement a votre demande pour aider notre equipe.
                    </div>
                    <div class="flex items-center gap-2">
                        <span wire:loading.class="inline" wire:loading.class.remove="hidden" class="hidden text-slate-500">Envoi...</span>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-sky-400"
                        >
                            Envoyer
                        </button>
                    </div>
                </div>
            </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-sm font-semibold text-slate-800">Vos tickets et reponses</h2>
                <p class="mt-1 text-xs text-slate-500">Consultez les echanges avec le support et repondez si besoin.</p>
            </div>

            <div class="grid gap-0 border-t border-slate-200 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <div class="h-full divide-y divide-slate-200">
                        @forelse ($recentTickets as $ticket)
                            <button
                                type="button"
                                wire:click="selectTicket({{ $ticket['id'] }})"
                                wire:key="ticket-{{ $ticket['id'] }}"
                                class="flex w-full flex-col items-start gap-2 px-6 py-4 text-left transition hover:bg-slate-50 focus:outline-none focus-visible:bg-slate-50 {{ $activeTicketId === $ticket['id'] ? 'bg-slate-100' : '' }}"
                            >
                                <div class="flex w-full items-center justify-between gap-3">
                                    <p class="text-sm font-medium text-slate-900">{{ $ticket['subject'] }}</p>
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-700">
                                        {{ $ticket['status_label'] }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">
                                    Cree {{ $ticket['created_at_human'] ?? '' }} &bull; Derniere activite {{ $ticket['last_message_human'] ?? '' }}
                                </p>
                            </button>
                        @empty
                            <div class="px-6 py-8 text-sm text-slate-500">
                                Aucun ticket envoye pour le moment. Les signalements apparaitront ici apres l envoi.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="border-t border-slate-200 lg:col-span-3 lg:border-l lg:border-t-0">
                    @php
                        $statusColors = [
                            'open' => 'bg-amber-100 text-amber-800',
                            'pending' => 'bg-amber-100 text-amber-800',
                            'resolved' => 'bg-emerald-100 text-emerald-800',
                            'closed' => 'bg-slate-200 text-slate-700',
                        ];
                    @endphp

                    @if ($activeTicket)
                        <div class="flex h-full flex-col">
                            <div class="flex items-start justify-between gap-4 px-6 py-5">
                                <div>
                                    <h3 class="text-base font-semibold text-slate-900">{{ $activeTicket['subject'] }}</h3>
                                    <p class="text-xs text-slate-500">Cree {{ $activeTicket['created_at_human'] ?? '' }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusColors[$activeTicket['status']] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $activeTicket['status_label'] }}
                                </span>
                            </div>

                            <div class="flex-1 space-y-5 overflow-y-auto px-6 py-2">
                                @foreach ($activeTicket['messages'] as $message)
                                    <div class="flex {{ $message['is_support'] ? 'justify-start' : 'justify-end' }}">
                                        <div class="max-w-md rounded-2xl px-4 py-3 text-sm leading-relaxed {{ $message['is_support'] ? 'bg-sky-50 text-slate-900' : 'bg-slate-900 text-white' }}">
                                            <p>{!! nl2br(e($message['content'])) !!}</p>
                                            <p class="mt-2 text-[11px] font-medium {{ $message['is_support'] ? 'text-sky-700' : 'text-slate-300' }}">
                                                {{ $message['author'] }} &bull; {{ $message['created_at_human'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                                <form wire:submit.prevent="sendReply" class="space-y-3">
                                    <label for="reply" class="text-sm font-medium text-slate-700">Votre message</label>
                                    <textarea
                                        id="reply"
                                        rows="4"
                                        wire:model.defer="reply"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                        placeholder="Repondez au support ou ajoutez des informations complementaires."
                                    ></textarea>
                                    @error('reply')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-slate-500">Votre reponse sera ajoutee au ticket et notifiee au support.</p>
                                        <button
                                            type="submit"
                                            wire:loading.attr="disabled"
                                            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-400"
                                        >
                                            Envoyer la reponse
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex h-full items-center justify-center px-6 py-10 text-center text-sm text-slate-500">
                            Selectionnez un ticket dans la liste pour consulter les messages et repondre.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
