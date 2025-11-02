@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php
    use App\Models\AiTrainer;
    use App\Models\Formation;

    if (! $enable || ! auth()->check()) {
        return;
    }

    $trainers = AiTrainer::query()
        ->active()
        ->where('show_everywhere', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    // Ajouter le trainer spécifique à la formation si on est sur une page de formation
    $currentRoute = request()->route();
    if ($currentRoute && str_contains($currentRoute->getName(), 'eleve.formation')) {
        $formationId = $currentRoute->parameter('formation');
        if ($formationId && is_numeric($formationId)) {
            $formation = Formation::find($formationId);
            if ($formation && $formation->primaryTrainer) {
                // Vérifier que ce trainer n'est pas déjà dans la liste
                $trainerExists = $trainers->contains('id', $formation->primaryTrainer->id);
                if (! $trainerExists) {
                    // Placer le trainer de formation en haut de la liste
                    $trainers->prepend($formation->primaryTrainer);
                }
            }
        }
    }
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
