<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Accueil') }}
            @livewire('notifications-bell')
        </h2>
        
    </x-slot>

    <div class="py-12">
    
<a href="{{ route('superadmin.home') }}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('Superadmin') }}</a>

    </div>

    <div class="py-12">
        @include('auth.vous.application-list')
    </div>

</x-app-layout>