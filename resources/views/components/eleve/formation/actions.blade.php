@props(['team', 'formation', 'progress'])

<!-- Actions -->
<div class="mt-6 flex justify-between items-center">
    <a
        href="{{ route('eleve.index', $team) }}"
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
    >
        Retour à l'accueil
    </a>
</div>
