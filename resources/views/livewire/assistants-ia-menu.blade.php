<div class="fixed bottom-6 right-6 z-50">
    <!-- Bouton flottant -->
    <button wire:click="toggleDrawer" aria-label="Ouvrir l'assistant IA"
        class="w-16 h-16 rounded-full bg-indigo-600 text-white shadow-2xl flex items-center justify-center text-3xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" />
        </svg>
    </button>

    <!-- Drawer latéral -->
    <div wire:loading.remove wire:target="toggleDrawer,selectTrainer,closeChat"
         style="{{ $drawer ? '' : 'display: none;' }}" class="fixed inset-0 z-50 flex justify-end">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30" wire:click="$set('drawer', false); $set('active', null)"></div>
        <!-- Panneau -->
        <div class="relative w-full max-w-xs h-full bg-white shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <span class="font-bold text-lg text-indigo-700">Assistants IA</span>
                <button wire:click="$set('drawer', false); $set('active', null)"
                    class="text-gray-400 hover:text-red-500 text-2xl" aria-label="Fermer">&times;</button>
            </div>

            @if(!$active)
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @foreach ($this->trainers as $trainer)
                        <button wire:click="selectTrainer('{{ $trainer->slug }}')"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-indigo-100 hover:bg-indigo-50 focus:bg-indigo-100 transition group">
                            <span class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xl">
                                {{ strtoupper(mb_substr($trainer->name,0,2)) }}
                            </span>
                            <span class="flex flex-col items-start">
                                <span class="text-gray-900 font-semibold text-base group-hover:text-indigo-700">{{ $trainer->name }}</span>
                                @if(!empty($trainer->description))
                                    <span class="text-xs text-gray-500 mt-1">{{ $trainer->description }}</span>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>
            @else
                <!-- Chat containers -->
                @foreach ($this->trainers as $trainer)
                    @if($active === $trainer->slug)
                        <div class="flex-1 flex flex-col overflow-hidden">
                            <div class="flex items-center gap-2 px-5 py-3 border-b bg-indigo-50">
                                <button wire:click="closeChat" class="text-indigo-600 hover:text-indigo-900 text-xl mr-2" aria-label="Retour">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>
                                <span class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                    {{ strtoupper(mb_substr($trainer->name,0,2)) }}
                                </span>
                                <span class="font-semibold text-indigo-900 text-lg">{{ $trainer->name }}</span>
                            </div>

                            @livewire('chat-box', [
                                'trainer' => $trainer->slug,
                                'title' => $trainer->name
                            ], key('chatbox-' . $trainer->slug))
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
