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

    // Ajouter le trainer spécifique à la formation si on est sur une page de formation (mais pas de leçon)
    $currentRoute = request()->route();
    if ($currentRoute && str_contains($currentRoute->getName(), 'eleve.formation') && !str_contains($currentRoute->getName(), 'eleve.formation.lesson')) {
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

{{-- Container that will render all chatboxes pushed to the `chatboxes` stack. 
     It is placed at the same bottom-right corner and laid out horizontally.
     pointer-events handling ensures the dock buttons remain clickable while allowing
     interaction with the chatboxes themselves. --}}
<div class="fixed bottom-6 right-6 z-40 flex items-end">
    <div class="pointer-events-none">
        <div class="pointer-events-auto flex flex-row items-end space-x-3 flex-wrap justify-end">
            @stack('chatboxes')
        </div>
    </div>
</div>
@once
    @push('assistant-dock')
        <script>
            (() => {
                console.log('[assistant-dock] init');
                const triggerSelector = '[data-assistant-launch]';

                const dispatchLaunchEvent = (slug) => {
                    console.log('[assistant-dock] dispatchLaunchEvent', slug);
                    if (!slug) {
                        return;
                    }

                        // Prefer emit (server event) but also dispatch a client event if available.
                        if (window.Livewire?.emit) {
                            console.log('[assistant-dock] using Livewire.emit');
                            window.Livewire.emit('launchAssistant', slug);
                            // Also dispatch for components/listeners expecting a browser event
                            if (window.Livewire?.dispatch) {
                                console.log('[assistant-dock] also using Livewire.dispatch');
                                window.Livewire.dispatch('launchAssistant', { slug });
                            }

                            // Fallback: try to find the chatbox DOM node and click its internal toggle button
                            // This will trigger the Livewire wire:click toggle handler if present.
                            setTimeout(() => {
                                try {
                                    const selector = `[data-trainer="${slug}"]`;
                                    const comp = document.querySelector(selector);
                                    if (comp) {
                                        const toggleBtn = comp.querySelector('button[wire\\:click="toggle"]');
                                        if (toggleBtn) {
                                            console.log('[assistant-dock] fallback: clicking internal toggle button for', slug);
                                            toggleBtn.click();
                                        } else {
                                            console.log('[assistant-dock] fallback: toggle button not found for', slug);
                                        }
                                    } else {
                                        console.log('[assistant-dock] fallback: chatbox DOM not found for', slug);
                                    }
                                } catch (e) {
                                    console.warn('[assistant-dock] fallback error', e);
                                }
                            }, 80);
                        } else if (window.Livewire?.dispatch) {
                            console.log('[assistant-dock] using Livewire.dispatch');
                            window.Livewire.dispatch('launchAssistant', { slug });
                        } else {
                            console.warn('[assistant-dock] Livewire not available at click time');
                        }
                };

                document.addEventListener('click', (event) => {
                    const trigger = event.target.closest(triggerSelector);
                    if (!trigger) {
                        return;
                    }

                    const slug = trigger.getAttribute('data-assistant-launch');
                    console.log('[assistant-dock] click on trigger', slug, trigger);
                    dispatchLaunchEvent(slug);
                });
            })();
        </script>
    @endpush
@endonce
