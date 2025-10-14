@props(['collector' => null])

@php
    $collector = $collector ?? ($authDebug ?? null);
@endphp

@if($collector && $collector->hasEntries() && config('app.debug'))
<div
    x-data="{ open: false }"
    class="fixed bottom-4 right-4 z-50 text-sm"
>
    <button
        @click="open = !open"
        class="mb-2 rounded-lg bg-gray-900/80 text-white px-3 py-2 shadow hover:bg-gray-900 focus:outline-none"
        title="Basculer le panneau de debug permissions"
    >🔐 Auth Debug</button>

    <div x-show="open"
         x-transition
         class="w-[28rem] max-h-[60vh] overflow-auto rounded-xl bg-white/95 backdrop-blur shadow-2xl border border-gray-200 p-3 dark:bg-gray-800/95 dark:border-gray-700 dark:text-gray-100"
    >
        <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-gray-700">
            <div class="font-semibold">Vérifications d’autorisations (Gate/Policy)</div>
            <button @click="open=false" class="text-xs opacity-70 hover:opacity-100">Fermer</button>
        </div>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($collector->all() as $entry)
                <li class="py-2">
                    <div class="flex items-center justify-between">
                        <div class="font-mono text-xs">
                            <span class="px-1.5 py-0.5 rounded {{ $entry['result'] ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200' }}">
                                {{ $entry['result'] ? 'ALLOW' : 'DENY' }}
                            </span>
                            <span class="ml-2">{{ $entry['ability'] }}</span>
                        </div>
                        <div class="text-[11px] opacity-70">
                            {{ $entry['route'] ?: 'route ?' }}
                        </div>
                    </div>
                    <div class="mt-1 text-[11px] text-gray-600 dark:text-gray-300">
                        Args: {{ implode(', ', $entry['arguments']) ?: '—' }}
                    </div>
                    @if($entry['caller'])
                        <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                            Caller: {{ $entry['caller'] }}
                        </div>
                    @endif
                    <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-400">
                        URL: {{ $entry['url'] }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endif
