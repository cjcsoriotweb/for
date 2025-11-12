<x-app-layout>
    <div class="min-h-screen">
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @php
                    $formation->loadMissing([
                        'chapters.lessons.lessonable' => function ($morphTo) {
                            $morphTo->morphWith([
                                \App\Models\VideoContent::class => [],
                                \App\Models\TextContent::class => [],
                                \App\Models\Quiz::class => ['quizQuestions'],
                            ]);
                        },
                        'entryQuiz.quizQuestions',
                        'teams:id,name',
                        'completionDocuments',
                    ]);

                    $chapters = $formation->chapters;
                    $lessonCount = $chapters->sum(fn($chapter) => $chapter->lessons->count());
                    $teams = $formation->teams;
                    $entryQuiz = $formation->entryQuiz;
                    $documentsCount = $formation->completionDocuments->count();

                    $lessons = $chapters->flatMap(fn($chapter) => $chapter->lessons);
                    $totalDurationMinutes = $lessons->sum(function ($lesson) {
                        if (!$lesson->lessonable) {
                            return 0;
                        }

                        return match ($lesson->lessonable_type) {
                            \App\Models\VideoContent::class => (int) ($lesson->lessonable->duration_minutes ?? 0),
                            \App\Models\TextContent::class => (int) ($lesson->lessonable->estimated_read_time ?? 0),
                            \App\Models\Quiz::class => (function () use ($lesson) {
                                $estimated = (int) ($lesson->lessonable->estimated_duration_minutes ?? 0);
                                if ($estimated > 0) {
                                    return $estimated;
                                }

                                $questionCount =
                                    $lesson->lessonable->quizQuestions?->count() ??
                                    $lesson->lessonable->quizQuestions()->count();

                                return $questionCount > 0 ? max($questionCount * 2, 5) : 0;
                            })(),
                            default => 0,
                        };
                    });

                    $durationHours = intdiv($totalDurationMinutes, 60);
                    $durationMinutesRemainder = $totalDurationMinutes % 60;
                    $formattedEstimatedDuration =
                        $totalDurationMinutes > 0
                            ? implode(
                                ' ',
                                array_filter([
                                    $durationHours > 0 ? $durationHours . ' h' : null,
                                    $durationMinutesRemainder > 0 ? $durationMinutesRemainder . ' min' : null,
                                ]),
                            )
                            : null;
                @endphp

                <!-- Grid Layout: Left column for formation info, right column for content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Formation Information -->
                    <div class="lg:col-span-1">
                        <!-- Formation Info Card -->
                        <div
                            class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6 relative overflow-hidden">
                            <!-- Formation Cover Image - Top Right Corner -->
                            @if ($formation->cover_image_url)
                                <div
                                    class="absolute top-4 right-4 w-16 h-16 rounded-lg overflow-hidden border-2 border-white shadow-md group">
                                    <img src="{{ $formation->cover_image_url }}"
                                        alt="Image de couverture de {{ $formation->title }}"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-150 group-hover:rotate-1" />
                                    <!-- Overlay for hover effect -->
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300">
                                    </div>
                                </div>
                            @endif

                            <div class="space-y-3">
                                <a href="{{ route('formateur.home') }}"
                                    class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Retour aux formations
                                </a>

                                <div class="space-y-2">
                                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">
                                        {{ $formation->title }}
                                    </h1>

                                    <p class="text-sm leading-relaxed text-slate-600">
                                        {{ $formation->description ?: 'Aucune description disponible pour le moment.' }}
                                    </p>
                                </div>

                                <div
                                    class="p-4 mt-4 bg-slate-50 rounded-2xl border border-slate-200 flex items-center gap-4">
                                    <div class="p-3 rounded-2xl bg-indigo-100 text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 1M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-wide text-slate-500">
                                            Temps estimé de la formation
                                        </p>
                                        @if ($totalDurationMinutes > 0)
                                            <p class="text-xl font-semibold text-slate-900">
                                                {{ $formattedEstimatedDuration }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ $totalDurationMinutes }} min cumulés
                                            </p>
                                        @else
                                            <p class="text-base font-medium text-slate-600">
                                                À définir
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Ajoutez un temps estimé à chaque leçon pour afficher la durée totale.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-1">
                                    <a href="{{ route('formateur.formation.preview', $formation) }}" target="_blank"
                                        rel="noopener"
                                        class="mb-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-emerald-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-5.197-2.6A1 1 0 008 9.47v5.06a1 1 0 001.555.832l5.197-2.6a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        A quoi ressemble la formation?
                                    </a>
                                    <a href="{{ route('formateur.formation.edit.title', $formation) }}"
                                        class="mb-5 inline-block w-full items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">

                                        Modifier le titre
                                    </a>
                                    <a href="{{ route('formateur.formation.edit.description', $formation) }}"
                                        class="mb-5 inline-block w-full items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">

                                        Modifier la description
                                    </a>
                                    <a href="{{ route('formateur.formation.edit.cover', $formation) }}"
                                        class="mb-5 inline-block w-full items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">

                                        Modifier le logo
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <section class="bg-white rounded-3xl shadow-lg border border-slate-200/60 overflow-hidden">
                            <div
                                class="bg-gradient-to-r from-slate-50 to-indigo-50 px-8 py-6 border-b border-slate-200/60">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 rounded-xl bg-indigo-100 text-indigo-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-900">Actions principales</h2>
                                        <p class="text-slate-600 mt-1">
                                            Accès rapide aux paramètres essentiels de la formation
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8">
                                <div class="grid gap-5">
                                    <!-- Parcours -->
                                    <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                                        class="group relative overflow-hidden  border border-slate-200 bg-gradient-to-br from-green-50 to-emerald-100 p-8 transition-all duration-300 hover:shadow-xl hover:shadow-green-500/10 hover:-translate-y-1 block">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-green-400/5 to-emerald-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="relative">
                                            <div class="flex items-center justify-between mb-6">
                                                <div class="p-4 rounded-xl bg-green-500 text-white shadow-lg">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </div>
                                                <svg class="h-6 w-6 text-green-400 group-hover:translate-x-1 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-900 mb-3">Gestion parcours</h3>
                                            <p class="text-base text-slate-600 leading-relaxed">
                                                Gestion du chapitre, leçons...
                                            </p>
                                        </div>
                                    </a>


                                    <!-- Gestion des équipes -->
                                    <a href="{{ route('formateur.formation.teams.index', $formation) }}"
                                        class="group relative overflow-hidden  border border-slate-200 bg-gradient-to-br from-green-50 to-emerald-100 p-8 transition-all duration-300 hover:shadow-xl hover:shadow-green-500/10 hover:-translate-y-1 block">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-teal-400/5 to-teal-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="relative">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="p-3 rounded-xl bg-teal-500 text-white shadow-lg">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                </div>
                                                <svg class="h-5 w-5 text-teal-400 group-hover:translate-x-1 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">Équipes rattachées</h3>
                                            <p class="text-sm text-slate-600 leading-relaxed">
                                                Gérez l'accès des équipes à cette formation
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </section>


                    </div>
                </div>

                <!-- Full-width Resources section -->
                <div class="mt-8">
                    <!-- Ressources et extensions -->
                    <section class="bg-white rounded-3xl shadow-lg border border-slate-200/60 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-rose-50 px-8 py-6 border-b border-slate-200/60">
                            <div class="flex items-center gap-4">
                                <div class="p-3 rounded-xl bg-rose-100 text-rose-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-900">Ressources et extensions</h2>
                                    <p class="text-slate-600 mt-1">
                                        Complétez l'expérience apprenante avec des outils complémentaires
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <!-- Documents de fin de formation -->
                                <a href="{{ route('formateur.formation.completion-documents.index', $formation) }}"
                                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-orange-50 to-orange-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/10 hover:-translate-y-1">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-orange-400/5 to-orange-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="p-3 rounded-xl bg-orange-500 text-white shadow-lg">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <svg class="h-5 w-5 text-orange-400 group-hover:translate-x-1 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-2">Documents de fin</h3>
                                        <p class="text-sm text-slate-600 leading-relaxed">
                                            Gérez attestations et contenus téléchargeables pour vos apprenants
                                        </p>
                                    </div>
                                </a>



                                <!-- Quiz d'entrée -->
                                <a href="{{ route('formateur.formation.entry-quiz.questions', $formation) }}"
                                    class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-amber-50 to-amber-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-amber-400/5 to-amber-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                    <div class="relative">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="p-3 rounded-xl bg-amber-500 text-white shadow-lg">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <svg class="h-5 w-5 text-amber-400 group-hover:translate-x-1 transition-transform duration-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-2">Quiz d'entrée</h3>
                                        <p class="text-sm text-slate-600 leading-relaxed">
                                            Évaluez les prérequis avant l'accès à la formation
                                        </p>
                                    </div>
                                </a>


                            </div>
                        </div>

                    </section>


                </div>

