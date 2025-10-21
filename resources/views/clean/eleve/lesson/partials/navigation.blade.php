{{-- Navigation de la leçon --}}
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold mb-4">Navigation</h3>
        <div class="flex justify-between items-center">
            <div class="text-center">
                <a
                    href="{{
                        route('eleve.formation.show', [$team, $formation])
                    }}"
                    class="text-blue-600 hover:text-blue-800"
                >
                    Retour à la formation
                </a>
            </div>

            {{-- Message d'information si la leçon est déjà terminée --}}
            @if($lessonProgress && $lessonProgress->pivot->status ===
            'completed')
            <div
                class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
            >
                <p class="text-green-800 dark:text-green-200 text-sm">
                    ✓ Cette leçon est terminée. Vous ne pouvez plus la modifier.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
