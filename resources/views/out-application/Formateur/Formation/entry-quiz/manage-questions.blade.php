<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Questions du quiz d\'entrée') }} - {{ $formation->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Configuration du quiz
                </a>
                <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Retour aux chapitres
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations du quiz -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-2">{{ $quiz->title }}</h3>
                    @if($quiz->description)
                        <p class="text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
                    @endif
                    <div class="mt-4 flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <span>Score de passage: <strong>{{ $quiz->passing_score }}%</strong></span>
                        <span>Questions: <strong>{{ $questions->count() }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Liste des questions existantes -->
            @if($questions->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h4 class="text-md font-medium mb-4">Questions existantes</h4>
                        <div class="space-y-4">
                            @foreach($questions as $question)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-medium">{{ $question->question }}</h5>
                                        <div class="flex space-x-2">
                                            <button type="button"
                                                    onclick="editQuestion({{ $question->id }}, '{{ addslashes($question->question) }}', '{{ $question->type }}', {{ $question->quizChoices->toJson() }})"
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                Modifier
                                            </button>
                                            <form method="POST" action="{{ route('formateur.formation.entry-quiz.questions.delete', [$formation, $question]) }}"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette question ?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Type: {{ $question->type === 'multiple_choice' ? 'Choix multiple' : 'Vrai/Faux' }}
                                    </div>
                                    <div class="mt-2">
                                        <strong>Réponses:</strong>
                                        <ul class="list-disc list-inside ml-4 mt-1">
                                            @foreach($question->quizChoices as $choice)
                                                <li class="{{ $choice->is_correct ? 'text-green-600 font-medium' : '' }}">
                                                    {{ $choice->choice_text }}
                                                    @if($choice->is_correct)
                                                        <span class="text-green-600">(Correct)</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulaire d'ajout de question -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-md font-medium mb-4" id="form-title">Ajouter une nouvelle question</h4>

                    <form id="question-form" method="POST" action="{{ route('formateur.formation.entry-quiz.questions.store', $formation) }}">
                        @csrf

                        <!-- Type de question -->
                        <div class="mb-4">
                            <x-input-label for="question_type" :value="__('Type de question')" />
                            <select id="question_type" name="question_type"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="multiple_choice">Choix multiple</option>
                                <option value="true_false">Vrai/Faux</option>
                            </select>
                            <x-input-error :messages="$errors->get('question_type')" class="mt-2" />
                        </div>

                        <!-- Texte de la question -->
                        <div class="mb-4">
                            <x-input-label for="question_text" :value="__('Question')" />
                            <textarea id="question_text" name="question_text" rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      required placeholder="Saisissez votre question ici...">{{ old('question_text') }}</textarea>
                            <x-input-error :messages="$errors->get('question_text')" class="mt-2" />
                        </div>

                        <!-- Réponses -->
                        <div class="mb-4">
                            <x-input-label :value="__('Réponses')" />
                            <div id="choices-container" class="space-y-2">
                                <!-- Les choix seront ajoutés dynamiquement par JavaScript -->
                            </div>
                            <button type="button" id="add-choice-btn"
                                    class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                + Ajouter une réponse
                            </button>
                            <x-input-error :messages="$errors->get('choices')" class="mt-2" />
                        </div>

                        <!-- Champ caché pour les choix JSON -->
                        <input type="hidden" id="choices-input" name="choices" value="[]">

                        <div class="flex justify-end space-x-2">
                            <button type="button" id="cancel-edit-btn" class="hidden bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Annuler
                            </button>
                            <x-primary-button id="submit-btn">
                                Ajouter la question
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let choiceCounter = 0;
        let editingQuestionId = null;

        document.addEventListener('DOMContentLoaded', function() {
            initializeChoices();
            document.getElementById('question_type').addEventListener('change', handleQuestionTypeChange);
            document.getElementById('add-choice-btn').addEventListener('click', addChoice);
            document.getElementById('cancel-edit-btn').addEventListener('click', cancelEdit);
            document.getElementById('question-form').addEventListener('submit', prepareChoicesData);
        });

        function initializeChoices() {
            const questionType = document.getElementById('question_type').value;
            updateChoicesForType(questionType);
        }

        function handleQuestionTypeChange() {
            const questionType = this.value;
            updateChoicesForType(questionType);
        }

        function updateChoicesForType(type) {
            const container = document.getElementById('choices-container');
            container.innerHTML = '';

            if (type === 'true_false') {
                addTrueFalseChoices();
            } else {
                // Pour les choix multiples, commencer avec 2 choix vides
                addChoice();
                addChoice();
            }
        }

        function addTrueFalseChoices() {
            addChoiceField('Vrai', true);
            addChoiceField('Faux', false);
        }

        function addChoice() {
            const questionType = document.getElementById('question_type').value;
            if (questionType === 'true_false') return;

            addChoiceField('', false);
        }

        function addChoiceField(text = '', isCorrect = false) {
            const container = document.getElementById('choices-container');
            const choiceId = `choice-${choiceCounter++}`;

            const choiceDiv = document.createElement('div');
            choiceDiv.className = 'flex items-center space-x-2';
            choiceDiv.innerHTML = `
                <input type="text" value="${text}" placeholder="Saisir la réponse"
                       class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <label class="flex items-center">
                    <input type="radio" name="correct-choice" value="${choiceId}" ${isCorrect ? 'checked' : ''}
                           class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm">Correct</span>
                </label>
                <button type="button" onclick="removeChoice(this)"
                        class="text-red-600 hover:text-red-800 text-sm ${document.getElementById('question_type').value === 'true_false' ? 'hidden' : ''}">
                    Supprimer
                </button>
            `;

            container.appendChild(choiceDiv);
        }

        function removeChoice(button) {
            button.closest('.flex').remove();
        }

        function prepareChoicesData(e) {
            const choices = [];
            const choiceElements = document.querySelectorAll('#choices-container .flex');
            const correctChoiceValue = document.querySelector('input[name="correct-choice"]:checked')?.value;

            choiceElements.forEach((element, index) => {
                const input = element.querySelector('input[type="text"]');
                const choiceId = `choice-${index}`;

                choices.push({
                    text: input.value.trim(),
                    is_correct: correctChoiceValue === choiceId
                });
            });

            document.getElementById('choices-input').value = JSON.stringify(choices);
        }

        function editQuestion(questionId, questionText, questionType, choicesJson) {
            editingQuestionId = questionId;

            // Changer le titre du formulaire
            document.getElementById('form-title').textContent = 'Modifier la question';
            document.getElementById('submit-btn').textContent = 'Mettre à jour la question';
            document.getElementById('cancel-edit-btn').classList.remove('hidden');

            // Remplir les champs
            document.getElementById('question_type').value = questionType;
            document.getElementById('question_text').value = questionText;

            // Changer l'action du formulaire
            const form = document.getElementById('question-form');
            form.action = `/formateur/formation/${{{ $formation->id }}}/entry-quiz/questions/${questionId}`;
            form.method = 'POST';

            // Ajouter le champ _method pour PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';

            // Remplir les choix
            const choices = JSON.parse(choicesJson);
            updateChoicesForEdit(questionType, choices);

            // Scroll vers le formulaire
            form.scrollIntoView({ behavior: 'smooth' });
        }

        function updateChoicesForEdit(questionType, choices) {
            const container = document.getElementById('choices-container');
            container.innerHTML = '';
            choiceCounter = 0;

            choices.forEach((choice, index) => {
                const choiceId = `choice-${choiceCounter++}`;
                const choiceDiv = document.createElement('div');
                choiceDiv.className = 'flex items-center space-x-2';
                choiceDiv.innerHTML = `
                    <input type="text" value="${choice.choice_text}" placeholder="Saisir la réponse"
                           class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <label class="flex items-center">
                        <input type="radio" name="correct-choice" value="${choiceId}" ${choice.is_correct ? 'checked' : ''}
                               class="text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm">Correct</span>
                    </label>
                    <button type="button" onclick="removeChoice(this)"
                            class="text-red-600 hover:text-red-800 text-sm ${questionType === 'true_false' ? 'hidden' : ''}">
                        Supprimer
                    </button>
                `;

                container.appendChild(choiceDiv);
            });
        }

        function cancelEdit() {
            editingQuestionId = null;

            // Remettre le titre original
            document.getElementById('form-title').textContent = 'Ajouter une nouvelle question';
            document.getElementById('submit-btn').textContent = 'Ajouter la question';
            document.getElementById('cancel-edit-btn').classList.add('hidden');

            // Remettre l'action du formulaire
            const form = document.getElementById('question-form');
            form.action = '{{ route("formateur.formation.entry-quiz.questions.store", $formation) }}';
            form.method = 'POST';

            // Supprimer le champ _method
            const methodField = form.querySelector('input[name="_method"]');
            if (methodField) {
                methodField.remove();
            }

            // Vider les champs
            document.getElementById('question_text').value = '';
            initializeChoices();
        }
    </script>
</x-app-layout>
