@php
    $rawChoices = old('choices');
    $initialChoices = [];

    if ($rawChoices) {
        $decoded = json_decode($rawChoices, true);
        if (is_array($decoded)) {
            $initialChoices = $decoded;
        }
    }

    if (empty($initialChoices)) {
        $initialChoices = [
            ['text' => 'Premiere reponse', 'is_correct' => true],
            ['text' => 'Deuxieme reponse', 'is_correct' => false],
        ];
    }
@endphp

<x-app-layout>
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 shadow">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-blue-100">{{ $formation->title }}</p>
                    <h1 class="text-2xl font-semibold text-white">Ajouter une question</h1>
                </div>
                <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                    class="inline-flex items-center rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/20">
                    Retour a la liste
                </a>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="px-6 py-6">
                    <h2 class="text-lg font-semibold text-slate-900">{{ $quiz->title }}</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Creez une nouvelle question pour le quiz d entree.
                    </p>
                </div>

                <div class="border-t border-slate-200 px-6 py-6">
                    <form id="question-form" method="POST"
                        action="{{ route('formateur.formation.entry-quiz.questions.store', $formation) }}"
                        class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="question_text" class="text-sm font-medium text-slate-700">
                                Intitule de la question
                            </label>
                            <textarea id="question_text" name="question_text" rows="3"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Saisissez la question">{{ old('question_text') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label for="question_type" class="text-sm font-medium text-slate-700">
                                Type de question
                            </label>
                            <select id="question_type" name="question_type"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="multiple_choice" @selected(old('question_type', 'multiple_choice') === 'multiple_choice')>
                                    Choix multiple
                                </option>
                                <option value="true_false" @selected(old('question_type') === 'true_false')>
                                    Vrai / Faux
                                </option>
                            </select>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-slate-700">
                                    Reponses proposees
                                </label>
                                <button type="button" id="add-choice"
                                    class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                    Ajouter une reponse
                                </button>
                            </div>

                            <div id="choices-list" class="space-y-3"></div>
                        </div>

                        <input type="hidden" name="choices" id="choices-field">

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                                class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                Annuler
                            </a>
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                Enregistrer la question
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        (() => {
            const initialChoices = @json($initialChoices);
            const form = document.getElementById('question-form');
            const typeSelect = document.getElementById('question_type');
            const addChoiceBtn = document.getElementById('add-choice');
            const choicesContainer = document.getElementById('choices-list');
            const choicesField = document.getElementById('choices-field');

            const TRUE_FALSE_PRESET = [
                { text: 'Vrai', is_correct: true },
                { text: 'Faux', is_correct: false },
            ];

            function createChoiceRow(choice, index) {
                const row = document.createElement('div');
                row.className = 'choice-row flex items-start gap-3 rounded-lg border border-slate-200 px-4 py-3';
                row.innerHTML = `
                    <div class="flex-1 space-y-1">
                        <label class="text-xs font-medium text-slate-500">reponse ${index + 1}</label>
                        <input type="text" class="choice-text w-full rounded-md border border-slate-300 px-3 py-1.5 text-sm focus:border-indigo-500 focus:ring-indigo-500" value="${choice.text ?? ''}">
                    </div>
                    <div class="flex flex-col items-center gap-2">
                        <label class="flex items-center gap-2 text-xs font-medium text-slate-500">
                            <input type="checkbox" class="choice-correct h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" ${choice.is_correct ? 'checked' : ''}>
                            Correct
                        </label>
                        <button type="button" class="remove-choice text-xs font-medium text-rose-600 hover:text-rose-700">
                            Supprimer
                        </button>
                    </div>
                `;
                return row;
            }

            function renderChoices(choices) {
                choicesContainer.innerHTML = '';
                choices.forEach((choice, index) => {
                    const row = createChoiceRow(choice, index);
                    choicesContainer.appendChild(row);
                });
                toggleRemoveButtons();
            }

            function getCurrentChoices() {
                const rows = [...choicesContainer.querySelectorAll('.choice-row')];
                return rows.map((row) => ({
                    text: row.querySelector('.choice-text').value.trim(),
                    is_correct: row.querySelector('.choice-correct').checked,
                }));
            }

            function toggleRemoveButtons() {
                const rows = choicesContainer.querySelectorAll('.choice-row');
                const removeButtons = choicesContainer.querySelectorAll('.remove-choice');
                removeButtons.forEach((button) => {
                    button.disabled = rows.length <= 2;
                    button.classList.toggle('opacity-50', rows.length <= 2);
                    button.classList.toggle('cursor-not-allowed', rows.length <= 2);
                });
            }

            function ensureMinimumChoices() {
                let choices = getCurrentChoices();
                if (choices.length < 2) {
                    while (choices.length < 2) {
                        choices.push({ text: '', is_correct: false });
                    }
                    renderChoices(choices);
                }
            }

            function syncWithType(force = false) {
                if (typeSelect.value === 'true_false') {
                    renderChoices(TRUE_FALSE_PRESET);
                    addChoiceBtn.disabled = true;
                    addChoiceBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    if (force) {
                        renderChoices(initialChoices);
                    } else {
                        ensureMinimumChoices();
                    }
                    addChoiceBtn.disabled = false;
                    addChoiceBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            addChoiceBtn.addEventListener('click', () => {
                const choices = getCurrentChoices();
                choices.push({ text: '', is_correct: false });
                renderChoices(choices);
            });

            choicesContainer.addEventListener('click', (event) => {
                if (event.target.classList.contains('remove-choice')) {
                    const rows = choicesContainer.querySelectorAll('.choice-row');
                    if (rows.length <= 2) {
                        return;
                    }
                    const row = event.target.closest('.choice-row');
                    row.remove();
                    toggleRemoveButtons();
                }
            });

            typeSelect.addEventListener('change', () => syncWithType());

            form.addEventListener('submit', (event) => {
                const choices = getCurrentChoices();
                if (choices.length < 2) {
                    event.preventDefault();
                    alert('Veuillez renseigner au moins deux reponses.');
                    return;
                }

                const hasText = choices.every((choice) => choice.text.length > 0);
                if (!hasText) {
                    event.preventDefault();
                    alert('Veuillez remplir le texte de chaque reponse.');
                    return;
                }

                const hasCorrect = choices.some((choice) => choice.is_correct);
                if (!hasCorrect) {
                    event.preventDefault();
                    alert('Veuillez Selectionner au moins une reponse correcte.');
                    return;
                }

                choicesField.value = JSON.stringify(choices);
            });

            renderChoices(initialChoices);
            syncWithType(true);
        })();
    </script>
</x-app-layout>
