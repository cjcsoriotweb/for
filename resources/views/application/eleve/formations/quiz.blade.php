<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">quiz</span>
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-xl text-white leading-tight">Quiz - {{ $lesson->title }}</h2>
                <p class="text-emerald-100 text-sm">{{ $formation->title }} - {{ $chapter->title }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('application.eleve.formations.lesson', [$team, $formation, $chapter, $lesson]) }}"
                    class="inline-flex items-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                    <span class="material-symbols-outlined mr-2">arrow_back</span>
                    Retour à la leçon
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Informations sur le quiz -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 mb-8">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl text-emerald-600 dark:text-emerald-400">quiz</span>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $quiz->title ?? 'Quiz d\'évaluation' }}</h1>
                        @if($quiz->description)
                            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ $quiz->description }}</p>
                        @endif
                        <div class="flex items-center space-x-6 mt-3 text-sm text-slate-500 dark:text-slate-400">
                            <span>{{ $questions->count() }} question{{ $questions->count() > 1 ? 's' : '' }}</span>
                            <span>•</span>
                            <span>À choix multiples</span>
                        </div>
                    </div>
                </div>

                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg p-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">info</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-emerald-800 dark:text-emerald-200 text-sm">
                                Répondez à toutes les questions. Vous pouvez modifier vos réponses avant de soumettre le quiz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire du quiz -->
            <form method="POST" action="{{ route('application.eleve.formations.quiz.submit', [$team, $formation, $chapter, $lesson, $quiz]) }}" id="quiz-form">
                @csrf

                <div class="space-y-8">
                    @foreach($questions as $index => $question)
                        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4 bg-slate-50 dark:bg-slate-700/50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center text-sm font-semibold text-slate-600 dark:text-slate-400">
                                        {{ $index + 1 }}
                                    </div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white">{{ $question->question_text }}</h3>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="space-y-3">
                                    @foreach($question->quizChoices as $choice)
                                        <label class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition-colors">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   value="{{ $choice->id }}"
                                                   class="w-4 h-4 text-emerald-600 bg-slate-100 border-slate-300 dark:bg-slate-700 dark:border-slate-600 focus:ring-emerald-500"
                                                   required>
                                            <div class="flex-1">
                                                <span class="text-slate-900 dark:text-white">{{ $choice->choice_text }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Actions du quiz -->
                <div class="mt-8 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            Assurez-vous d'avoir répondu à toutes les questions
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button"
                                    onclick="submitQuiz()"
                                    class="inline-flex items-center px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="submit-btn">
                                <span class="material-symbols-outlined mr-2">send</span>
                                Soumettre le quiz
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function submitQuiz() {
            if (confirm('Êtes-vous sûr de vouloir soumettre votre quiz ? Vous ne pourrez plus modifier vos réponses.')) {
                const form = document.getElementById('quiz-form');
                const submitBtn = document.getElementById('submit-btn');

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="material-symbols-outlined mr-2 animate-spin">refresh</span>Soumission en cours...';

                form.submit();
            }
        }

        // Validation côté client
        document.getElementById('quiz-form').addEventListener('submit', function(e) {
            const requiredInputs = document.querySelectorAll('input[required]');
            let allAnswered = true;

            requiredInputs.forEach(input => {
                const questionGroup = input.name;
                const checked = document.querySelector(`input[name="${questionGroup}"]:checked`);
                if (!checked) {
                    allAnswered = false;
                }
            });

            if (!allAnswered) {
                e.preventDefault();
                alert('Veuillez répondre à toutes les questions avant de soumettre le quiz.');
            }
        });
    </script>
</x-application-layout>
