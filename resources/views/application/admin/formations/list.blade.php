<x-application-layout :team="$team">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-white">
            {{ __('Gerer les formations de') . ' ' . $team->name }}
        </h2>
    </x-slot>

    <x-block-div>

        <div class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 inset-ring inset-ring-gray-500/10">
            <p>Nombre de formations activé : {{ $formationsByTeam->count() }} / {{ $formationsAll->count() }}</p>
        </div>
        @foreach ($formationsAll as $formation)
            <div
                class="{{ $formation->is_attached ? 'bg-green-100' : 'bg-red-100' }} p-8 dark:bg-slate-800/50 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-xl mb-2">{{ $formation->title }}</h3>
                        <h2>{{ $formation->description }}</h2>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if ($formation->is_attached)
                            <form  action="{{ route('application.admin.formations.disable', [$team, $formation]) }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Desactiver</button>
                            </form>

                        @else
                            <form action="{{ route('application.admin.formations.enable', [$team, $formation]) }}" method="POST">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                                <button type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Activer</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </x-block-div>

    <x-block-div>
        <x-block-navigation :navigation="[]" :team="$team" backTitle="Retour aux formations"
            back="{{ route('application.admin.formations.index', $team) }}" />
    </x-block-div>
</x-application-layout>
