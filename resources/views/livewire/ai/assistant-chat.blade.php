@php
    $trainerName = $trainer['name'] ?: __('Assistant IA');
    $trainerDescription = $trainer['description'] ?? null;
    $trainerAvatar = $trainer['avatar'] ?? null;
@endphp

@if ($hasTrainer)
    <div class="flex h-full flex-1 flex-col">
        <header class="border-b border-slate-200 bg-white px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($trainerAvatar)
                    <img src="{{ asset($trainerAvatar) }}" alt="{{ $trainerName }}" class="h-10 w-10 rounded-full object-cover">
                @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div class="min-w-0">
                    <h3 class="truncate text-lg font-semibold text-slate-900">{{ $trainerName }}</h3>
                    <p class="truncate text-sm text-slate-600">
                        {{ $trainerDescription ?? __('Votre assistant IA personnel pour toutes vos questions.') }}
                    </p>
                </div>
            </div>
        </header>

        <section class="flex flex-1 flex-col overflow-hidden" wire:poll.4s="pollMessages">
            <div class="flex-1 space-y-4 overflow-y-auto bg-slate-50 p-4">
                @if ($awaitingResponse)
                    <div class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-600">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z"></path>
                        </svg>
                        <span>{{ __('L assistant redige sa reponse...') }}</span>
                    </div>
                @endif

                @foreach ($messages as $message)
                    <div class="flex {{ $message['role'] === 'assistant' ? 'justify-start' : 'justify-end' }}" wire:key="message-{{ $message['id'] }}">
                        <div class="max-w-full rounded-xl px-4 py-3 shadow-sm {{ $message['role'] === 'assistant' ? 'bg-white' : 'bg-emerald-500' }}">
                            <div class="mb-1 flex items-center justify-between text-xs text-slate-500 {{ $message['role'] === 'assistant' ? '' : 'text-white/80' }}">
                                <span class="font-medium">{{ $message['author'] }}</span>
                                <span>{{ $message['created_at_human'] }}</span>
                            </div>
                            <div class="prose prose-sm max-w-none break-words {{ $message['role'] === 'assistant' ? 'text-slate-900 prose-a:text-emerald-600' : 'text-white prose-a:text-white/90' }}">
                                {!! nl2br(e($message['content'])) !!}
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (empty($messages))
                    <div class="py-10 text-center text-slate-500">
                        <svg class="mx-auto mb-4 h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="mb-2 text-lg font-medium">{{ __('Commencez une conversation') }}</p>
                        <p class="text-sm">{{ __('Posez votre premiere question pour lancer le dialogue.') }}</p>
                    </div>
                @endif
            </div>

            <footer class="border-t border-slate-200 bg-white p-4">
                @if ($error)
                    <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2">
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <textarea
                        wire:model.defer="message"
                        rows="2"
                        class="flex-1 resize-none rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200 disabled:opacity-50"
                        placeholder="{{ __('Tapez votre message ici...') }}"
                        @disabled($awaitingResponse)
                        wire:target="sendMessage"
                        wire:loading.attr="disabled"
                    ></textarea>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-1 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-200 disabled:opacity-50"
                        @disabled($awaitingResponse)
                        wire:target="sendMessage"
                        wire:loading.attr="disabled"
                    >
                        @if ($awaitingResponse)
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z"></path>
                            </svg>
                            <span>{{ __('Envoi...') }}</span>
                        @else
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m3 10 18-7-7 18-2-7-7-4Z" />
                            </svg>
                            <span>{{ __('Envoyer') }}</span>
                        @endif
                    </button>
                </form>

                @error('message')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </footer>
        </section>
    </div>
@else
    <div class="flex h-full flex-1 items-center justify-center bg-slate-50">
        <div class="max-w-md rounded-2xl border border-slate-200 bg-white px-8 py-10 text-center shadow-sm">
            @if ($error)
                <svg class="mx-auto mb-4 h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-medium text-red-900">{{ __('Assistant IA indisponible') }}</h3>
                <p class="text-red-600">{{ $error }}</p>
            @else
                <svg class="mx-auto mb-4 h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mb-2 text-lg font-medium text-slate-900">{{ __('Assistant IA indisponible') }}</h3>
                <p class="text-slate-600">{{ __('L assistant IA n est pas configure ou n est pas disponible pour le moment.') }}</p>
            @endif
        </div>
    </div>
@endif

