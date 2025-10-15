<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
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
                ],
                [
                    'title' => 'Changer logo',
                    'description' => '',
                    'route' => 'application.admin.configuration.logo',
                    'image' => $team->profile_photo_path ? asset('storage/'.$team->profile_photo_path) : null,
                ],
            ]"
            :team="$team"
            backTitle="Retour à Administration"
            back="{{ route('application.admin.index', $team) }}"
        />
        
    </x-block-div>



</x-application-layout>