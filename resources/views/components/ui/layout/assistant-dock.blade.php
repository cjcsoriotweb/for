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

<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-4">
    @foreach ($trainers as $trainer)
        <x-ui.layout.assistant-entry
            :slug="$trainer->slug"
            :name="$trainer->name"
            :notifications="$notifications"
            :locked="$locked"
        />
    @endforeach

    @stack('assistant-dock-items')
</div>

@once
    @push('assistant-dock')
        <script>
            (() => {
                const triggerSelector = '[data-assistant-launch]';

                const dispatchLaunchEvent = (slug) => {
                    if (!slug) {
                        return;
                    }

                    if (window.Livewire?.dispatch) {
                        window.Livewire.dispatch('launchAssistant', { slug });
                    } else if (window.Livewire?.emit) {
                        window.Livewire.emit('launchAssistant', slug);
                    }
                };

                document.addEventListener('click', (event) => {
                    const trigger = event.target.closest(triggerSelector);
                    if (!trigger) {
                        return;
                    }

                    const slug = trigger.getAttribute('data-assistant-launch');
                    dispatchLaunchEvent(slug);
                });
            })();
        </script>
    @endpush
@endonce




