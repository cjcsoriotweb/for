<!-- Formations en Cours -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
                Continuer mes formations
            </h2>
            <a href="#" class="text-primary hover:text-blue-600 font-medium">
                Voir tout →
            </a>
        </div>

        @php $studentFormationService =
        app(\App\Services\Formation\StudentFormationService::class); @endphp
        @if($currentFormation->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($currentFormation as $formation) @php $progress =
            $studentFormationService->getStudentProgress(auth()->user(),
            $formation); $progressWidth = $progress['progress_percent'] ?? 0;
            @endphp

            <div
                class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow"
            >
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-blue-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full"
                    >
                        En cours
                    </span>
                </div>

                <h3 class="font-semibold text-lg mb-2">
                    {{ $formation->title }}
                </h3>
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                    {{ $formation->description ?? 'Formation en cours' }}
                </p>

                @if($progress)
                <div class="mb-4">
                    <div
                        class="flex justify-between text-sm text-gray-600 mb-1"
                    >
                        <span>Progression</span>
                        <span>{{ $progress["progress_percent"] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-primary h-2 rounded-full transition-all duration-300"
                            style="width: {{ $progressWidth }}%"
                        ></div>
                    </div>
                </div>

                @if($progress['last_seen_at'])
                <p class="text-xs text-gray-500 mb-3">
                    Dernière activité:
                    {{ $progress['last_seen_at']->diffForHumans() }}
                </p>
                @endif @else
                <div class="mb-4">
                    <div
                        class="flex justify-between text-sm text-gray-600 mb-1"
                    >
                        <span>Progression</span>
                        <span>0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                            class="bg-primary h-2 rounded-full"
                            style="width: 0%"
                        ></div>
                    </div>
                </div>
                @endif

                <a
                    href="{{ route('eleve.formation.show', [$team, $formation->id]) }}"
                    class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors text-center block"
                >
                    Continuer
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div
                class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
            >
                <svg
                    class="w-12 h-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                    ></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                Aucune formation en cours
            </h3>
            <p class="text-gray-500 mb-6">
                Inscrivez-vous à une formation pour commencer votre
                apprentissage.
            </p>
            <a
                href="{{ route('eleve.index', $team) }}"
                class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors"
            >
                Découvrir les formations
            </a>
        </div>
        @endif
    </div>
</section>
