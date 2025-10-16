<x-app-layout>
    <!-- Full Height Container -->
    <div class="min-h-screen flex flex-col">

        <main class="flex-1 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
            <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="mb-16 text-center">
                    <div
                        class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg">
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
                    <!-- Organizations Section -->
                    <div class="mb-16">
                        <div class="mb-8 text-center">
                            <h2 class="mb-4 text-3xl font-bold text-slate-900 dark:text-white">
                                {{ __('Vos organismes') }}
                            </h2>
                            <p class="text-slate-600 dark:text-slate-300">
                                {{ __('Sélectionnez l\'organisme pour accéder à votre espace de formation') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach ($items as $application)
                                <form method="POST" action="{{ route('application.switch', $application) }}">
                                    @csrf
                                    <button type="submit"
                                        class="group relative overflow-hidden rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-2xl hover:ring-2 hover:ring-blue-500/20 dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:bg-slate-800 dark:hover:ring-blue-400/30">
                                        <!-- Background gradient on hover -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-blue-50 to-purple-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100 dark:from-blue-900/20 dark:to-purple-900/20">
                                        </div>

                                        <div class="relative">
                                            <!-- Logo container with improved styling -->
                                            <div
                                                class="mb-6 flex h-16 w-16 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600">
                                                @if ($application->profile_photo_url)
                                                    <img src="{{ $application->profile_photo_url }}"
                                                        alt="{{ $application->name }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <span
                                                        class="material-symbols-outlined text-2xl text-slate-600 dark:text-slate-300">business</span>
                                                @endif
                                            </div>

                                            <!-- Content -->
                                            <div class="text-center">
                                                <h3
                                                    class="mb-2 text-lg font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                                    {{ $application->name }}
                                                </h3>
                                                <div
                                                    class="flex items-center justify-center text-sm text-slate-500 dark:text-slate-400">
                                                    <span
                                                        class="material-symbols-outlined text-base mr-1">arrow_forward</span>
                                                    {{ __('Accéder') }}
                                                </div>
                                            </div>

                                            <!-- Hover indicator -->
                                            <div
                                                class="absolute top-4 right-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-white shadow-lg">
                                                    <span class="material-symbols-outlined text-sm">chevron_right</span>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="mb-16">
                        <div
                            class="mx-auto max-w-md rounded-2xl bg-white p-12 text-center shadow-lg ring-1 ring-slate-200 dark:bg-slate-800/50 dark:ring-slate-700">
                            <div
                                class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/20 dark:to-orange-900/20">
                                <span
                                    class="material-symbols-outlined text-2xl text-amber-600 dark:text-amber-400">school</span>
                            </div>
                            <h3 class="mb-4 text-xl font-semibold text-slate-900 dark:text-white">
                                {{ __('Aucun organisme') }}
                            </h3>
                            <p class="mb-6 text-slate-600 dark:text-slate-300">
                                {{ __('Vous n\'êtes actuellement rattaché à aucun organisme de formation.') }}
                            </p>

                            <!-- Email sharing section -->
                            <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-700/50">
                                <p class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    {{ __('Votre adresse e-mail de connexion :') }}
                                </p>
                                <div
                                    class="flex items-center justify-between rounded-md bg-white px-3 py-2 dark:bg-slate-800">
                                    <span
                                        class="text-sm font-mono text-slate-900 dark:text-slate-100">{{ auth()->user()->email }}</span>
                                    <button onclick="navigator.clipboard.writeText('{{ auth()->user()->email }}')"
                                        class="flex h-6 w-6 items-center justify-center rounded text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-600 dark:hover:text-slate-300">
                                        <span class="material-symbols-outlined text-sm">content_copy</span>
                                    </button>
                                </div>
                                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('Partagez cette adresse avec un organisme pour rejoindre leur équipe') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($invitations_pending->count() > 0)
                    <!-- Pending Invitations Section -->
                    <div class="mb-16">
                        <div class="mb-8 text-center">
                            <h2 class="mb-4 text-3xl font-bold text-slate-900 dark:text-white">
                                {{ __('Invitations en attente') }}
                            </h2>
                            <p class="text-slate-600 dark:text-slate-300">
                                {{ __('Vous avez reçu de nouvelles invitations à rejoindre des organismes') }}
                            </p>
                        </div>

                        <div class="mx-auto max-w-2xl space-y-4">
                            @foreach ($invitations_pending as $invitation)
                                <div
                                    class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg ring-1 ring-slate-200 transition-all duration-300 hover:shadow-xl dark:bg-slate-800/50 dark:ring-slate-700">
                                    <!-- Background decoration -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-emerald-50 to-green-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100 dark:from-emerald-900/20 dark:to-green-900/20">
                                    </div>

                                    <div class="relative flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <!-- Team logo -->
                                            <div
                                                class="flex h-14 w-14 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600">
                                                @if ($invitation->team->profile_photo_url)
                                                    <img src="{{ $invitation->team->profile_photo_url }}"
                                                        alt="{{ $invitation->team->name }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <span
                                                        class="material-symbols-outlined text-xl text-slate-600 dark:text-slate-300">business</span>
                                                @endif
                                            </div>

                                            <!-- Invitation info -->
                                            <div>
                                                <h3 class="font-semibold text-slate-900 dark:text-white">
                                                    {{ $invitation->team->name }}
                                                </h3>
                                                <p class="text-sm text-slate-600 dark:text-slate-300">
                                                    {{ __('vous invite à rejoindre leur organisme') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Accept button -->
                                        <form method="POST"
                                            action="{{ route('vous.invitation.accept', $invitation->id) }}"
                                            class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="group/btn flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-2.5 text-sm font-medium text-white shadow-lg transition-all duration-300 hover:from-emerald-600 hover:to-green-600 hover:shadow-xl hover:scale-105">
                                                <span
                                                    class="material-symbols-outlined text-base transition-transform group-hover/btn:scale-110">check</span>
                                                {{ __('Accepter') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- No Invitations Info Section -->
                    <div class="mb-16">
                        <div class="mx-auto max-w-lg text-center">
                            <div
                                class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20">
                                <span
                                    class="material-symbols-outlined text-2xl text-blue-600 dark:text-blue-400">mail</span>
                            </div>

                            <div class="group cursor-pointer" onclick="toggleInfo()">
                                <h3 class="mb-4 text-xl font-semibold text-slate-900 dark:text-white">
                                    {{ __('Aucune invitation en attente') }}
                                </h3>
                                <div
                                    class="mx-auto max-w-sm rounded-lg bg-slate-50 p-4 transition-all duration-300 hover:bg-slate-100 dark:bg-slate-700/50 dark:hover:bg-slate-700">
                                    <div class="flex items-center text-slate-700 dark:text-slate-300">
                                        <span class="material-symbols-outlined text-xl mr-2 text-blue-500">info</span>
                                        <span
                                            class="text-sm font-medium">{{ __('Comment recevoir une invitation ?') }}</span>
                                    </div>
                                    <div id="info"
                                        class="mt-3 hidden text-left text-sm text-slate-600 dark:text-slate-400">
                                        <p class="mb-2">{{ __('• Demandez à un organisme de vous inviter') }}</p>
                                        <p class="mb-2">{{ __('• Partagez votre adresse e-mail avec eux') }}</p>
                                        <p>{{ __('• Ils pourront alors vous envoyer une invitation') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Refresh indicator -->
                            <div class="mt-8 flex items-center justify-center gap-3">
                                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                    <svg id="refresh-icon" class="animate-spin h-4 w-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 10m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    <span>{{ __('Vérification en cours...') }}</span>
                                </div>
                            </div>

                            <form method="GET" class="mt-4 hidden" id="refresh-form">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-2.5 text-sm font-medium text-slate-700 shadow-lg ring-1 ring-slate-200 transition-all hover:bg-slate-50 hover:shadow-xl hover:ring-slate-300 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700 dark:hover:bg-slate-700">
                                    <span class="material-symbols-outlined text-base">refresh</span>
                                    {{ __('Vérifier à nouveau') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </main>

    </div>


</x-app-layout>
