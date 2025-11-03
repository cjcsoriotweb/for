<x-app-layout>
    <!-- Formation Details -->
    @if($errors->any())
    <div
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
        role="alert"
    >
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline"
            >Veuillez corriger les erreurs suivantes:</span
        >
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a
                                href="{{
                                    route(
                                        'formateur.formation.show',
                                        $formation
                                    )
                                }}"
                                class="text-sm text-gray-700 hover:text-indigo-600"
                            >
                                {{ $formation->title }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.chapter.edit',
                                            [$formation, $chapter]
                                        )
                                    }}"
                                    class="text-sm text-gray-700 hover:text-indigo-600 ml-1"
                                >
                                    Chapitre {{ $chapter->position }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <a
                                    href="{{
                                        route(
                                            'formateur.formation.chapter.lesson.quiz.edit',
                                            [$formation, $chapter, $lesson]
                                        )
                                    }}"
                                    class="text-sm text-gray-700 hover:text-indigo-600 ml-1"
                                >
                                    {{ $quiz->title }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg
                                    class="w-3 h-3 text-gray-400 mx-1"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"
                                    ></path>
                                </svg>
                                <span class="text-sm text-gray-500 ml-1"
                                    >Gestion des Questions</span
                                >
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Questions List -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white overflow-hidden sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">
                                        Questions du Quiz
                                    </h2>
                                    <p class="text-gray-600 mt-1">
                                        {{ $questions->count() }} question(s)
                                        dans ce quiz
                                    </p>
                                </div>
                                <button
                                    onclick="openQuestionModal()"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 inline-flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                    <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        ></path>
                                    </svg>
                                    Ajouter une Question
                                </button>
                            </div>

                            @if($questions->count() > 0)
                            <div class="space-y-4">
                                @foreach($questions as $index => $question)
                                <div
                                    class="border border-gray-200 rounded-lg p-4"
                                >
                                    <div
                                        class="flex items-start justify-between mb-3"
                                    >
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <span
                                                    class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded"
                                                >
                                                    Question {{ $index + 1 }}
                                                </span>
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}"
                                                >
                                                    {{ $question->type === 'multiple_choice' ? 'QCM' : 'V/F' }}
                                                </span>
                                            </div>
                                            <h3
                                                class="text-lg font-medium text-gray-900 mb-2"
                                            >
                                                {{ $question->question }}
                                            </h3>
                                            <div class="space-y-1">
                                                @foreach($question->quizChoices
                                                as $choice)
                                                <div class="flex items-center">
                                                    @if($choice->is_correct)
                                                    <svg
                                                        class="w-4 h-4 text-green-500 mr-2"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"
                                                        ></path>
                                                    </svg>
                                                    @else
                                                    <div
                                                        class="w-4 h-4 mr-2"
                                                    ></div>
                                                    @endif
                                                    <span
                                                        class="{{ $choice->is_correct ? 'font-medium text-green-700' : 'text-gray-700' }}"
                                                    >
                                                        {{ $choice->choice_text }}
                                                    </span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="flex space-x-2 ml-4">
                                            <button
                                                onclick="editQuestion({{ $question->id }}, '{{ addslashes($question->question) }}', '{{ $question->type }}', @json($question->quizChoices))"
                                                class="text-indigo-600 hover:text-indigo-900 p-1"
                                            >
                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    ></path>
                                                </svg>
                                            </button>
                                            <button
                                                onclick="deleteQuestion({{ $question->id }}, '{{ addslashes($question->question) }}')"
                                                class="text-red-600 hover:text-red-900 p-1"
                                            >
                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    ></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-12">
                                <svg
                                    class="mx-auto h-12 w-12 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    ></path>
                                </svg>
                                <h3
                                    class="mt-2 text-sm font-medium text-gray-900"
                                >
                                    Aucune question
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Commencez par ajouter des questions à votre
                                    quiz.
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quiz Info Sidebar -->
                <div>
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                Informations du Quiz
                            </h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">
                                        Titre
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $quiz->title }}
                                    </dd>
                                </div>
                                @if($quiz->description)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">
                                        Description
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $quiz->description }}
                                    </dd>
                                </div>
                                @endif
                                @if($quiz->max_attempts)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">
                                        Tentatives max
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $quiz->max_attempts }}
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">
                                        Questions
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ $questions->count() }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a
                            href="{{
                                route(
                                    'formateur.formation.chapter.lesson.quiz.edit',
                                    [$formation, $chapter, $lesson]
                                )
                            }}"
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-center inline-block"
                        >
                            ← Retour aux paramètres du Quiz
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Modal -->
    <div
        id="questionModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50"
    >
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
        >
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3
                        class="text-lg font-medium text-gray-900"
                        id="modalTitle"
                    >
                        Ajouter une Question
                    </h3>
                    <button
                        onclick="closeQuestionModal()"
                        class="text-gray-400 hover:text-gray-600"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>

                <form id="questionForm" method="POST">
                    @csrf
                    <input type="hidden" id="questionId" name="question_id" />

                    <!-- Hidden fields for form submission -->
                    <input type="hidden" id="formattedChoices" name="choices" />

                    <!-- Question Text -->
                    <div class="mb-4">
                        <label
                            for="question_text"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Texte de la Question *
                        </label>
                        <textarea
                            id="question_text"
                            name="question_text"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Ex: Quelle est la capitale de la France ?"
                            required
                        ></textarea>
                    </div>

                    <!-- Question Type -->
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Type de Question *
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="question_type"
                                    value="multiple_choice"
                                    checked
                                    class="text-indigo-600 focus:ring-indigo-500"
                                    onchange="toggleQuestionType()"
                                />
                                <span class="ml-2 text-sm text-gray-700"
                                    >Choix multiples</span
                                >
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    name="question_type"
                                    value="true_false"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                    onchange="toggleQuestionType()"
                                />
                                <span class="ml-2 text-sm text-gray-700"
                                    >Vrai/Faux</span
                                >
                            </label>
                        </div>
                    </div>

                    <!-- Choices -->
                    <div id="choicesContainer" class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Réponses *
                        </label>
                        <div id="choicesList" class="space-y-2">
                            <div
                                class="choice-item flex items-center space-x-2"
                            >
                                <input
                                    type="radio"
                                    name="correct_choice"
                                    value="0"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <input
                                    type="text"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Réponse 1"
                                />
                                <button
                                    type="button"
                                    onclick="removeChoice(this)"
                                    class="text-red-600 hover:text-red-900 p-1 hidden"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                            <div
                                class="choice-item flex items-center space-x-2"
                            >
                                <input
                                    type="radio"
                                    name="correct_choice"
                                    value="1"
                                    class="text-indigo-600 focus:ring-indigo-500"
                                />
                                <input
                                    type="text"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Réponse 2"
                                />
                                <button
                                    type="button"
                                    onclick="removeChoice(this)"
                                    class="text-red-600 hover:text-red-900 p-1 hidden"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        ></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button
                            type="button"
                            onclick="addChoice()"
                            class="mt-2 text-sm text-indigo-600 hover:text-indigo-900"
                        >
                            + Ajouter une réponse
                        </button>
                    </div>

                    <!-- Modal Actions -->
                    <div
                        class="flex justify-end space-x-3 pt-4 border-t border-gray-200"
                    >
                        <button
                            type="button"
                            onclick="closeQuestionModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span id="modalSubmitText"
                                >Ajouter la Question</span
                            >
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openQuestionModal(
            questionId = null,
            questionText = "",
            questionType = "multiple_choice",
            choices = []
        ) {
            const modal = document.getElementById("questionModal");
            const form = document.getElementById("questionForm");
            const modalTitle = document.getElementById("modalTitle");
            const modalSubmitText = document.getElementById("modalSubmitText");
            const questionInput = document.getElementById("question_text");
            const questionIdInput = document.getElementById("questionId");

            if (questionId) {
                // Edit mode
                modalTitle.textContent = "Modifier la Question";
                modalSubmitText.textContent = "Mettre à Jour la Question";
                questionIdInput.value = questionId;
                questionInput.value = questionText;
                document.querySelector(
                    `input[name="question_type"][value="${questionType}"]`
                ).checked = true;

                // Clear existing choices
                document.getElementById("choicesList").innerHTML = "";

                // Add choices
                choices.forEach((choice, index) => {
                    addChoice(choice.choice_text, choice.is_correct);
                });

                // Update form action for edit
                form.action = `/formateur/formation/{{ $formation->id }}/chapitre/{{ $chapter->id }}/lesson/{{ $lesson->id }}/quiz/{{ $quiz->id }}/questions/${questionId}`;
                form.method = "POST";
                // Remove any existing method input first
                const existingMethod = form.querySelector(
                    'input[name="_method"]'
                );
                if (existingMethod) existingMethod.remove();
                form.innerHTML += '@method("PUT")';
            } else {
                // Add mode
                modalTitle.textContent = "Ajouter une Question";
                modalSubmitText.textContent = "Ajouter la Question";
                questionIdInput.value = "";
                questionInput.value = "";
                document.querySelector(
                    'input[name="question_type"][value="multiple_choice"]'
                ).checked = true;

                // Reset to default choices
                document.getElementById("choicesList").innerHTML = `
                    <div class="choice-item flex items-center space-x-2">
                        <input type="radio" name="correct_choice" value="0" class="text-indigo-600 focus:ring-indigo-500">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Réponse 1">
                        <button type="button" onclick="removeChoice(this)" class="text-red-600 hover:text-red-900 p-1 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="choice-item flex items-center space-x-2">
                        <input type="radio" name="correct_choice" value="1" class="text-indigo-600 focus:ring-indigo-500">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Réponse 2">
                        <button type="button" onclick="removeChoice(this)" class="text-red-600 hover:text-red-900 p-1 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                `;

                // Update form action for create
                form.action = `/formateur/formation/{{ $formation->id }}/chapitre/{{ $chapter->id }}/lesson/{{ $lesson->id }}/quiz/{{ $quiz->id }}/questions`;
                form.method = "POST";
                // Remove any existing method input
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();
            }

            toggleQuestionType();
            modal.classList.remove("hidden");
        }

        function closeQuestionModal() {
            const modal = document.getElementById("questionModal");
            modal.classList.add("hidden");
        }

        function addChoice(text = "", isCorrect = false) {
            const choicesList = document.getElementById("choicesList");
            const choiceIndex = choicesList.children.length;
            const choiceItem = document.createElement("div");
            choiceItem.className = "choice-item flex items-center space-x-2";
            choiceItem.innerHTML = `
                <input type="radio" name="correct_choice" value="${choiceIndex}" class="text-indigo-600 focus:ring-indigo-500" ${
                isCorrect ? "checked" : ""
            }>
                <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Réponse ${
                    choiceIndex + 1
                }" value="${text}">
                <button type="button" onclick="removeChoice(this)" class="text-red-600 hover:text-red-900 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            choicesList.appendChild(choiceItem);
        }

        function removeChoice(button) {
            const choiceItem = button.parentElement;
            choiceItem.remove();
            updateChoiceIndexes();
        }

        function updateChoiceIndexes() {
            const choices = document.querySelectorAll(".choice-item");
            choices.forEach((choice, index) => {
                const radio = choice.querySelector('input[type="radio"]');
                const input = choice.querySelector('input[type="text"]');
                radio.value = index;
                input.placeholder = `Réponse ${index + 1}`;
            });
        }

        function toggleQuestionType() {
            const questionType = document.querySelector(
                'input[name="question_type"]:checked'
            ).value;
            const choicesContainer =
                document.getElementById("choicesContainer");
            const choicesList = document.getElementById("choicesList");

            if (questionType === "true_false") {
                // For true/false, only show 2 choices
                choicesList.innerHTML = `
                    <div class="choice-item flex items-center space-x-2">
                        <input type="radio" name="correct_choice" value="0" class="text-indigo-600 focus:ring-indigo-500">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Vrai" readonly value="Vrai">
                        <button type="button" onclick="removeChoice(this)" class="text-red-600 hover:text-red-900 p-1 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="choice-item flex items-center space-x-2">
                        <input type="radio" name="correct_choice" value="1" class="text-indigo-600 focus:ring-indigo-500">
                        <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Faux" readonly value="Faux">
                        <button type="button" onclick="removeChoice(this)" class="text-red-600 hover:text-red-900 p-1 hidden">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                `;
                choicesContainer
                    .querySelector('button[onclick="addChoice()"]')
                    .classList.add("hidden");
            } else {
                // For multiple choice, allow adding more choices
                if (choicesList.children.length === 0) {
                    addChoice();
                    addChoice();
                }
                choicesContainer
                    .querySelector('button[onclick="addChoice()"]')
                    .classList.remove("hidden");
            }
        }

        function deleteQuestion(questionId, questionText) {
            if (
                confirm(
                    `Êtes-vous sûr de vouloir supprimer la question : "${questionText}" ?`
                )
            ) {
                const form = document.createElement("form");
                form.method = "POST";
                form.action = `/formateur/formation/{{ $formation->id }}/chapitre/{{ $chapter->id }}/lesson/{{ $lesson->id }}/quiz/{{ $quiz->id }}/questions/${questionId}`;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document
            .getElementById("questionModal")
            .addEventListener("click", function (e) {
                if (e.target === this) {
                    closeQuestionModal();
                }
            });

        // Handle form submission
        document
            .getElementById("questionForm")
            .addEventListener("submit", function (e) {
                e.preventDefault(); // Prevent default form submission

                const questionText = document
                    .getElementById("question_text")
                    .value.trim();
                const questionType = document.querySelector(
                    'input[name="question_type"]:checked'
                ).value;
                const choiceItems = document.querySelectorAll(".choice-item");

                // Validate question text
                if (!questionText) {
                    alert("Veuillez saisir le texte de la question.");
                    return;
                }

                // Validate choices
                if (choiceItems.length < 2) {
                    alert("Veuillez ajouter au moins 2 réponses.");
                    return;
                }

                // Collect choices data
                const choices = [];
                let hasCorrectAnswer = false;

                choiceItems.forEach((item, index) => {
                    const textInput = item.querySelector('input[type="text"]');
                    const radioInput = item.querySelector(
                        'input[type="radio"]'
                    );
                    const choiceText = textInput.value.trim();

                    if (!choiceText) {
                        alert(
                            `Veuillez saisir le texte de la réponse ${
                                index + 1
                            }.`
                        );
                        throw new Error("Validation failed");
                    }

                    const isCorrect = radioInput.checked;
                    if (isCorrect) {
                        hasCorrectAnswer = true;
                    }

                    choices.push({
                        text: choiceText,
                        is_correct: isCorrect,
                    });
                });

                // Validate that at least one correct answer is selected
                if (!hasCorrectAnswer) {
                    alert(
                        "Veuillez sélectionner au moins une réponse correcte."
                    );
                    return;
                }

                // Set the formatted choices in the hidden input
                document.getElementById("formattedChoices").value =
                    JSON.stringify(choices);

                // Submit the form
                this.submit();
            });
    </script>
</x-app-layout>
