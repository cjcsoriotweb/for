<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Quiz d\'entrée') }} - {{ $formation->title }}
            </h2>
            <a href="{{ route('eleve.formation.show', [$team, $formation]) }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour à la formation
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations du quiz -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-2">{{ $entryQuiz->title }}</h3>
                    @if($entryQuiz->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $entryQuiz->description }}</p>
                    @endif
                    <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <span>Questions: <strong>{{ $questions->count() }}</strong></span>
                        <span>Score requis: <strong>{{ $entryQuiz->passing_score }}%</strong></span>
                    </div>
                    <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-blue-800 dark:text-blue-200 text-sm">
                            <strong>Important:</strong> Ce quiz d'entrée évalue votre niveau actuel.
                            Si votre score est inférieur à {{ $entryQuiz->passing_score }}%, cela signifie que cette formation
                            pourrait ne pas correspondre à votre niveau. Un superadmin vous contactera pour vous orienter
                            vers une formation plus adaptée.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulaire du quiz -->
            <form id="quiz-form" method="POST" action="{{ route('eleve.formation.entry-quiz.submit', [$team, $formation]) }}">
                @csrf

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @foreach($questions as $index => $question)
                            <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                <h4 class="text-lg font-medium mb-4">
                                    {{ $index + 1 }}. {{ $question->question }}
                                </h4>

                                @if($question->type === 'multiple_choice')
                                    <div class="space-y-2">
                                        @foreach($question->quizChoices as $choice)
                                            <label class="flex items-center">
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                       value="{{ $choice->id }}"
                                                       class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-3">{{ $choice->choice_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($question->type === 'true_false')
                                    <div class="space-y-2">
                                        @foreach($question->quizChoices as $choice)
                                            <label class="flex items-center">
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                       value="{{ $choice->id }}"
                                                       class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-3">{{ $choice->choice_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="flex justify-end pt-6">
                            <x-primary-button type="submit" id="submit-btn">
                                Soumettre le quiz
                            </x-primary-button>
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

            form.addEventListener('submit', function(e) {
                // Vérifier que toutes les questions ont une réponse
                const questions = {{ $questions->count() }};
                let answeredQuestions = 0;

                for (let i = 0; i < questions; i++) {
                    const questionInputs = form.querySelectorAll(`input[name="answers[{{ $questions[$i]->id }}]"]`);
                    const hasAnswer = Array.from(questionInputs).some(input => input.checked);
                    if (hasAnswer) {
                        answeredQuestions++;
                    }
                }

                if (answeredQuestions < questions) {
                    e.preventDefault();
                    alert(`Veuillez répondre à toutes les questions. Vous avez répondu à ${answeredQuestions} question(s) sur ${questions}.`);
                    return false;
                }

                // Désactiver le bouton et changer le texte
                submitBtn.disabled = true;
                submitBtn.textContent = 'Soumission en cours...';
            });
        });
    </script>
</x-app-layout>
