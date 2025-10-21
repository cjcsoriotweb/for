@props(['team', 'formation', 'progress'])

<!-- Actions -->
<div class="mt-6 flex justify-between items-center">
    <a
        href="{{ route('eleve.index', $team) }}"
        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
    >
        Retour à l'accueil
    </a>

    @if(($progress['percentage'] ?? 0) < 100)
    <form
        method="POST"
        action="{{
            route('eleve.formation.reset-progress', [$team, $formation])
        }}"
        class="inline"
    >
        @csrf @method('POST')
        <button
            type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser votre progression dans cette formation ?')"
        >
            Réinitialiser la progression
        </button>
    </form>
    @endif
</div>
