@props(['formation', 'progress'])

<div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-gray-200/50 dark:border-gray-700/50" data-progress>
    <!-- Image de couverture -->
    <div class="relative overflow-hidden rounded-t-2xl">
        <img
            src="{{ $formation->cover_image_url }}"
            alt="Image de couverture de {{ $formation->title }}"
            class="h-48 w-full object-cover sm:h-56 lg:h-64"
            loading="lazy"
            onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
    </div>

    <div class="p-8">
        <!-- Titre et description -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                {{ is_string($formation->title) ? $formation->title : 'Formation' }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ is_string($formation->description) ? $formation->description : '' }}
            </p>
        </div>

        <!-- Barre de progression globale -->
        @php
            $progressPercent = is_array($progress) ? ($progress['progress_percent'] ?? 0) : (is_numeric($progress) ? $progress : 0);
        @endphp
        @if($progressPercent > 0)
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Progression globale
                </span>
                <span class="text-sm font-bold text-gray-900 dark:text-white">
                    {{ round($progressPercent) }}%
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                <div
                    class="h-full bg-gradient-to-r from-blue-500 to-purple-600 rounded-full transition-all duration-500 ease-out"
                    style="width: {{ $progressPercent }}%"
                ></div>
            </div>
        </div>
        @endif

        <!-- Statistiques rapides -->
        @php
            $chaptersCount = $formation->chapters ? $formation->chapters->count() : 0;
            $lessonsCount = $formation->chapters ? $formation->chapters->sum(fn($chapter) => $chapter->lessons ? $chapter->lessons->count() : 0) : 0;
            $completedCount = $formation->chapters ? $formation->chapters->where('is_completed', true)->count() : 0;
            $currentCount = $formation->chapters ? $formation->chapters->where('is_current', true)->count() : 0;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $chaptersCount }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Chapitre{{ $chaptersCount > 1 ? 's' : '' }}
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ $lessonsCount }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Leçon{{ $lessonsCount > 1 ? 's' : '' }}
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $completedCount }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Terminé{{ $completedCount > 1 ? 's' : '' }}
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                    {{ $currentCount }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    En cours
                </div>
            </div>
        </div>
    </div>
</div>
