<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vos formations') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">
        {{ $formation->name }}
    </div>
</x-app-layout>