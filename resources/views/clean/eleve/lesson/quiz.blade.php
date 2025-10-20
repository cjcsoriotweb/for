<x-eleve-layout :team="$team">
    {{-- Messages de notification --}}
    @if(session('success'))
    <div
        class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"
    >
        {{ session("success") }}
    </div>
    @endif @if(session('warning'))
    <div
        class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg"
    >
        {{ session("warning") }}
    </div>
    @endif @if(session('error'))
    <div
        class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"
    >
        {{ session("error") }}
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a
                        href="{{ route('eleve.index', $team) }}"
                        class="text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white"
                    >
                        Accueil
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg
                            class="w-3 h-3 text-gray-400 mx-1"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 9 4-4-4-4"
                            />
                        </svg>
                        <a
                            href="{{
                                route('eleve.formation.show', [
                                    $team,
                                    $formation
                                ])
                            }}"
                            class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"
                        >
                            {{ $formation->title }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg
                            class="w-3 h-3 text-gray-400 mx-1"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 9 4-4-4-4"
                            />
                        </svg>
                        <span
                            class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400"
                            >{{ $chapter->title }}</span
                        >
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg
                            class="w-3 h-3 text-gray-400 mx-1"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 6 10"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 9 4-4-4-4"
                            />
                        </svg>
                        <span
                            class="ml-1 text-blue-600 md:ml-2 dark:text-blue-400"
                            >Quiz: {{ $lesson->title }}</span
                        >
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Quiz Header -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            Quiz: {{ $lesson->title }}
                        </h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Répondez à toutes les questions ci-dessous
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Questions
                        </div>
                        <div class="text-lg font-semibold text-blue-600">
                            {{ $questions->count() }}
                        </div>
                    </div>
                </div>

                <!-- Quiz Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Score de passage
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $quiz->passing_score }}%
                        </div>
                    </div>

                    @if($quiz->max_attempts > 0)
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Tentatives restantes
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ max(0, $quiz->max_attempts - ($attempts ?? 0)) }}
                        </div>
                    </div>
                    @endif

                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div
                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                        >
                            Points possibles
                        </div>
                        <div
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ $questions->sum('points') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Form -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
        >
            <form
                id="quiz-form"
                method="POST"
                action="{{
                    route('eleve.lesson.quiz.submit', [
                        $team,
                        $formation,
                        $chapter,
                        $lesson
                    ])
                }}"
            >
                @csrf
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="quiz-questions">
                        @foreach($questions as $index => $question)
                        <div
                            class="mb-8 pb-8 border-b border-gray-200 dark:border-gray-700 last:border-b-0"
                        >
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold mb-2">
                                    Question {{ $index + 1 }}:
                                    {{ $question->question }}
                                </h3>
                                @if($question->explanation)
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-400 mb-3"
                                >
                                    {{ $question->explanation }}
                                </p>
                                @endif
                                <div
                                    class="text-sm text-gray-500 dark:text-gray-400 mb-3"
                                >
                                    {{ $question->points }}
                                    point{{ $question->points > 1 ? 's' : '' }}
                                </div>
                            </div>

                            <div class="space-y-3">
                                @foreach($question->quizChoices as $choice)
                                <label
                                    class="flex items-start space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:dark:bg-blue-900/20 has-[:checked]:dark:border-blue-400"
                                >
                                    <input
                                        type="radio"
                                        name="answers[{{ $question->id }}]"
                                        value="{{ $choice->id }}"
                                        class="mt-1 text-blue-600 focus:ring-blue-500 focus:ring-2"
                                        required
                                    />
                                    <span
                                        class="flex-1 has-[:checked]:text-blue-900 has-[:checked]:dark:text-blue-100"
                                        >{{ $choice->choice_text }}</span
                                    >
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 text-center">
                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg"
                        >
                            Soumettre le Quiz
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quiz Results (hidden initially) -->
        <div
            id="quiz-results"
            class="hidden bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6"
        >
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="text-center">
                    <h2 class="text-2xl font-bold mb-4" id="result-title"></h2>
                    <div class="text-6xl mb-4" id="result-score"></div>
                    <p class="text-lg mb-6" id="result-message"></p>

                    <div class="flex justify-center space-x-4">
                        <a
                            href="{{
                                route('eleve.lesson.show', [
                                    $team,
                                    $formation,
                                    $chapter,
                                    $lesson
                                ])
                            }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded"
                        >
                            Retour à la leçon
                        </a>
                        @if($quiz->max_attempts == 0 || ($attempts ?? 0) <
                        $quiz->max_attempts)
                        <button
                            onclick="retryQuiz()"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded"
                        >
                            Réessayer
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document
            .getElementById("quiz-form")
            .addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            showQuizResults(data);
                        } else {
                            alert("Erreur lors de la soumission du quiz");
                        }
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert("Erreur lors de la soumission du quiz");
                    });
            });

        function showQuizResults(data) {
            // Hide quiz form
            document.getElementById("quiz-questions").style.display = "none";
            document.querySelector(
                '#quiz-form button[type="submit"]'
            ).style.display = "none";

            // Show results
            const resultsDiv = document.getElementById("quiz-results");
            resultsDiv.classList.remove("hidden");

            const title = document.getElementById("result-title");
            const score = document.getElementById("result-score");
            const message = document.getElementById("result-message");

            if (data.passed) {
                title.textContent = "🎉 Quiz réussi !";
                title.className = "text-2xl font-bold mb-4 text-green-600";
                score.textContent = data.score + "%";
                score.className = "text-6xl mb-4 text-green-600";
                message.textContent = `Vous avez obtenu ${data.correct_answers} bonnes réponses sur ${data.total_questions}.`;
            } else {
                title.textContent = "❌ Quiz échoué";
                title.className = "text-2xl font-bold mb-4 text-red-600";
                score.textContent = data.score + "%";
                score.className = "text-6xl mb-4 text-red-600";
                message.textContent = `Vous avez obtenu ${data.correct_answers} bonnes réponses sur ${data.total_questions}. Score minimum requis: ${data.passing_score}%.`;
            }
        }

        function retryQuiz() {
            location.reload();
        }
    </script>
</x-eleve-layout>
