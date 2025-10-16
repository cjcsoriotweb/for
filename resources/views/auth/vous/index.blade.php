<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between p-6 bg-slate-700 text-white">
            <h2 class="flex items-center">
                <span class="material-symbols-outlined text-4xl mr-2">home</span>
                {{__("Accueil")}}
            </h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button class="text-white hover:bg-red-600">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    Déconnexion
                </x-button>
            </form>
        </header>
    </x-slot>

    <main class="flex flex-1 justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl space-y-12">
            <div>
                <h1 class="text-4xl font-bold tracking-tight text-background-dark dark:text-background-light">
                    {{ __('Bienvenue sur votre espace de formation')}}
                </h1>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-background-dark dark:text-background-light mb-6 relative">
                    <span class="material-symbols-outlined text-4xl mr-2 animate-bounce ease-in-out duration-500 cursor-pointer">
                        arrow_downward
                    </span>
                    {{ __('Cliquez sur l\'organisme')}}
                </h2>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($items as $application)
                    <form method="POST" action="{{ route('application.switch', $application) }}">
                        @csrf
                        <button type="submit"
                            class="flex cursor-pointer flex-col gap-4 rounded-xl border border-primary/20 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-primary/30 dark:bg-background-dark/50 dark:hover:bg-background-dark">
                            <div class="h-12 w-12 rounded-lg bg-cover bg-center"
                                style='background-image: url("{{ $application->profile_photo_url }}");'>
                            </div>
                            <div class="flex flex-col">
                                <h3 class="font-semibold text-background-dark dark:text-background-light">{{ $application->name }}</h3>
                                <p class="text-sm text-background-dark/60 dark:text-background-light/60">{{__('Cliquez ici pour accéder à votre espace')}}</p>
                            </div>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-background-dark dark:text-background-light mb-6">Pending invitations
                </h2>
                <div class="space-y-4">
                    @foreach($invitations_pending as $invitation)
                    <div
                        class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm dark:bg-background-dark/50">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 flex-shrink-0 rounded-lg bg-cover bg-center"
                                style='background-image: url("{{ $invitation->team->profile_photo_url }}");'>
                            </div>
                            <div>
                                <p class="font-medium text-background-dark dark:text-background-light">{{ __("Invitation de l'organisme")}}
                                    {{ $invitation->team->name }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('vous.invitation.accept', $invitation->id) }}">
                            @csrf
                            <button type="submit"
                                class="h-9 min-w-[84px] rounded bg-primary px-4 text-sm font-medium text-white shadow-sm hover:bg-primary/90">
                                {{ __('Accepter') }}
                            </button>
                            @method('PATCH')
                            @csrf
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>


  

</x-app-layout>
