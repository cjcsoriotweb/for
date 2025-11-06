<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('formateur.formation.show', $formation) }}" class="text-sm text-gray-700 hover:text-indigo-600">
                                {{ $formation->title }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('formateur.formation.chapter.edit', [$formation, $chapter]) }}" class="text-sm text-gray-700 hover:text-indigo-600 ml-1">
                                    Chapitre {{ $chapter->position }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('formateur.formation.chapter.lesson.quiz.edit', [$formation, $chapter, $lesson]) }}" class="text-sm text-gray-700 hover:text-indigo-600 ml-1">
                                    {{ $quiz->title }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1">
                                    Tester le quiz
                                </span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Tester le quiz</h1>
                            <p class="text-gray-600 mt-1">
                                Previsualisez l'experience eleve et verifiez que toutes les questions fonctionnent comme prevu.
                            </p>
                        </div>
                        <a
                            href="{{ route('formateur.formation.chapter.lesson.quiz.questions', [$formation, $chapter, $lesson, $quiz]) }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300"
                        >
                            Retour aux questions
                        </a>
                    </div>

                    @if($questions->isEmpty())
                        <div class="mt-6 rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 p-6 text-center">
                            <h2 class="text-lg font-semibold text-gray-800">Aucune question pour le moment</h2>
                            <p class="mt-2 text-sm text-gray-600">
                                Ajoutez au moins une question pour pouvoir tester le quiz.
                            </p>
                        </div>
                    @else
                        <form id="quizTestForm" class="mt-6 space-y-6">
                            @foreach($questions as $index => $question)
                                <div class="border border-gray-200 rounded-lg p-4" data-question-id="{{ $question->id }}">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="flex items-center mb-2 gap-2">
                                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                    Question {{ $index + 1 }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $question->type === 'multiple_choice' ? 'QCM' : 'V/F' }}
                                                </span>
                                            </div>
                                            <h2 class="text-lg font-semibold text-gray-900">
                                                {{ $question->question }}
                                            </h2>
                                        </div>
                                    </div>
                                    <div class="mt-4 space-y-3">
                                        @foreach($question->quizChoices as $choice)
                                            <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg hover:border-indigo-300 transition-colors cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="question_{{ $question->id }}"
                                                    value="{{ $choice->id }}"
                                                    data-is-correct="{{ $choice->is_correct ? 1 : 0 }}"
                                                    class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                                >
                                                <span class="text-gray-800">{{ $choice->choice_text }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="mt-3 text-sm hidden" data-question-result></p>
                                </div>
                            @endforeach

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                                <button
                                    type="button"
                                    id="quizTestReset"
                                    class="inline-flex justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300"
                                >
                                    Reinitialiser
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex justify-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Corriger mes reponses
                                </button>
                            </div>
                        </form>

                        <div
                            id="quizTestSummary"
                            class="mt-6 hidden border border-green-200 bg-green-50 text-green-700 px-4 py-3 rounded-lg"
                        >
                            <p class="font-semibold">Resultats du test</p>
                            <p class="mt-1 text-sm" data-summary-details></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('quizTestForm');
            if (!form) {
                return;
            }

            const summary = document.getElementById('quizTestSummary');
            const summaryDetails = summary ? summary.querySelector('[data-summary-details]') : null;
            const resetButton = document.getElementById('quizTestReset');

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const questionBlocks = form.querySelectorAll('[data-question-id]');
                let total = questionBlocks.length;
                let answered = 0;
                let correct = 0;

                questionBlocks.forEach(function (block) {
                    const inputs = block.querySelectorAll('input[type="radio"]');
                    const selected = block.querySelector('input[type="radio"]:checked');
                    const result = block.querySelector('[data-question-result]');

                    if (!result) {
                        return;
                    }

                    result.classList.remove('text-green-600', 'text-red-600', 'text-gray-600', 'hidden');

                    if (!selected) {
                        result.textContent = 'Aucune reponse selectionnee.';
                        result.classList.add('text-gray-600');
                        result.classList.remove('hidden');
                        return;
                    }

                    answered += 1;

                    if (selected.dataset.isCorrect === '1') {
                        correct += 1;
                        result.textContent = 'Bonne reponse !';
                        result.classList.add('text-green-600');
                    } else {
                        const correctInput = Array.prototype.find.call(inputs, function (input) {
                            return input.dataset.isCorrect === '1';
                        });

                        let correctText = '';
                        if (correctInput) {
                            const label = correctInput.closest('label');
                            const textSpan = label ? label.querySelector('span') : null;
                            if (textSpan) {
                                correctText = textSpan.textContent.trim();
                            }
                        }

                        result.textContent = correctText
                            ? 'Mauvaise reponse. Reponse attendue : ' + correctText
                            : 'Mauvaise reponse.';
                        result.classList.add('text-red-600');
                    }

                    result.classList.remove('hidden');
                });

                if (summary && summaryDetails) {
                    const score = total > 0 ? Math.round((correct / total) * 100) : 0;
                    summary.classList.remove('hidden');
                    summaryDetails.textContent =
                        'Score : ' +
                        correct +
                        '/' +
                        total +
                        ' (' +
                        score +
                        '%). Questions repondues : ' +
                        answered +
                        '/' +
                        total +
                        '.';
                }
            });

            if (resetButton) {
                resetButton.addEventListener('click', function () {
                    form.reset();

                    const resultMessages = form.querySelectorAll('[data-question-result]');
                    resultMessages.forEach(function (result) {
                        result.textContent = '';
                        result.classList.add('hidden');
                    });

                    if (summary) {
                        summary.classList.add('hidden');
                    }

                    if (summaryDetails) {
                        summaryDetails.textContent = '';
                    }
                });
            }
        })();
    </script>
</x-app-layout>
