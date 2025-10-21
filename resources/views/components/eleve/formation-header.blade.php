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
        </div>
    </div>
</div>
