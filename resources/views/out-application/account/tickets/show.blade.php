@php
    /** @var \App\Models\SupportTicket $ticket */
@endphp

<x-app-layout>
    <div class="space-y-8">
        <section class="rounded-3xl border border-slate-200/70 bg-white/80 px-8 py-10 shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/50 dark:shadow-none sm:px-12">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('user.tickets') }}"
                           class="inline-flex items-center gap-1 rounded-full border border-transparent px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-600 transition hover:border-blue-200 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:text-blue-300 dark:hover:border-blue-500 dark:hover:bg-blue-900/40">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            {{ __('Retour aux tickets') }}
                        </a>
                        <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide
                            @class([
                                'border-blue-200 bg-blue-100 text-blue-700' => $ticket->status === \App\Models\SupportTicket::STATUS_OPEN,
                                'border-amber-200 bg-amber-100 text-amber-700' => $ticket->status === \App\Models\SupportTicket::STATUS_PENDING,
                                'border-emerald-200 bg-emerald-100 text-emerald-700' => $ticket->status === \App\Models\SupportTicket::STATUS_RESOLVED,
                                'border-slate-300 bg-slate-100 text-slate-700' => $ticket->status === \App\Models\SupportTicket::STATUS_CLOSED,
                            ])">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $ticket->subject }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
                        <span class="inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-slate-400 dark:text-slate-500">schedule</span>
                            {{ __('Cree : :date', ['date' => optional($ticket->created_at)->diffForHumans()]) }}
                        </span>
                        @if ($ticket->last_message_at)
                            <span class="inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-slate-400 dark:text-slate-500">chat</span>
                                {{ __('Derniere reponse : :date', ['date' => optional($ticket->last_message_at)->diffForHumans()]) }}
                            </span>
                        @endif
                        @if ($ticket->origin_label)
                            <span class="inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-slate-400 dark:text-slate-500">link</span>
                                {{ $ticket->origin_label }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                    <a href="{{ route('user.tickets.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-blue-500 dark:hover:text-blue-300">
                        <span class="material-symbols-outlined text-base">add</span>
                        {{ __('Creer un nouveau ticket') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-3xl border border-slate-200/80 shadow-2xl shadow-slate-200/60 dark:border-slate-700/50 dark:shadow-slate-900/50">
            <livewire:support.ticket-reporter :default-ticket-id="$ticket->id" mode="detail" />
        </section>
    </div>
</x-app-layout>
