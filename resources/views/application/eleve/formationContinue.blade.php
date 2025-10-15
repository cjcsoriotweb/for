<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $formation->title }}
        </h2>
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
        </div>
    </x-slot>

    <div class="py-12 space-y-6">
        <p>{{ $formation->description }}</p>
    </div>

</x-application-layout>