{{-- resources/views/livewire/notifications-bell.blade.php --}}
<div wire:poll.10s> {{-- ← rafraîchit toutes les 10s --}}
    <button class="relative">
        🔔
        @if($unreadCount)
            <span class="absolute -top-1 -right-1 text-xs bg-red-600 text-white rounded-full px-1">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div class="mt-2 w-80 bg-white shadow rounded p-2">
        <div class="flex justify-between items-center mb-2">
            <span class="font-semibold">Notifications</span>
            <button wire:click="markAllRead" class="text-sm text-blue-600">Tout marquer lu</button>
        </div>

        <ul class="space-y-1 max-h-64 overflow-y-auto">
            @forelse($latest as $n)
                <li class="p-2 rounded {{ is_null($n->read_at) ? 'bg-gray-50' : '' }}">
                    <a href="{{ data_get($n->data,'url') }}" class="block">
                        <div class="text-sm font-medium">{{ data_get($n->data,'title','Notification') }}</div>
                        <div class="text-xs text-gray-500">{{ data_get($n->data,'message') }}</div>
                        <div class="text-[11px] text-gray-400">{{ $n->created_at->diffForHumans() }}</div>
                    </a>
                </li>
            @empty
                <li class="text-sm text-gray-500 p-2">Aucune notification</li>
            @endforelse
        </ul>
    </div>
</div>
