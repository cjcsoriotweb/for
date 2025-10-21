@props(['formation', 'progress'])

<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $formation->title }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ $formation->description }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Progression
                </div>
                <div
                    class="text-2xl font-bold text-blue-600 dark:text-blue-400"
                >
                    {{ $progress["percentage"] ?? 0 }}%
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div
            class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4"
        >
            <div
                class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                style="width: {{ $progress['percentage'] ?? 0 }}%"
            ></div>
        </div>

        <!-- Formation Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Chapitres
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $progress["total_chapters"] ?? 0 }}
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Leçons complétées
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $progress["completed_lessons"] ?? 0 }}
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div
                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                >
                    Quiz réussis
                </div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $progress["passed_quizzes"] ?? 0 }}
                </div>
            </div>
        </div>
    </div>
</div>
