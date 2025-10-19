<div>
    <!-- Search Section -->
    <div class="mb-8">
        <div class="relative max-w-md">
            <div
                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"
            >
                <svg
                    class="h-5 w-5 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                    ></path>
                </svg>
            </div>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Rechercher une formation..."
                class="block w-full pl-12 pr-10 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-500 shadow-sm hover:shadow-md focus:shadow-lg"
            />
            @if($search)
            <button
                wire:click="clearSearch"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors duration-200"
            >
                <svg
                    class="h-5 w-5"
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
            @endif
        </div>

        @if($search)
        <div class="mt-3 flex items-center justify-between text-sm">
            <span class="text-gray-600">
                Recherche: "<span class="font-medium text-gray-900">{{
                    $search
                }}</span
                >"
            </span>
            <span class="text-gray-500">
                {{ $formations->count() }}
                résultat{{ $formations->count() > 1 ? 's' : '' }}
            </span>
        </div>
        @endif
    </div>

    <!-- Formations List -->
    <div class="space-y-6">
        @foreach ($formations as $formation)
        <div
            class="group relative bg-gradient-to-br from-white via-blue-50 to-indigo-50 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-blue-100/50 hover:border-blue-200"
        >
            <!-- Decorative gradient overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-transparent to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
            ></div>

            <div class="relative p-8">
                <!-- Header with status indicator -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3
                            class="font-bold text-2xl text-gray-900 leading-tight mb-2 group-hover:text-blue-900 transition-colors duration-300"
                        >
                            {{ $formation->title }}
                        </h3>
                        <div
                            class="flex items-center space-x-2 text-sm text-gray-500"
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
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <span>Créée récemment</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div
                            class="w-3 h-3 bg-green-400 rounded-full animate-pulse"
                        ></div>
                    </div>
                </div>

                <!-- Description -->
                <p
                    class="text-gray-700 text-lg leading-relaxed mb-6 line-clamp-2"
                >
                    {{ Str::limit($formation->description, 150) }}
                </p>

                <!-- Action button -->
                <div class="flex items-center justify-between">
                    <a
                        href="{{
                            route('formateur.formation.edit', $formation)
                        }}"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl group/btn"
                    >
                        <span>Voir les détails</span>
                        <svg
                            class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform duration-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"
                            ></path>
                        </svg>
                    </a>

                    <!-- Stats or additional info -->
                    <div
                        class="flex items-center space-x-4 text-sm text-gray-500"
                    >
                        <div class="flex items-center space-x-1">
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
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                ></path>
                            </svg>
                            <span
                                >{{ $formation->users_count ?? 0 }}
                                étudiants</span
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hover effect border -->
            <div
                class="absolute inset-0 rounded-2xl ring-2 ring-blue-500/0 group-hover:ring-blue-500/20 transition-all duration-300 pointer-events-none"
            ></div>
        </div>
        @endforeach @if($formations->isEmpty())
        <div class="text-center py-16">
            <div
                class="mx-auto w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mb-6"
            >
                <svg
                    class="w-12 h-12 text-blue-600"
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
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                Aucune formation trouvée
            </h3>
            <p class="text-gray-600">
                Vous n'avez pas encore créé de formations.
            </p>
        </div>
        @endif
    </div>
</div>
