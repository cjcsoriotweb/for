<div class="flex flex-col items-end space-y-3">
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
                    <h3 class="font-semibold">{{ $assistantMeta['name'] ?? 'Assistant' }}</h3>
                    @if (! empty($assistantMeta['description']))
                        <p class="text-xs text-blue-100">{{ $assistantMeta['description'] }}</p>
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

            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                @forelse ($messages as $message)
                    <div class="flex flex-col gap-2 {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div class="max-w-[80%] rounded-lg px-4 py-2 text-sm {{ $message['role'] === 'user' ? 'bg-blue-600 text-white' : 'bg-white text-gray-900 border border-slate-200' }}">
                            @php
                                echo $this->renderMessageHtml($message['content']);
                            @endphp
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center gap-2 text-center text-sm text-gray-500 py-10">
                        <p class="font-medium text-gray-600">{{ __('Bienvenue !') }}</p>
                        <p>{{ __('Posez votre question pour démarrer la conversation.') }}</p>
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
