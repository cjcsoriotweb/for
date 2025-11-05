@php
    $studentName = $student->name ?? $student->email;
    $statusLabels = [
        'completed' => ['label' => 'Terminée', 'badge' => 'bg-green-100 text-green-800', 'dot' => 'bg-green-500'],
        'in_progress' => ['label' => 'En cours', 'badge' => 'bg-blue-100 text-blue-800', 'dot' => 'bg-blue-500'],
        'enrolled' => ['label' => 'Non commencée', 'badge' => 'bg-gray-100 text-gray-800', 'dot' => 'bg-gray-400'],
        'not_started' => ['label' => 'Non commencée', 'badge' => 'bg-gray-100 text-gray-800', 'dot' => 'bg-gray-400'],
    ];

    $enrollmentStatus = $statusLabels[$enrollment->status] ?? $statusLabels['enrolled'];
@endphp

<x-app-layout :team="$team">
    <x-slot name="header">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-600 dark:text-blue-100">
                    Gestion des élèves
                </p>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $formation->title }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-blue-100/80">
                    Progression de {{ $studentName }}
                </p>
            </div>
            <div class="flex gap-3">
                <a
                    href="{{ route('application.admin.formations.revenue', [$team, $formation]) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:border-white/20 dark:text-white dark:hover:bg-white/10"
                >
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 text-gray-900 dark:text-white">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-white">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-white/20 dark:bg-white/10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-white/60">
                        Statut de la formation
                    </p>
                    <div class="mt-3 inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-medium {{ $enrollmentStatus['badge'] }}">
                        <span class="material-symbols-outlined text-base">verified</span>
                        {{ $enrollmentStatus['label'] }}
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-white/20 dark:bg-white/10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-white/60">
                        Progression globale
                    </p>
                    <div class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $progressPercent }}%
                    </div>
                    <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-white/20">
                        <div
                            class="h-full rounded-full bg-gradient-to-r from-sky-400 to-purple-500 transition-all"
                            style="width: {{ $progressPercent }}%;"
                        ></div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-white/20 dark:bg-white/10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-white/60">
                        Étapes complétées
                    </p>
                    <div class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $completedLessons }} / {{ $totalLessons }}
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-white/70">
                        {{ $inProgressLessons }} en cours • {{ $notStartedLessons }} en attente
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-white/20 dark:bg-white/10">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-white/60">
                        Inscription
                    </p>
                    <div class="mt-3 space-y-1 text-sm text-gray-700 dark:text-white/80">
                        <div>
                            <span class="text-gray-500 dark:text-white/60">Date:</span>
                            {{ optional($enrollment->enrolled_at)->isoFormat('DD MMM YYYY HH:mm') ?? '—' }}
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-white/60">Dernière activité:</span>
                            {{ optional($enrollment->last_seen_at)->diffForHumans() ?? '—' }}
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-white/60">Utilisation factur�e:</span>
                              <span class="text-gray-900 dark:text-white">{{ __("Non applicable") }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr),320px]">
            <section class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Progression par chapitre
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-white/70">
                        Visualisez chaque étape et complétez-les si nécessaire pour l'élève.
                    </p>
                </div>

                @forelse($chapters as $chapterData)
                    @php
                        $chapter = $chapterData['chapter'];
                        $lessons = $chapterData['lessons'];
                    @endphp
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                        <div class="flex items-center justify-between gap-4 border-b border-gray-200 pb-4 dark:border-white/10">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-white/60">
                                    {{ $chapter?->title ?? 'Chapitre sans titre' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-white/50">
                                    {{ $lessons->count() }} étape(s)
                                </p>
                            </div>
                        </div>

                        <ul class="mt-4 space-y-3">
                            @foreach($lessons as $lesson)
                                @php
                                    $lessonStatus = $statusLabels[$lesson->pivot->status ?? 'enrolled'] ?? $statusLabels['enrolled'];
                                @endphp
                                <li class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-white/5">
                                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="h-2.5 w-2.5 rounded-full {{ $lessonStatus['dot'] }}"></span>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $lesson->title }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-white/60">
                                                    Statut : {{ $lessonStatus['label'] }}
                                                </p>
                                            </div>
                                        </div>

                                        @if(($lesson->pivot->status ?? 'enrolled') !== 'completed')
                                            <form
                                                method="POST"
                                                action="{{ route('application.admin.formations.students.lessons.complete', [$team, $formation, $student, $lesson]) }}"
                                                class="flex items-center justify-end"
                                                onsubmit="return confirm('Confirmer la validation de cette étape pour {{ $studentName }} ?');"
                                            >
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-white transition hover:bg-green-600"
                                                >
                                                    <span class="material-symbols-outlined text-base">task_alt</span>
                                                    Marquer comme terminée
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-xs font-semibold uppercase tracking-wide text-green-700 dark:bg-white/10 dark:text-white/80">
                                                <span class="material-symbols-outlined text-base">done</span>
                                                Terminée
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center dark:border-white/10 dark:bg-white/5">
                        <p class="text-sm text-gray-600 dark:text-white/70">
                            Aucune leçon n'est configurée pour cette formation.
                        </p>
                    </div>
                @endforelse
            </section>

            <aside class="space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-white/70">
                        Actions administrateur
                    </h4>
                    <p class="mt-2 text-sm text-gray-600 dark:text-white/60">
                        Utilisez ces actions pour assister l'élève ou gérer son inscription.
                    </p>

                    <div class="mt-4 space-y-3">
                        <form
                            method="POST"
                            action="{{ route('application.admin.formations.students.reset', [$team, $formation, $student]) }}"
                            onsubmit="return confirm('Réinitialiser toute la progression de {{ $studentName }} ?');"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-amber-500/90 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-500"
                            >
                                <span class="material-symbols-outlined text-base">restart_alt</span>
                                Réinitialiser la formation
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="{{ route('application.admin.formations.students.unenroll', [$team, $formation, $student]) }}"
                            onsubmit="return confirm('Désinscrire {{ $studentName }} et recréditer les jetons ?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
                            >
                                <span class="material-symbols-outlined text-base">person_remove</span>
                                Désinscrire et recréditer
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
