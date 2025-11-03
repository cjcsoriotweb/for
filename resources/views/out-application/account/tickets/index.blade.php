<x-app-layout>
    <div class="space-y-8">
        <section
            class="relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white/80 px-8 py-10 shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/50 dark:shadow-none sm:px-12">
            <div class="absolute right-[-60px] top-[-60px] h-48 w-48 rounded-full bg-blue-500/20 blur-3xl dark:bg-blue-400/10"></div>
            <div class="relative grid gap-6 md:grid-cols-[auto,160px] md:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-blue-500 dark:text-blue-300">
                        {{ __('Assistance Evolubat') }}
                    </p>
                    <h1 class="mt-4 max-w-2xl text-3xl font-bold text-slate-900 dark:text-white">
                        {{ __('Gerez vos tickets de support') }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-base text-slate-600 dark:text-slate-300">
                        {{ __('Suivez vos demandes en cours, consultez l\'historique et contactez rapidement le support.') }}
                    </p>
                </div>
                <div class="hidden md:flex md:justify-end">
                    <div class="flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-500 to-purple-600 text-white shadow-lg shadow-blue-500/40">
                        <span class="material-symbols-outlined text-4xl">confirmation_number</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[2fr,1fr]">
            <div class="space-y-6">
                <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 bg-white/90 px-6 py-4 shadow-md shadow-slate-200/50 dark:border-slate-700/60 dark:bg-slate-900/60 dark:shadow-none">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                            {{ __('Tickets en cours') }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ __('Tickets ouverts ou en attente de reponse.') }}
                        </p>
                    </div>
                    <a href="{{ route('user.tickets.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-blue-500/40 transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <span class="material-symbols-outlined text-base">add</span>
                        {{ __('Creer un ticket') }}
                    </a>
                </div>

                <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-lg shadow-slate-200/40 dark:border-slate-700/60 dark:bg-slate-900/60 dark:shadow-none">
                    <div class="border-b border-slate-200/80 bg-slate-50 px-6 py-4 dark:border-slate-700/60 dark:bg-slate-900/80">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ __('Vos tickets actifs') }}
                        </h3>
                    </div>

                    <ul class="divide-y divide-slate-200/70 dark:divide-slate-700/60">
                        @forelse ($openTickets as $ticket)
                            <li>
                                <a href="{{ route('user.tickets.show', $ticket) }}"
                                   class="flex flex-col gap-1 px-6 py-4 transition hover:bg-slate-50/80 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:hover:bg-slate-800/80">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $ticket->subject }}
                                        </span>
                                        <span class="rounded-full border px-3 py-1 text-xs font-medium uppercase tracking-wide
                                            @class([
                                                'border-blue-200 bg-blue-100 text-blue-700' => $ticket->status === \App\Models\SupportTicket::STATUS_OPEN,
                                                'border-amber-200 bg-amber-100 text-amber-700' => $ticket->status === \App\Models\SupportTicket::STATUS_PENDING,
                                                'border-emerald-200 bg-emerald-100 text-emerald-700' => $ticket->status === \App\Models\SupportTicket::STATUS_RESOLVED,
                                                'border-slate-200 bg-slate-100 text-slate-700' => $ticket->status === \App\Models\SupportTicket::STATUS_CLOSED,
                                            ])">
                                            {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 dark:text-slate-300">
                                        {{ __('Derniere activite : :date', ['date' => optional($ticket->last_message_at ?? $ticket->updated_at ?? $ticket->created_at)->diffForHumans()]) }}
                                    </p>
                                </a>
                            </li>
                        @empty
                            <li class="px-6 py-10 text-center text-sm text-slate-500 dark:text-slate-300">
                                {{ __('Vous n\'avez pas de ticket en cours. Creez un ticket pour contacter le support.') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-2xl border border-slate-200/70 bg-white/90 p-6 shadow-lg shadow-slate-200/60 dark:border-slate-700/60 dark:bg-slate-900/60 dark:shadow-none">
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                        {{ __('Historique complet') }}
                    </h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Consultez tous vos tickets, y compris ceux deja resolus ou fermes.') }}
                    </p>

                    <ul class="mt-4 space-y-3">
                        @forelse ($tickets as $ticket)
                            <li>
                                <a href="{{ route('user.tickets.show', $ticket) }}"
                                   class="flex items-start justify-between rounded-xl border border-transparent bg-slate-50/80 px-4 py-3 transition hover:border-blue-200 hover:bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-slate-800/60 dark:hover:bg-slate-800">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $ticket->subject }}
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-300">
                                            {{ __('Cree : :date', ['date' => optional($ticket->created_at)->diffForHumans()]) }}
                                        </p>
                                    </div>
                                    <span class="mt-0.5 rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-wide
                                        @class([
                                            'border-blue-200 bg-blue-100 text-blue-700' => $ticket->status === \App\Models\SupportTicket::STATUS_OPEN,
                                            'border-amber-200 bg-amber-100 text-amber-700' => $ticket->status === \App\Models\SupportTicket::STATUS_PENDING,
                                            'border-emerald-200 bg-emerald-100 text-emerald-700' => $ticket->status === \App\Models\SupportTicket::STATUS_RESOLVED,
                                            'border-slate-300 bg-slate-100 text-slate-700' => $ticket->status === \App\Models\SupportTicket::STATUS_CLOSED,
                                        ])">
                                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="rounded-xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-300">
                                {{ __('Vous n\'avez pas encore cree de ticket.') }}
                            </li>
                        @endforelse
                    </ul>
                </div>

                <div class="rounded-2xl border border-blue-200/70 bg-blue-50/80 p-6 shadow-lg shadow-blue-200/40 dark:border-blue-400/40 dark:bg-blue-900/40 dark:text-white">
                    <h3 class="text-base font-semibold">
                        {{ __('Besoin d\'aide immediate ?') }}
                    </h3>
                    <p class="mt-2 text-sm text-blue-900/80 dark:text-blue-100">
                        {{ __('Rassemblez toutes les informations utiles (captures, messages d\'erreur) pour faciliter le diagnostic du support.') }}
                    </p>
                    <a href="{{ route('user.tickets.create') }}"
                       class="mt-4 inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-blue-700 shadow transition hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-800 dark:text-white dark:hover:bg-blue-700">
                        <span class="material-symbols-outlined text-base">north_east</span>
                        {{ __('Creer un nouveau ticket') }}
                    </a>
                </div>
            </aside>
        </section>
    </div>
</x-app-layout>
