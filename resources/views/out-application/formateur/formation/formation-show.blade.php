<x-app-layout>
    <div class="min-h-screen">
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @php
                    $formation->loadMissing([
                        'chapters.lessons',
                        'entryQuiz.quizQuestions',
                        'teams:id,name',
                        'completionDocuments',
                    ]);

                    $chapters = $formation->chapters;
                    $lessonCount = $chapters->sum(fn($chapter) => $chapter->lessons->count());
                    $teams = $formation->teams;
                    $entryQuiz = $formation->entryQuiz;
                    $documentsCount = $formation->completionDocuments->count();
                @endphp

                <!-- Grid Layout: Left column for formation info, right column for content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Formation Information -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Formation Info Card -->
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/60 p-6 relative overflow-hidden">
                            <!-- Formation Cover Image - Top Right Corner -->
                            @if($formation->cover_image_url)
                                <div class="absolute top-4 right-4 w-16 h-16 rounded-lg overflow-hidden border-2 border-white shadow-md group">
                                    <img src="{{ $formation->cover_image_url }}"
                                         alt="Image de couverture de {{ $formation->title }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-150 group-hover:rotate-1" />
                                    <!-- Overlay for hover effect -->
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                                </div>
                            @endif

                            <div class="space-y-4">
                                <a href="{{ route('formateur.home') }}"
                                    class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Retour aux formations
                                </a>

                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-medium text-slate-700">
                                            <div
                                                class="mr-2 h-1.5 w-1.5 rounded-full {{ $formation->active ? 'bg-green-500' : 'bg-red-500' }}">
                                            </div>
                                            {{ $formation->active ? 'Active' : 'Désactivée' }}
                                        </span>
                                    </div>

                                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                                        {{ $formation->title }}
                                    </h1>

                                    <p class="text-sm sm:text-base leading-relaxed text-slate-600">
                                        {{ $formation->description ?: 'Aucune description disponible pour le moment.' }}
                                    </p>
                                </div>

                                <div class="pt-2">
                                    <a href="{{ route('formateur.formation.edit', $formation) }}"
                                        class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        Modifier
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Parcours -->
                                    <a href="{{ route('formateur.formation.chapters.index', $formation) }}"
                                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-purple-50 to-purple-100 p-8 transition-all duration-300 hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 block">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-purple-400/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="relative">
                                            <div class="flex items-center justify-between mb-6">
                                                <div class="p-4 rounded-xl bg-purple-500 text-white shadow-lg">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <svg class="h-6 w-6 text-purple-400 group-hover:translate-x-1 transition-transform duration-300"
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

                                    <!-- Paramètres -->
                                    <a href="{{ route('formateur.formation.edit', $formation) }}"
                                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-blue-50 to-blue-100 p-8 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 block">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="relative">
                                            <div class="flex items-center justify-between mb-6">
                                                <div class="p-4 rounded-xl bg-blue-500 text-white shadow-lg">
                                                    <svg class="h-8 w-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <svg class="h-6 w-6 text-blue-400 group-hover:translate-x-1 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-900 mb-3">Paramètres</h3>
                                            <p class="text-base text-slate-600 leading-relaxed">
                                                Configurez les options générales de votre formation
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </section>

                        <!-- Ressources et extensions -->
                        <section class="bg-white rounded-3xl shadow-lg border border-slate-200/60 overflow-hidden">
                            <div
                                class="bg-gradient-to-r from-slate-50 to-rose-50 px-8 py-6 border-b border-slate-200/60">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 rounded-xl bg-rose-100 text-rose-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                <div class="grid gap-4 md:grid-cols-2">
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">Documents de fin</h3>
                                            <p class="text-sm text-slate-600 leading-relaxed">
                                                Gérez attestations et contenus téléchargeables pour vos apprenants
                                            </p>
                                        </div>
                                    </a>

                                    <!-- Catégorie IA -->
                                    <a href="{{ route('formateur.formation.ai.edit', $formation) }}"
                                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-cyan-50 to-cyan-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-cyan-500/10 hover:-translate-y-1">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-cyan-400/5 to-cyan-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="relative">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="p-3 rounded-xl bg-cyan-500 text-white shadow-lg">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                    </svg>
                                                </div>
                                                <svg class="h-5 w-5 text-cyan-400 group-hover:translate-x-1 transition-transform duration-300"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">Intelligence Artificielle
                                            </h3>
                                            <p class="text-sm text-slate-600 leading-relaxed">
                                                Configurez l'assistant IA pour accompagner vos apprenants
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">Quiz d'entrée</h3>
                                            <p class="text-sm text-slate-600 leading-relaxed">
                                                Évaluez les prérequis avant l'accès à la formation
                                            </p>
                                        </div>
                                    </a>

                                    <!-- Gestion des équipes -->
                                    <a href="{{ route('formateur.formation.teams.index', $formation) }}"
                                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-teal-50 to-teal-100 p-6 transition-all duration-300 hover:shadow-xl hover:shadow-teal-500/10 hover:-translate-y-1">
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

                        <!-- Action buttons -->
                        <div class="bg-white rounded-3xl shadow-lg border border-slate-200/60 p-8">
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="{{ route('formateur.formation.export', ['formation' => $formation, 'format' => 'zip']) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-4 text-sm font-semibold text-white transition-all hover:from-emerald-600 hover:to-emerald-700 hover:shadow-lg hover:shadow-emerald-500/25 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Exporter (ZIP)
                                </a>

                                @if (Auth::user()->superadmin)
                                    <a href="{{ route('formateur.formation.delete.show', $formation) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-red-500 to-red-600 px-8 py-4 text-sm font-semibold text-white transition-all hover:from-red-600 hover:to-red-700 hover:shadow-lg hover:shadow-red-500/25 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Supprimer
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


</x-app-layout>
