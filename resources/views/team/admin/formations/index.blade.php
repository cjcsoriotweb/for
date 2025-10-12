
<x-app-layout>
    <x-slot name="header">
            <x-admin-navigation :team="$team"  />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-formations-list-admin team-id="{{ Auth::user()->currentTeam->id }}" />
            </div>
        </div>
    </div>
</x-app-layout>
