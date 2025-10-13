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
                'title' => 'Inviter des utilisateurs',
                'description' => 'Voir les invitations et permissions', 
                'route' => 'application.admin.users.manager'
            ],
            [
                'title' => 'Liste de vos utilisateurs *',
                'description' => '..', 
                'route' => 'application.admin.configuration.index'
            ],
     
            
        ]" 
        :team="$team" 
        backTitle="Retour à l'Administration"
        back="{{ route('application.admin.users.index', $team) }}" />
    </x-block-div>
</x-application-layout>