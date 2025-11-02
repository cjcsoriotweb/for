<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Résultats du quiz d\'entrée') }} - {{ $formation->title }}
            </h2>
            <a href="{{ route('eleve.formation.show', [$team, $formation]) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour à la formation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Résumé des résultats -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold mb-2">{{ $entryQuiz->title }}</h3>
                        <div class="text-4xl font-bold mb-4
                            {{ $attempt->score >= ($entryQuiz->passing_score ?? 80) ? 'text-green-600' : 'text-red-600' }}">
                            {{ round($attempt->score, 1) }}%
                        </div>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            Score requis: {{ $entryQuiz->passing_score ?? 80 }}%
                        </p>
                    </div>

                    @if($attempt->score >= ($entryQuiz->passing_score ?? 80))
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <h4 class="text-green-800 dark:text-green-200 font-medium">Félicitations !</h4>
                                    <p class="text-green-700 dark:text-green-300 text-sm">
                                        Vous avez réussi le quiz d'entrée. Vous pouvez maintenant commencer la formation.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <div>
                                    <h4 class="text-red-800 dark:text-red-200 font-medium">Niveau supérieur détecté</h4>
                                    <p class="text-red-700 dark:text-red-300 text-sm">
                                        Votre score indique que cette formation pourrait ne pas correspondre à votre niveau actuel.
                                        Un superadmin vous contactera prochainement pour vous orienter vers une formation plus adaptée.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $attempt->correct_answers }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Réponses correctes</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $questions->count() }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Total des questions</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $attempt->submitted_at ? $attempt->submitted_at->diffForHumans() : 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Soumis</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détail des réponses -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-medium mb-4">Détail de vos réponses</h4>

                    <div class="space-y-6">
                        @foreach($questions as $index => $question)
                            @php
                                $userAnswer = $answers->where('question_id', $question->id)->first();
                                $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                                $selectedChoice = $userAnswer ? $userAnswer->choice : null;
                            @endphp

                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $isCorrect ? 'bg-green-50 dark:bg-green-900/10' : 'bg-red-50 dark:bg-red-900/10' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <h5 class="font-medium">
                                        {{ $index + 1 }}. {{ $question->question }}
                                    </h5>
                                    <span class="flex items-center text-sm {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                                        @if($isCorrect)
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Correct
                                        @else
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Incorrect
                                        @endif
                                    </span>
                                </div>

                                <div class="space-y-2">
                                    @foreach($question->quizChoices as $choice)
                                        <div class="flex items-center {{ $choice->is_correct ? 'font-medium text-green-700 dark:text-green-300' : '' }}">
                                            <div class="w-4 h-4 rounded-full border-2 mr-3 flex items-center justify-center
                                                {{ $choice->id == $selectedChoice->id ?? null ? 'border-blue-500' : 'border-gray-300' }}
                                                {{ $choice->is_correct ? 'border-green-500' : '' }}">
                                                @if($choice->id == $selectedChoice->id ?? null)
                                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                                @endif
                                                @if($choice->is_correct)
                                                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                                @endif
                                            </div>
                                            <span>{{ $choice->choice_text }}</span>
                                            @if($choice->is_correct)
                                                <span class="ml-2 text-green-600 text-sm">(Réponse correcte)</span>
                                            @endif
                                            @if($choice->id == $selectedChoice->id ?? null && !$choice->is_correct)
                                                <span class="ml-2 text-blue-600 text-sm">(Votre réponse)</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('eleve.formation.show', [$team, $formation]) }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Continuer vers la formation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
