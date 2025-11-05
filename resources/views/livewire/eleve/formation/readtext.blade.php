{{-- Composant Readtext - logique en arrière-plan uniquement --}}
{{-- La progression est maintenant affichée par les composants ProgressDisplay --}}
{{-- Ce composant gère uniquement le timer et la sauvegarde en arrière-plan --}}
<div style="display: none;" wire:poll.1s="checkTimer">
    {{-- Élément caché pour satisfaire l'exigence de balise racine Livewire --}}
    {{-- Le wire:poll déclenche la méthode checkTimer toutes les secondes --}}
</div>
