<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <x-block-div>
        
        <div>
            <p>Nombre de formations activé : {{ $formationsByTeam->count() }} / {{ $formationsAll->count() }}</p>
        </div>
        @foreach($formationsAll as $formation)
            <div class="{{ $formation->is_attached ? 'bg-green-100' : 'bg-red-100' }} p-8 dark:bg-slate-800/50 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-xl mb-2">{{ $formation->title }}</h3>
                    <div class="flex items-center space-x-2">
                        <a href="{{ url('application.admin.formations.edit', [$team, $formation]) }}" class="text-blue-500 hover:underline">Editer</a>
                        <a href="{{ url('application.admin.formations.show', [$team, $formation]) }}" class="text-blue-500 hover:underline">Voir</a>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </x-block-div>

    <x-block-div>
        <x-block-navigation 
        :navigation="[
           
        ]" 
        :team="$team" 
        backTitle="Retour aux formations"
        back="{{ route('application.admin.formations.index', $team) }}" />
    </x-block-div>
</x-application-layout>
