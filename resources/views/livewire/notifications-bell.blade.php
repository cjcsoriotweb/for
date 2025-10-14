{{-- resources/views/livewire/notifications-bell.blade.php --}}
<div x-data="{ open: $wire.entangle('open') }" class="relative select-none">
    {{-- Poll uniquement le compteur (pause auto navigateur via Livewire keep-alive) --}}
    <span wire:poll.10s.keep-alive class="sr-only"></span>

    <!-- Bouton cloche -->
    <button type="button"
            class="relative inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            @click="open = !open"
            wire:click="refreshList"
            :aria-expanded="open.toString()"
            aria-haspopup="menu">
        <span class="text-lg">🔔</span>
        @if($unreadCount)
            <span class="absolute -top-1 -right-1 text-[10px] leading-none bg-red-600 text-white rounded-full px-1.5 py-0.5 min-w-4 text-center">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-cloak
         x-show="open"
         x-transition:enter="motion-safe:transition motion-safe:ease-out motion-safe:duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="motion-safe:transition motion-safe:ease-in motion-safe:duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         @click.outside="open = false"
         @keydown.escape.window="open = false"
         class="absolute right-0 mt-2 z-50 w-[min(92vw,22rem)] sm:w-80 bg-white shadow-lg ring-1 ring-black/5 rounded-xl overflow-hidden"
         role="menu"
         aria-label="Notifications">

        <!-- Header -->
        <div class="flex items-center justify-between px-3 py-2 border-b">
            <span class="font-semibold">Notifications</span>
            <div class="flex items-center gap-3">
                @if($items)
                <button class="text-sm text-blue-600 hover:underline"
                        wire:click="markAllRead">
                    Tout marquer lu
                </button>
                @endif
            </div>
        </div>

        <!-- Liste -->
        <ul class="max-h-[60vh] overflow-y-auto divide-y">
            @if(empty($items))
                <li class="p-3 text-sm text-gray-500">
                    <div class="flex items-center justify-center py-4">
                        <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="ml-2 text-sm text-gray-500">En attente de notification...</span>
                    </div>
                </li>
            @else
                @foreach($items as $n)
                    <li class="p-3 {{ empty($n['read_at']) ? 'bg-gray-50/60' : '' }}">
                        <a href="{{ data_get($n['data'],'url','#') }}" class="block" @click="open = false">
                            <div class="text-sm font-medium">{{ data_get($n['data'],'title','Notification') }}</div>
                            <div class="text-xs text-gray-600 mt-0.5">{{ data_get($n['data'],'message','') }}</div>
                            <div class="text-[11px] text-gray-400 mt-1">{{ $n['human_created_at'] }}</div>
                        </a>
                    </li>
                @endforeach

                @if($next_before)
                    <li class="p-2 text-center">
                        <button class="text-xs text-gray-600 hover:underline"
                                wire:click="loadMore">
                            Charger plus
                        </button>
                    </li>
                @endif
            @endif
        </ul>
    </div>
</div>
