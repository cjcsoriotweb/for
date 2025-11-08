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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
                <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="px-6 py-5 border-b border-slate-200">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Configuration</p>
                        <h2 class="mt-2 text-lg font-semibold text-slate-900">{{ $quiz->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Creez une nouvelle question qui s integrera naturellement dans le quiz d entree.
                        </p>
                    </div>

                    <div class="px-6 py-6">
                        <form id="question-form" method="POST"
                            action="{{ route('formateur.formation.entry-quiz.questions.store', $formation) }}"
                            class="space-y-8">
                            @csrf

                            <div class="space-y-3">
                                <div class="flex flex-col gap-1">
                                    <label for="question_text" class="text-sm font-medium text-slate-700">
                                        Intitule de la question
                                    </label>
                                    <p class="text-sm text-slate-500">
                                        Decrivez clairement la question, vous pouvez ajouter du contexte ou un scenario.
                                    </p>
                                </div>
                                <textarea id="question_text" name="question_text" rows="4"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Saisissez la question">{{ old('question_text') }}</textarea>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="question_type" class="text-sm font-medium text-slate-700">
                                        Type de question
                                    </label>
                                    <select id="question_type" name="question_type"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="multiple_choice"
                                            @selected(old('question_type', 'multiple_choice') === 'multiple_choice')>
                                            Choix multiple
                                        </option>
                                        <option value="true_false" @selected(old('question_type') === 'true_false')>
                                            Vrai / Faux
                                        </option>
                                    </select>
                                    <p class="text-xs text-slate-500">
                                        Les questions Vrai / Faux generent automatiquement deux reponses verrouillees.
                                    </p>
                                </div>
                                <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                                    <p class="font-semibold">Astuce</p>
                                    <p class="mt-1">
                                        Variez les types de questions pour couvrir la theorie et la pratique. Les reponses
                                        peuvent toutes etre correctes ou non.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Reponses proposees</p>
                                        <p class="text-xs text-slate-500">Minimum deux reponses, marquez au moins une reponse correcte.</p>
                                    </div>
                                    <button type="button" id="add-choice"
                                        class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                        Ajouter une reponse
                                    </button>
                                </div>

                                <div id="choices-list" class="space-y-3"></div>
                            </div>

                            <input type="hidden" name="choices" id="choices-field">

                            <div class="flex flex-col gap-3 border-t border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm text-slate-500">
                                    Utilisez l apercu pour relire votre question avant de l enregistrer.
                                </p>
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                                        Annuler
                                    </a>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                                        Enregistrer la question
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <aside class="space-y-5">
                    <article class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Quiz</p>
                        <h3 class="mt-2 text-base font-semibold text-slate-900">{{ $quiz->title }}</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $quiz->description ?? 'Aucun descriptif pour le moment. Ajoutez un court texte pour aider vos apprenants.' }}
                        </p>
                        <dl class="mt-4 grid gap-4 text-sm">
                            <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Score de reussite</dt>
                                <dd class="text-slate-900 font-semibold">{{ $quiz->passing_score ?? 70 }} %</dd>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Prerequis</dt>
                                <dd class="text-slate-900 font-semibold">Quiz d entree</dd>
                            </div>
                        </dl>
                    </article>

                    <article class="rounded-2xl border border-indigo-100 bg-white px-5 py-5 shadow-sm">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Apercu en direct</p>
                            <span
                                class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-semibold text-indigo-600">Vue
                                eleve</span>
                        </div>
                        <div class="mt-4 space-y-4">
                            <h4 id="preview-question-text" class="text-base font-semibold text-slate-900">
                                Votre question apparaitra ici.
                            </h4>
                            <ul id="preview-choices" class="space-y-2"></ul>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5 shadow-inner">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Bonnes pratiques</p>
                        <ul class="mt-3 space-y-2 text-sm text-slate-600">
                            <li class="flex gap-2">
                                <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-indigo-400"></span>
                                Commencez par une phrase simple et precise.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-indigo-400"></span>
                                Equilibrez les reponses correctes pour eviter les biais.
                            </li>
                            <li class="flex gap-2">
                                <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-indigo-400"></span>
                                Indiquez des reponses incorrectes plausibles pour tester la comprehension.
                            </li>
                        </ul>
                    </article>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const initialChoices = @json($initialChoices);
        const form = document.getElementById('question-form');
        const typeSelect = document.getElementById('question_type');
        const addChoiceBtn = document.getElementById('add-choice');
        const choicesContainer = document.getElementById('choices-list');
        const choicesField = document.getElementById('choices-field');
        const questionInput = document.getElementById('question_text');
        const previewQuestion = document.getElementById('preview-question-text');
        const previewChoices = document.getElementById('preview-choices');

        const TRUE_FALSE_PRESET = [
            { text: 'Vrai', is_correct: true },
            { text: 'Faux', is_correct: false },
        ];

        function createChoiceRow(choice, index) {
            const row = document.createElement('div');
            row.className =
                'choice-row flex flex-col gap-3 rounded-xl border border-slate-200 px-4 py-4 sm:flex-row sm:items-center';
            row.innerHTML = `
                <div class="flex-1 space-y-1">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Reponse ${index + 1}</label>
                    <input type="text" class="choice-text w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" value="${choice.text ?? ''}">
                </div>
                <div class="flex items-center justify-between gap-3 sm:w-auto">
                    <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                        <input type="checkbox" class="choice-correct h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" ${choice.is_correct ? 'checked' : ''}>
                        Correct
                    </label>
                    <button type="button" class="remove-choice text-xs font-semibold text-rose-600 transition hover:text-rose-700">
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
            refreshPreview();
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
                const isLocked = rows.length <= 2;
                button.disabled = isLocked;
                button.classList.toggle('opacity-50', isLocked);
                button.classList.toggle('cursor-not-allowed', isLocked);
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
                refreshPreview();
            }
        });

        choicesContainer.addEventListener('input', (event) => {
            if (event.target.classList.contains('choice-text') || event.target.classList.contains('choice-correct')) {
                refreshPreview();
            }
        });

        questionInput.addEventListener('input', refreshPreview);
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
                alert('Veuillez selectionner au moins une reponse correcte.');
                return;
            }

            choicesField.value = JSON.stringify(choices);
        });

        function refreshPreview() {
            const text = questionInput.value.trim();
            previewQuestion.textContent = text || 'Votre question apparaitra ici.';

            const choices = getCurrentChoices();
            previewChoices.innerHTML = '';

            if (!choices.length) {
                const placeholder = document.createElement('li');
                placeholder.className = 'rounded-lg border border-dashed border-slate-300 px-3 py-2 text-sm text-slate-500';
                placeholder.textContent = 'Ajoutez des reponses pour visualiser le rendu.';
                previewChoices.appendChild(placeholder);
                return;
            }

            choices.forEach((choice) => {
                const item = document.createElement('li');
                item.className = `flex items-start gap-2 rounded-lg border px-3 py-2 text-sm ${
                    choice.is_correct
                        ? 'border-emerald-100 bg-emerald-50 text-emerald-700'
                        : 'border-slate-200 bg-white text-slate-600'
                }`;

                const bullet = document.createElement('span');
                bullet.className = `mt-1 inline-flex h-2 w-2 rounded-full ${
                    choice.is_correct ? 'bg-emerald-500' : 'bg-slate-300'
                }`;

                const textSpan = document.createElement('span');
                textSpan.className = 'flex-1';
                textSpan.textContent = choice.text || 'Reponse sans texte';

                item.appendChild(bullet);
                item.appendChild(textSpan);
                previewChoices.appendChild(item);
            });
        }

        renderChoices(initialChoices);
        syncWithType(true);
        refreshPreview();
    });
</script>
