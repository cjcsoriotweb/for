
<x-team-layout :team="$team">
    <x-slot name="header">
            <x-admin-navigation :team="$team"  />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-admin-money-team :team="$team" />

            @livewire('teamNeedFormationList', ['team' => $team, 'display'=>'admin'])
            
        </div>
        
    </div>
</x-team-layout>
