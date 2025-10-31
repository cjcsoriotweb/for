
@if ($hasTrainer)
    <div class="bg-gray-50 rounded-lg">
        <div class="border-b border-gray-200 bg-white px-6 py-4">
            <div class="flex items-center gap-3">
                @if ($trainerAvatar)
                    <img src="{{ asset($trainerAvatar) }}" alt="{{ $trainerName }}" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $trainerName ?: __('Assistant IA') }}</h3>
                    @if ($trainerDescription)
                        <p class="text-sm text-gray-600">{{ $trainerDescription }}</p>
                    @else
                        <p class="text-sm text-gray-600">{{ __('Votre assistant IA personnel pour toutes vos questions.') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="h-96 flex flex-col">
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                @if ($awaitingResponse)
                    <div class="flex items-center gap-2 text-sm text-emerald-600 bg-emerald-50 rounded-lg px-3 py-2 border border-emerald-200">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v2a6 6 0 00-6 6H4z"></path>
                        </svg>
                        <span>{{ __('L\'assistant redige sa reponse...') }}</span>
                    </div>
                @endif

                @foreach ($messages as $message)
                    <div class="flex {{ $message['role'] === 'assistant' ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs lg:max-w-md xl:max-w-lg {{ $message['role'] === 'assistant' ? 'bg-white' : 'bg-emerald-500' }} rounded-lg shadow-sm px-4 py-3">
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                                <span class="font-medium">{{ $message['author'] }}</span>
                                <span>{{ $message['created_at_human'] }}</span>
                            </div>
                            <div class="{{ $message['role'] === 'assistant' ? 'text-gray-900' : 'text-white' }} prose prose-sm max-w-none">
                                {!! nl2br(e($message['content'])) !!}
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (empty($messages))
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-lg font-medium mb-2">{{ __('Commencez une conversation') }}</p>
                        <p class="text-sm">{{ __('Posez votre premiere question pour commencer le dialogue.') }}</p>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-200 bg-white p-4" wire:poll.1s="poll">
                @if ($error)
                    <div class="mb-3 rounded-lg bg-red-50 border border-red-200 px-3 py-2">
                        <p class="text-sm text-red-700">{{ $error }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <textarea
                        wire:model.defer="message"
                        rows="2"
                        class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 resize-none disabled:opacity-50"
                        placeholder="{{ __('Tapez votre message ici...') }}"
                        :disabled="$awaitingResponse"
                    ></textarea>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-1 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-600 focus:ring-2 focus:ring-emerald-200 disabled:opacity-50"
                        :disabled="$awaitingResponse"
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
            </div>
        </div>
    </div>
@else
    <div class="bg-gray-50 rounded-lg p-8 text-center">
        @if ($error)
            <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="text-lg font-medium text-red-900 mb-2">{{ __('Assistant IA indisponible') }}</h3>
            <p class="text-red-600">{{ $error }}</p>
        @else
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('Assistant IA indisponible') }}</h3>
            <p class="text-gray-600">{{ __('L\'assistant IA n\'est pas configuré ou n\'est pas disponible pour le moment.') }}</p>
        @endif
    </div>
@endif
