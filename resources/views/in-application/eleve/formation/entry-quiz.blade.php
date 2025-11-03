 <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quiz d\'entrée') }} - {{ $formation->title }}
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
            <!-- Informations du quiz -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden shadow-lg sm:rounded-xl border border-blue-200 mb-8">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $entryQuiz->title }}</h3>
                            @if($entryQuiz->description)
                                <p class="text-gray-600 mt-1">{{ $entryQuiz->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-blue-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Questions</p>
                                    <p class="text-2xl font-bold text-blue-900">{{ $questions->count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-amber-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-amber-600 font-medium">Score requis</p>
                                    <p class="text-2xl font-bold text-amber-900">{{ $entryQuiz->passing_score }}%</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/70 backdrop-blur-sm p-4 rounded-xl border border-green-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-green-600 font-medium">Durée</p>
                                    <p class="text-2xl font-bold text-green-900">Illimitée</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="text-amber-800 font-semibold mb-1">Important</h4>
                                <p class="text-amber-700 text-sm">
                                    Ce quiz d'entrée évalue votre niveau actuel. Si vous obtenez un score supérieur à {{ $entryQuiz->passing_score }}%,
                                    cela signifie que cette formation est trop facile pour vous. Un superadmin vous contactera
                                    pour vous proposer une formation plus adaptée à votre niveau.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire du quiz -->
            <form id="quiz-form" method="POST" action="{{ route('eleve.formation.entry-quiz.submit', [$team, $formation]) }}">
                @csrf

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-200">
                    <div class="p-8">
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Questions du quiz</h4>
                            <p class="text-sm text-gray-600">Répondez à toutes les questions. Vous ne pourrez soumettre qu'une seule fois.</p>
                        </div>

                        <div class="space-y-8">
                            @foreach($questions as $index => $question)
                                <div class="bg-gradient-to-r from-gray-50 to-slate-50 p-6 rounded-xl border border-gray-100">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-start">
                                        <span class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold mr-4 mt-0.5">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="flex-1">{{ $question->question }}</span>
                                    </h4>

                                    @if($question->type === 'multiple_choice')
                                        <div class="space-y-3 ml-12">
                                            @foreach($question->quizChoices as $choice)
                                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 cursor-pointer group">
                                                    <input type="radio" name="answers[{{ $question->id }}]"
                                                           value="{{ $choice->id }}"
                                                           class="text-blue-600 focus:ring-blue-500 focus:ring-2 mr-3">
                                                    <span class="text-gray-700 group-hover:text-gray-900">{{ $choice->choice_text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @elseif($question->type === 'true_false')
                                        <div class="space-y-3 ml-12">
                                            @foreach($question->quizChoices as $choice)
                                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 cursor-pointer group">
                                                    <input type="radio" name="answers[{{ $question->id }}]"
                                                           value="{{ $choice->id }}"
                                                           class="text-blue-600 focus:ring-blue-500 focus:ring-2 mr-3">
                                                    <span class="text-gray-700 group-hover:text-gray-900">{{ $choice->choice_text }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    <span id="answered-count">0</span> / {{ $questions->count() }} questions répondues
                                </div>
                                <x-primary-button type="submit" id="submit-btn"
                                                  class="px-8 py-3 text-base font-semibold bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Soumettre le quiz
                                </x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('quiz-form');
            const submitBtn = document.getElementById('submit-btn');
            const answeredCount = document.getElementById('answered-count');
            const totalQuestions = {{ $questions->count() }};

            // Mettre à jour le compteur de réponses
            function updateAnsweredCount() {
                let answered = 0;
                const questionIds = @json($questions->pluck('id'));

                questionIds.forEach(questionId => {
                    const inputs = form.querySelectorAll(`input[name="answers[${questionId}]"]`);
                    const hasAnswer = Array.from(inputs).some(input => input.checked);
                    if (hasAnswer) {
                        answered++;
                    }
                });

                answeredCount.textContent = answered;

                // Changer la couleur selon le progrès
                if (answered === totalQuestions) {
                    answeredCount.className = 'text-green-600 font-semibold';
                } else if (answered > 0) {
                    answeredCount.className = 'text-blue-600 font-semibold';
                } else {
                    answeredCount.className = 'text-gray-600';
                }
            }

            // Écouter les changements sur les inputs radio
            form.addEventListener('change', function(e) {
                if (e.target.type === 'radio') {
                    updateAnsweredCount();
                }
            });

            // Validation avant soumission
            form.addEventListener('submit', function(e) {
                let answeredQuestions = 0;
                const questionIds = @json($questions->pluck('id'));

                questionIds.forEach(questionId => {
                    const inputs = form.querySelectorAll(`input[name="answers[${questionId}]"]`);
                    const hasAnswer = Array.from(inputs).some(input => input.checked);
                    if (hasAnswer) {
                        answeredQuestions++;
                    }
                });

                if (answeredQuestions < totalQuestions) {
                    e.preventDefault();
                    alert(`Veuillez répondre à toutes les questions avant de soumettre.\n\nVous avez répondu à ${answeredQuestions} question(s) sur ${totalQuestions}.`);
                    return false;
                }

                // Désactiver le bouton et changer le texte
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Soumission en cours...
                `;
            });

            // Initialiser le compteur
            updateAnsweredCount();
        });
    </script>
</x-app-layout>
