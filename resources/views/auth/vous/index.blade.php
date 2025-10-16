<x-app-layout>
    <x-slot name="header">
        <header class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
            <!-- Background decoration -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

            <div class="relative flex items-center justify-between p-8 backdrop-blur-sm">
                <div class="flex items-center space-x-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10 backdrop-blur-sm">
                        <span class="material-symbols-outlined text-2xl text-white">school</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">{{ __('Accueil') }}</h2>
                        <p class="text-sm text-slate-300">{{ __('Espace de formation') }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="flex items-center space-x-2">
                    @csrf
                    <div class="hidden sm:block text-right mr-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-300">{{ auth()->user()->email }}</p>
                    </div>
                    <button type="submit" class="group flex items-center space-x-2 rounded-lg bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm transition-all hover:bg-red-500/20 hover:scale-105">
                        <span class="material-symbols-outlined text-lg transition-transform group-hover:rotate-12">logout</span>
                        <span class="hidden sm:inline">{{ __('Déconnexion') }}</span>
                    </button>
                </form>
            </div>
        </header>
    </x-slot>

    <main class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="mb-16 text-center">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg">
                    <span class="material-symbols-outlined text-3xl text-white">auto_awesome</span>
                </div>
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    {{ __('Bienvenue sur votre') }}
                    <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        {{ __('espace de formation') }}
                    </span>
                </h1>
                <p class="mx-auto max-w-2xl text-lg text-slate-600 dark:text-slate-300">
                    {{ __('Gérez vos formations et rejoignez de nouveaux organismes en toute simplicité') }}
                </p>
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
