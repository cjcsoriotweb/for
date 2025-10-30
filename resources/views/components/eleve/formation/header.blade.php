@props(['formation', 'progress'])

<div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6"
>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200/60 dark:border-gray-700/60">
            <img
                src="{{ $formation->cover_image_url }}"
                alt="Image de couverture de {{ $formation->title }}"
                class="h-56 w-full object-cover sm:h-64 lg:h-72"
                loading="lazy"
                onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
            />
        </div>
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
