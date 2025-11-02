@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php
    use App\Models\AiTrainer;

    if (! $enable || ! auth()->check()) {
        return;
    }

    $trainers = AiTrainer::query()
        ->active()
        ->where('show_everywhere', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();
@endphp

@if ($trainers->isNotEmpty())
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-4">
        @foreach ($trainers as $trainer)
            @if (! $locked)
                <button
                    type="button"
                    class="inline-flex items-center gap-3 rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-lg ring-1 ring-black/5 transition hover:-translate-y-0.5 hover:bg-gray-50"
                    data-assistant-launch="{{ $trainer->slug }}"
                >
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-600 text-white text-xs font-bold">
                        {{ strtoupper(substr($trainer->name, 0, 2)) }}
                    </span>
                    <span>{{ $trainer->name }}</span>
                    @if ($notifications > 0)
                        <span class="ml-2 inline-flex items-center justify-center rounded-full bg-red-500 px-2 py-0.5 text-xs font-semibold text-white">
                            {{ $notifications }}
                        </span>
                    @endif
                </button>
            @endif

            <livewire:chat-box
                :key="'assistant-chat-'.$trainer->slug"
                :trainer="$trainer->slug"
                :title="$trainer->name"
            />
        @endforeach
    </div>
@endif




