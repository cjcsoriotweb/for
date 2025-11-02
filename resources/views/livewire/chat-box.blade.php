<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3">
    @if (! $isOpen)
        <button
            type="button"
            wire:click="toggle"
            class="text-left bg-blue-600 text-white rounded-2xl shadow-2xl px-4 py-3 flex items-start gap-3 w-64 sm:w-72 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-white shadow-inner">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9h8M8 13h5m1 8l-4-4H7a3 3 0 01-3-3V7a3 3 0 013-3h10a3 3 0 013 3v6a3 3 0 01-3 3h-1v6z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="inline-flex w-fit items-center rounded-full bg-white/20 px-2 py-0.5 text-[11px] font-semibold uppercase tracking-wide">
                    {{ __('Assistant') }}
                </span>
                <span class="mt-2 text-sm font-semibold">{{ $title }}</span>
                <p class="mt-1 text-xs text-white/90">
                    {{ __("Discutez avec votre assistant Evolubat pour obtenir de l'aide immediatement.") }}
                </p>
            </div>
        </button>
    @else
        <div class="bg-white rounded-2xl shadow-2xl flex flex-col w-72 sm:w-96 h-[32rem] overflow-hidden border border-slate-200">
            <div class="bg-blue-600 text-white px-4 py-3 flex items-center justify-between">
                <div>
                    <h3 class="font-semibold">{{ $trainerMeta['name'] ?? 'Assistant' }}</h3>
                    @if (! empty($trainerMeta['description']))
                        <p class="text-xs text-blue-100">{{ $trainerMeta['description'] }}</p>
                    @endif
                </div>
                <button
                    type="button"
                    wire:click="toggle"
                    class="rounded-full p-1 hover:bg-blue-500 transition"
                    aria-label="{{ __('Fermer le chat') }}"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if ($this->hasTrainerChoice)
                <div class="bg-blue-50 px-4 py-2 text-xs text-blue-900 flex items-center gap-2 border-b border-blue-100">
                    <span class="font-semibold">{{ __('Assistant :') }}</span>
                    <select
                        wire:model="selectedTrainer"
                        class="flex-1 rounded-lg border border-blue-200 bg-white px-2 py-1 text-xs text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                        @foreach ($trainerOptions as $slug => $option)
                            <option value="{{ $slug }}">{{ $option['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                @forelse ($messages as $message)
                    <div class="flex flex-col gap-2 {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div class="max-w-[80%] rounded-lg px-4 py-2 text-sm {{ $message['role'] === 'user' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-slate-200' }}">
                            @php
                                echo $this->renderMessageHtml($message['content']);
                            @endphp
                        </div>

                        @if (! empty($message['buttons']))
                            <div class="flex flex-wrap gap-2 max-w-[80%]">
                                @foreach ($message['buttons'] as $buttonLabel)
                                    @if ($message['ticket_url'] && $buttonLabel === $ticketButtonLabel)
                                        <a
                                            href="{{ $message['ticket_url'] }}"
                                            target="_blank"
                                            class="bg-white hover:bg-blue-50 text-blue-600 border border-blue-300 rounded-lg px-3 py-1.5 text-xs transition-colors"
                                        >
                                            {{ $buttonLabel }}
                                        </a>
                                    @else
                                        <button
                                            type="button"
                                            wire:click="sendSuggestedMessageEncoded('{{ base64_encode($buttonLabel) }}')"
                                            class="bg-white hover:bg-blue-50 text-blue-600 border border-blue-300 rounded-lg px-3 py-1.5 text-xs transition-colors"
                                        >
                                            {{ $buttonLabel }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="space-y-3">
                        <p class="text-sm text-gray-500 text-center mb-2">{{ __('Comment puis-je vous aider ?') }}</p>
                        @foreach ($suggestionPresets as $preset)
                            <button
                                type="button"
                                wire:click="sendSuggestedMessageEncoded('{{ base64_encode($preset) }}')"
                                class="w-full text-left bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg px-4 py-3 text-sm transition-colors border border-blue-200"
                            >
                                {{ $preset }}
                            </button>
                        @endforeach
                    </div>
                @endforelse

                @if ($isLoading)
                    <div class="flex justify-center py-2">
                        <span class="text-xs text-gray-500">{{ __('Chargement...') }}</span>
                    </div>
                @endif

                @if ($error)
                    <div class="bg-red-50 text-red-600 border border-red-200 rounded-lg px-4 py-3 text-sm">
                        {{ $error }}
                    </div>
                @endif
            </div>

            <form
                wire:submit.prevent="sendMessage"
                class="border-t border-gray-200 bg-white px-4 py-3"
            >
                <div class="flex gap-2 items-end">
                    <textarea
                        wire:model.defer="message"
                        rows="2"
                        placeholder="{{ __('Votre message...') }}"
                        class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 text-sm"
                        @disabled($isSending || $isLoading)
                        maxlength="{{ (int) config('ai.max_message_length', 2000) }}"
                    ></textarea>
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white px-3 py-2 rounded-lg transition-colors flex items-center justify-center"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage"
                        @disabled($isSending || $isLoading)
                    >
                        <span wire:loading wire:target="sendMessage" class="h-4 w-4 border-2 border-white/60 border-t-transparent rounded-full animate-spin"></span>
                        <svg
                            wire:loading.remove
                            wire:target="sendMessage"
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
