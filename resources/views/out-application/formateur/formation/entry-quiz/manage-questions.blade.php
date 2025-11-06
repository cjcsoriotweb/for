<x-app-layout>
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 shadow">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-blue-100">{{ $formation->title }}</p>
                    <h1 class="text-2xl font-semibold text-white">Gestion des questions du quiz d entree</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('formateur.formation.entry-quiz.edit', $formation) }}"
                        class="inline-flex items-center rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/20">
                        Configuration du quiz
                    </a>
                    <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                        class="inline-flex items-center rounded-lg border border-white/40 bg-white/10 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/20">
                        Retour aux chapitres
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="px-6 py-5">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">{{ $quiz->title }}</h2>
                            <p class="text-sm text-slate-500">
                                @if ($quiz->description)
                                    {{ $quiz->description }}
                                @else
                                    Aucun descriptif pour le moment.
                                @endif
                            </p>
                        </div>
                        <dl class="grid grid-cols-2 gap-4 text-sm">
                            <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3">
                                <dt class="text-indigo-600 font-medium">Fourchette cible</dt>
                                <dd class="text-slate-900 font-semibold">
                                    {{ $quiz->entry_min_score ?? 0 }} % &ndash; {{ $quiz->entry_max_score ?? ($quiz->passing_score ?? 100) }} %
                                </dd>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <dt class="text-slate-500">Questions</dt>
                                <dd class="text-slate-900 font-semibold">{{ $questions->count() }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Liste des questions</h3>
                        <p class="text-sm text-slate-500">Consultez, modifiez ou supprimez chaque question.</p>
                    </div>
                    <a href="{{ route('formateur.formation.entry-quiz.questions.create', $formation) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Ajouter une question
                    </a>
                </div>

                @forelse ($questions as $question)
                    <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="space-y-2">
                                <div class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                                    {{ $question->type === 'true_false' ? 'Vrai / Faux' : 'Choix multiple' }}
                                </div>
                                <h4 class="text-base font-semibold text-slate-900">{{ $question->question }}</h4>
                                <ul class="space-y-2 text-sm text-slate-600">
                                    @foreach ($question->quizChoices as $choice)
                                        <li class="flex items-start gap-2">
                                            <span class="mt-1 inline-flex h-2 w-2 rounded-full {{ $choice->is_correct ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                            <span class="{{ $choice->is_correct ? 'font-semibold text-emerald-600' : '' }}">
                                                {{ $choice->choice_text }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('formateur.formation.entry-quiz.questions.edit', [$formation, $question]) }}"
                                    class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:text-slate-900">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('formateur.formation.entry-quiz.questions.delete', [$formation, $question]) }}"
                                    onsubmit="return confirm('Supprimer cette question ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center rounded-lg border border-rose-200 px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-10 text-center text-sm text-slate-500">
                        Aucune question enregistree pour l'instant. Commencez en ajoutant votre premiere question.
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
