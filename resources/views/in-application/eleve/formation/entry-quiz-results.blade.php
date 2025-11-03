<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Résultats du quiz d\'entrée') }} - {{ $formation->title }}
            </h2>
            <a href="{{ route('eleve.formation.show', [$team, $formation]) }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 rounded-lg border border-gray-300 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à la formation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Résumé des résultats -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden shadow-lg sm:rounded-xl border border-blue-200 mb-8">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                            @if($attempt->score >= ($entryQuiz->passing_score ?? 80))
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @endif
                        </div>

                        <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $entryQuiz->title }}</h3>

                        <div class="inline-flex items-center justify-center w-32 h-32 rounded-full border-8
                            {{ $attempt->score <= ($entryQuiz->passing_score ?? 80) ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}
                            mb-6">
                            <span class="text-4xl font-bold
                                {{ $attempt->score <= ($entryQuiz->passing_score ?? 80) ? 'text-green-600' : 'text-red-600' }}">
                                {{ round($attempt->score, 1) }}%
                            </span>
                        </div>

                        <p class="text-xl text-gray-600 mb-2">
                            Score maximum autorisé: <span class="font-semibold">{{ $entryQuiz->passing_score ?? 80 }}%</span>
                        </p>

                        @if($attempt->score <= ($entryQuiz->passing_score ?? 80))
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-semibold">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Niveau adapté - Accès autorisé
                            </div>
                        @else
                            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-semibold">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Niveau trop élevé - Accès refusé
                            </div>
                        @endif
                    </div>

                    @if($attempt->score <= ($entryQuiz->passing_score ?? 80))
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-6">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-green-800 font-semibold mb-2">Félicitations !</h4>
                                    <p class="text-green-700">
                                        Votre niveau correspond parfaitement à cette formation. Vous pouvez maintenant accéder à toutes les ressources.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-6 mb-6">
                            <div class="flex items-start">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-red-800 font-semibold mb-2">Niveau trop élevé détecté</h4>
                                    <p class="text-red-700">
                                        Votre score indique que cette formation est trop facile pour vous. Un superadmin vous contactera
                                        pour vous proposer une formation plus adaptée à votre niveau avancé.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-blue-100 text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-blue-900">{{ $attempt->correct_answers }}</div>
                            <div class="text-sm text-blue-600 font-medium">Réponses correctes</div>
                        </div>
                        <div class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-purple-100 text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-purple-900">{{ $questions->count() }}</div>
                            <div class="text-sm text-purple-600 font-medium">Total des questions</div>
                        </div>
                        <div class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-green-100 text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-green-900">
                                {{ $attempt->submitted_at ? $attempt->submitted_at->format('H:i') : 'N/A' }}
                            </div>
                            <div class="text-sm text-green-600 font-medium">Soumis le {{ $attempt->submitted_at ? $attempt->submitted_at->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détail des réponses -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h4 class="text-xl font-semibold text-gray-900">Détail de vos réponses</h4>
                    </div>

                    <div class="space-y-6">
                        @foreach($questions as $index => $question)
                            @php
                                $userAnswer = $answers->where('question_id', $question->id)->first();
                                $isCorrect = $userAnswer ? $userAnswer->is_correct : false;
                                $selectedChoice = $userAnswer ? $userAnswer->choice : null;
                            @endphp

                            <div class="bg-gradient-to-r {{ $isCorrect ? 'from-green-50 to-emerald-50' : 'from-red-50 to-pink-50' }}
                                         p-6 rounded-xl border {{ $isCorrect ? 'border-green-200' : 'border-red-200' }}">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-start flex-1">
                                        <span class="flex-shrink-0 w-8 h-8 {{ $isCorrect ? 'bg-green-500' : 'bg-red-500' }}
                                                     text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 mt-0.5">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-900 mb-2">{{ $question->question }}</h5>
                                            <div class="flex items-center">
                                                @if($isCorrect)
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Réponse correcte
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Réponse incorrecte
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3 ml-12">
                                    @foreach($question->quizChoices as $choice)
                                        <div class="flex items-center p-3 bg-white rounded-lg border transition-all duration-200
                                            {{ $choice->is_correct ? 'border-green-300 bg-green-50' : 'border-gray-200' }}
                                            {{ $choice->id == $selectedChoice->id ?? null && !$choice->is_correct ? 'border-blue-300 bg-blue-50' : '' }}">
                                            <div class="w-5 h-5 rounded-full border-2 mr-4 flex items-center justify-center
                                                {{ $choice->id == $selectedChoice->id ?? null ? 'border-blue-500' : 'border-gray-300' }}
                                                {{ $choice->is_correct ? 'border-green-500' : '' }}">
                                                @if($choice->id == $selectedChoice->id ?? null)
                                                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                                @elseif($choice->is_correct)
                                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                                @endif
                                            </div>
                                            <span class="flex-1 {{ $choice->is_correct ? 'font-medium text-green-800' : 'text-gray-700' }}">
                                                {{ $choice->choice_text }}
                                            </span>
                                            @if($choice->is_correct)
                                                <span class="text-green-600 text-sm font-medium ml-2">✓ Correct</span>
                                            @elseif($choice->id == $selectedChoice->id ?? null)
                                                <span class="text-blue-600 text-sm font-medium ml-2">Votre choix</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-center">
                            <a href="{{ route('eleve.formation.show', [$team, $formation]) }}"
                               class="inline-flex items-center px-8 py-3 text-base font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                Continuer vers la formation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
