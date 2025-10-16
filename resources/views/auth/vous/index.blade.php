<x-app-layout>
    <x-slot name="header">
        <header class="flex items-center justify-between p-6 bg-slate-700 text-white">
            <h2 class="flex items-center">
                <span class="material-symbols-outlined text-4xl mr-2">home</span>
                {{ __('Accueil') }}
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
                    {{ __('Bienvenue sur votre espace de formation') }}
                </h1>
            </div>
            @if ($items->count() > 0)
                <div>
                    <h2 class="text-2xl font-bold text-background-dark dark:text-background-light mb-6 relative">
                        <span
                            class="material-symbols-outlined text-4xl mr-2 animate-bounce ease-in-out duration-500 cursor-pointer">
                            arrow_downward
                        </span>
                        {{ __('Cliquez sur l\'organisme') }}
                    </h2>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($items as $application)
                            <form method="POST" action="{{ route('application.switch', $application) }}">
                                @csrf
                                <button type="submit"
                                    class="flex cursor-pointer flex-col gap-4 rounded-xl border border-primary/20 bg-white p-6 shadow-sm transition-all hover:shadow-lg dark:border-primary/30 dark:bg-background-dark/50 dark:hover:bg-background-dark">
                                    <div class="h-12 w-12 rounded-lg bg-cover bg-center"
                                        style='background-image: url("{{ $application->profile_photo_url }}");'>
                                    </div>
                                    <div class="flex flex-col">
                                        <h3 class="font-semibold text-background-dark dark:text-background-light">
                                            {{ $application->name }}</h3>
                                        <p class="text-sm text-background-dark/60 dark:text-background-light/60">
                                            {{ __('Cliquez ici pour accéder à votre espace') }}</p>
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center p-8 bg-background-light/50 dark:bg-background-dark/50">
                    <div class="text-center">
                        <p class="text-sm font-semibold text-background-dark dark:text-background-light">
                            {{ __('Vous n\'êtes dans aucun organisme de formation') }}
                        </p>
                        <p class="text-sm text-background-dark/60 dark:text-background-light/60">
                            {{ __('Partagez votre e-mail avec un organisme pour rejoindre leur équipe.') }}
                        </p>
                        <p class="text-sm text-background-dark/60 dark:text-background-light/60">
                            {{ __('N\'oubliez pas de partager votre e-mail : ') }}<strong>{{ auth()->user()->email }}</strong>{{ __(' à votre organisme.') }}
                        </p>
                    </div>
                </div>


            @endif
            @if ($invitations_pending->count() > 0)
                <div>
                    <h2 class="text-2xl font-bold text-background-dark dark:text-background-light mb-6">Pending
                        invitations
                    </h2>
                    <div class="space-y-4">
                        @foreach ($invitations_pending as $invitation)
                            <div
                                class="flex items-center justify-between rounded-lg bg-white p-4 shadow-sm dark:bg-background-dark/50">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 flex-shrink-0 rounded-lg bg-cover bg-center"
                                        style='background-image: url("{{ $invitation->team->profile_photo_url }}");'>
                                    </div>
                                    <div>
                                        <p class="font-medium text-background-dark dark:text-background-light">
                                            {{ __("Invitation de l'organisme") }}
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
            @else
                <div class="flex items-center justify-center flex-col animate-pulse">

                    <div class="relative bg-background-light/50 dark:bg-background-dark/50 rounded-lg p-4">
                        <div class="text-xl font-medium text-background-dark dark:text-background-light" onclick="toggleInfo()">
                            <span class="material-symbols-outlined text-xl mr-2 text-info cursor-pointer" >info</span>
                            {{ __('Vous avez reçu une invitation de nouvel organisme de formation.') }}
                        </div>
                        <div id="info" class="hidden text-sm text-background-dark/60 dark:text-background-light/60 mt-4">
                            {{ __('Pour accepter cette invitation, cliquez sur le bouton "Accepter" dans la section "Invitations en attente".') }}
                        </div>
                    </div>
                    <script>
                        function toggleInfo() {
                            const info = document.getElementById("info");
                            info.classList.toggle("hidden");
                        }
                    </script>


                    <div>
                            <form method="GET" class="hidden" id="refresh-form">
                            <button type="submit" class="mt-4 flex items-center h-9 min-w-[84px] rounded bg-slate-100 px-4 text-sm font-medium text-slate-600 shadow-sm">
                                {{ __('Rafraîchir') }}
                            </button>
                        </form>
                        <svg id="refresh-icon"  class=" animate-spin w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 10m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById("refresh-form").classList.remove("hidden");
                        document.getElementById("refresh-icon").classList.add("hidden");
                    }, 5000);
                </script>

            @endif
        </div>
    </main>




</x-app-layout>
