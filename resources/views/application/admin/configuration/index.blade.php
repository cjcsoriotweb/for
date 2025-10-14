<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>

        <x-block-navigation 
            :navigation="[
                [
                    'title' => 'Changer nom',
                    'description' => 'Remplacer le nom ' . e($team->name) . '',
                    'route' => 'application.admin.configuration.name',
                    'hasTeamRole' => 'admin',
                ],
                [
                    'title' => 'Changer logo',
                    'description' => '',
                    'route' => 'application.admin.configuration.logo',
                    'image' => $team->profile_photo_path ? asset('storage/'.$team->profile_photo_path) : null,
                    'hasTeamRole' => 'admin',
                ],
            ]"
            :team="$team"
            backTitle="Retour à Administration"
            back="{{ route('application.admin.index', $team) }}"
        />
        
    </x-block-div>



</x-application-layout>