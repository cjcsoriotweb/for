<x-application-layout :team="$team">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-xl">school</span>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">Gérer les formations</h2>
                <p class="text-blue-100 text-sm">Gérer les formations de {{ $team->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <!-- Hero section -->
        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Gestionnaire de formations</h1>
                    <p class="text-blue-100 text-lg mb-6">Activez ou désactivez les formations disponibles pour votre équipe.</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-32 h-32 bg-white bg-opacity-10 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-6xl text-white text-opacity-80">library_books</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-8">
            <div class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 inset-ring inset-ring-gray-500/10">
                <p>Nombre de formations activé : {{ $formationsByTeam->count() }} / {{ $formationsAll->count() }}</p>
            </div>
        </div>

        <!-- Formations list -->
        <div class="space-y-4">
            @foreach ($formationsAll as $formation)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <h3 class="font-semibold text-xl mb-2 text-slate-900 dark:text-white">{{ $formation->title }}</h3>
                            <p class="text-slate-600 dark:text-slate-400">{{ $formation->description }}</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            @if ($formation->is_attached)
                                <form action="{{ route('application.admin.formations.disable', [$team, $formation]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                        Désactiver cette formation
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('application.admin.formations.enable', [$team, $formation]) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                        Activer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Navigation retour -->
        <div class="mt-8 text-center">
            <a href="{{ route('application.admin.formations.index', $team) }}"
                class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl shadow-sm transition-colors">
                <span class="material-symbols-outlined mr-2">arrow_back</span>
                Retour aux formations
            </a>
        </div>
    </div>
</x-application-layout>
