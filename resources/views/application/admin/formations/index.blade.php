<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>

    </x-block-div>

    <x-block-div>
        <x-block-navigation 
        :navigation="[
            [
                'title' => 'Gerer les formations',
                'description' => __('Ajouter ou desactiver une formation.'), 
                'route' => 'application.admin.formations.list'
            ],
     
            
        ]" 
        :team="$team" 
        backTitle="Retour à l'Administration"
        back="{{ route('application.admin.index', $team) }}" />
    </x-block-div>
</x-application-layout>