<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accueil') }}
        </h2>
    </x-slot>

    @include('application.admin.navbar', ['team' => $team])

   
    <x-slot name="block">
        Bienvenue sur le panneau d'administration de votre équipe. Ici, vous pouvez gérer les membres, les projets, les paramètres et bien plus encore.
    </x-slot>

</x-application-layout>


