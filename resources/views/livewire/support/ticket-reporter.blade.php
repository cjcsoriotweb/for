<div class="min-h-full bg-slate-100 py-6">
    <div class="mx-auto max-w-3xl space-y-6 px-4 sm:px-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h1 class="text-lg font-semibold text-slate-900">Signaler un bug</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Decrivez le probleme rencontre et ajoutez un maximum de details pour que l equipe support puisse vous aider rapidement.
                </p>
            </div>

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
                        <span wire:loading class="text-slate-500">Envoi...</span>
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
        </div>

        @if (count($recentTickets) > 0)
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-6 py-4">
                    <h2 class="text-sm font-semibold text-slate-800">Vos derniers tickets</h2>
                    <p class="mt-1 text-xs text-slate-500">Suivez l etat de vos demandes envoyees au support.</p>
                </div>

                <ul class="divide-y divide-slate-200">
                    @foreach ($recentTickets as $ticket)
                        <li class="px-6 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-slate-900">{{ $ticket['subject'] }}</p>
                                    <p class="text-xs text-slate-500">
                                        Cree {{ $ticket['created_at_human'] ?? '' }} &bull; Derniere activite {{ $ticket['last_message_human'] ?? '' }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ $ticket['status_label'] }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-8 text-center text-sm text-slate-500">
                Aucun ticket envoye pour le moment. Les signalements apparaitront ici apres l envoi.
            </div>
        @endif
    </div>
</div>
