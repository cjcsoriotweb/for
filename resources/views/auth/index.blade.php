<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selectionnez votre organisme') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @include('auth.vous.application-list')
    </div>
</x-app-layout>