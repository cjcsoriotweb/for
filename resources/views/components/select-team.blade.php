
<div class="flex flex-col min-h-screen">
    <main class="flex-grow">
        <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-8 tracking-tight">
                Organismes
            </h1>
            <div class="space-y-4">

                @foreach($user->allTeams() as $team)
                <div
                    class="bg-white dark:bg-gray-800/50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 flex items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $team->name }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $team->description }}
                        </p>
                    </div>
                    <a class="bg-primary text-white font-bold py-2 px-6 rounded-full hover:bg-primary/80 transition-colors duration-300 flex items-center space-x-2"
                        href="{{ route('team.dashboard', ['team' => $team->id ]) }}">
                        <span>Aller</span>
                        <span class="material-symbols-outlined text-xl">arrow_forward</span>
                    </a>
                </div>
                @endforeach


            </div>
        </div>
    </main>
</div>