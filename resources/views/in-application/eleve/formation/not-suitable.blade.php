<x-eleve-layout :team="$team">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-200 dark:border-gray-700">
            <div class="p-8 space-y-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Formation non adaptée</h1>
                <p class="text-gray-600 dark:text-gray-300 text-lg">
                    Après avoir évalué vos réponses, cette formation ne semble pas correspondre à votre niveau actuel.
                </p>

                <div class="rounded-xl border border-orange-200 bg-orange-50 p-4">
                    <p class="text-sm uppercase tracking-wide text-orange-600 font-semibold">Résultat du quiz d'entrée</p>
                    <p class="text-4xl font-bold text-orange-700">{{ $score }}%</p>
                    <p class="text-sm text-orange-600">
                        @if($reason === 'too_low')
                            Votre score est inférieur au seuil minimum requis ({{ $threshold }}%). Nous vous orientons vers un parcours plus préparatoire.
                        @else
                            Votre score dépasse le seuil supérieur ({{ $threshold }}%) : cette formation risquerait d'être trop simple.
                        @endif
                    </p>
                </div>

                <p class="text-gray-600 dark:text-gray-300">
                    Nos équipes pédagogiques ont bien reçu vos résultats et vous contacteront pour vous proposer une alternative adaptée.
                </p>

                <div class="flex gap-3 flex-wrap">
                    <a href="{{ route('eleve.index', $team) }}"
                       class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-500 transition">
                        Explorer d'autres formations
                    </a>
                    <a href="{{ route('eleve.index', $team) }}"
                       class="inline-flex items-center px-5 py-3 border border-gray-200 rounded-xl text-gray-700 hover:border-gray-300 transition">
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-eleve-layout>
