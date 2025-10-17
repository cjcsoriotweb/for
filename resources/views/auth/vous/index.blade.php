<x-app-layout>
    <!-- Full Height Container -->
    <div class="min-h-screen flex flex-col">
        <main class="flex-1 relative">
            <!-- Background gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 pointer-events-none"></div>
            <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
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

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            <!-- Add Organization Card - Premium Design -->
                            <div class="group">
                                <div class="w-full relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8 shadow-lg ring-2 ring-slate-200/60 dark:from-slate-800/50 dark:via-slate-700/50 dark:to-slate-600/50 dark:ring-slate-600/40 transition-all duration-700 hover:shadow-2xl hover:ring-blue-300/50 dark:hover:ring-blue-400/50 hover:scale-[1.02] cursor-pointer">
                                    <!-- Multiple animated background layers -->
                                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100/80 via-indigo-100/60 to-purple-100/80 opacity-0 transition-all duration-700 group-hover:opacity-100 dark:from-blue-900/30 dark:via-indigo-900/20 dark:to-purple-900/30"></div>
                                    <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-blue-50/30 to-purple-50/40 opacity-0 transition-all duration-500 group-hover:opacity-100 dark:via-blue-900/10 dark:to-purple-900/15"></div>

                                    <!-- Animated particles effect -->
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                                        <div class="absolute top-4 left-4 h-2 w-2 bg-blue-400 rounded-full animate-pulse"></div>
                                        <div class="absolute top-8 right-6 h-1.5 w-1.5 bg-purple-400 rounded-full animate-pulse animation-delay-300"></div>
                                        <div class="absolute bottom-6 left-8 h-1 w-1 bg-indigo-400 rounded-full animate-pulse animation-delay-600"></div>
                                        <div class="absolute bottom-4 right-4 h-2.5 w-2.5 bg-blue-300 rounded-full animate-pulse animation-delay-900"></div>
                                    </div>

                                    <div class="relative z-10 flex flex-col items-center justify-center h-full min-h-[200px]">
                                        <!-- Premium Plus icon container -->
                                        <div class="mb-6 relative">
                                            <!-- Main icon with multiple layers -->
                                            <div class="relative flex h-28 w-28 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 shadow-2xl ring-4 ring-white/30 dark:ring-slate-800/40 transition-all duration-700 group-hover:scale-110 group-hover:shadow-2xl group-hover:ring-blue-200/50 dark:group-hover:ring-blue-700/50">
                                                <!-- Inner glow effect -->
                                                <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                                <!-- Main icon -->
                                                <span class="material-symbols-outlined text-5xl text-white drop-shadow-lg relative z-10">add</span>
                                            </div>

                                            <!-- Orbiting ring effect -->
                                            <div class="absolute inset-0 rounded-3xl ring-2 ring-blue-400/30 scale-100 group-hover:scale-125 opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                                            <div class="absolute inset-0 rounded-3xl ring-1 ring-purple-400/20 scale-100 group-hover:scale-150 opacity-0 group-hover:opacity-100 transition-all duration-1000"></div>

                                            <!-- Corner sparkles -->
                                            <div class="absolute -top-1 -right-1 h-3 w-3 bg-yellow-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300 animate-pulse"></div>
                                            <div class="absolute -bottom-1 -left-1 h-2 w-2 bg-blue-300 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse animation-delay-200"></div>
                                        </div>

                                        <!-- Enhanced Content -->
                                        <div class="text-center">
                                            <h3 class="mb-4 text-2xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-500">
                                                {{ __('Rechercher un organisme') }}
                                            </h3>
                                            <div class="flex items-center justify-center text-base font-semibold text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 bg-blue-50/50 dark:bg-blue-900/20 px-4 py-2 rounded-full transition-all duration-300 group-hover:bg-blue-100/70 dark:group-hover:bg-blue-800/30">
                                                <span class="material-symbols-outlined text-xl mr-2 transition-transform duration-300 group-hover:scale-110">search</span>
                                                <span>{{ __('Découvrir') }}</span>
                                            </div>
                                        </div>

                                        <!-- Enhanced bottom accent line -->
                                        <div class="absolute bottom-0 left-0 h-1.5 w-full bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 transform scale-x-0 transition-transform duration-700 group-hover:scale-x-100 origin-left"></div>
                                    </div>
                                </div>
                            </div>

                            @foreach ($items as $application)
                                <div class="group">
                                    <form method="POST" action="{{ route('application.switch', $application) }}">
                                        @csrf
                                        <button type="submit"
                                            class="group/btn w-full relative overflow-hidden rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200 transition-all duration-500 hover:shadow-xl hover:ring-2 hover:ring-blue-500/20 hover:scale-[1.02] dark:bg-slate-800/50 dark:ring-slate-700 dark:hover:bg-slate-800 dark:hover:ring-blue-400/30">
                                            <!-- Animated background gradient on hover -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 opacity-0 transition-all duration-500 group-hover/btn:opacity-100 dark:from-blue-900/20 dark:via-indigo-900/20 dark:to-purple-900/20"></div>

                                            <!-- Subtle pattern overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-transparent to-slate-50/50 opacity-0 transition-opacity duration-500 group-hover/btn:opacity-100 dark:to-slate-800/50"></div>

                                            <!-- Centered background logo -->
                                            <div class="absolute inset-0 flex items-center justify-center opacity-8 group-hover/btn:opacity-15 transition-opacity duration-500">
                                                @if ($application->profile_photo_url)
                                                    <img src="{{ $application->profile_photo_url }}"
                                                        alt=""
                                                        class="h-40 w-40 rounded-2xl opacity-30 group-hover/btn:opacity-50 transition-opacity duration-500"
                                                        style="object-fit: scale-down;">
                                                @else
                                                    <div class="h-40 w-40 rounded-2xl bg-gradient-to-br from-blue-400/10 via-purple-500/10 to-indigo-600/10 flex items-center justify-center backdrop-blur-sm">
                                                        <span class="material-symbols-outlined text-5xl text-slate-400 dark:text-slate-500">business</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="relative z-10">
                                                <!-- Logo container as main feature -->
                                                <div class="mb-6 flex h-20 w-20 items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-br from-white via-slate-50 to-slate-100 ring-2 ring-slate-200/50 dark:from-slate-700/50 dark:via-slate-600/50 dark:to-slate-500/50 dark:ring-slate-600/30 group-hover/btn:ring-blue-300/50 dark:group-hover/btn:ring-blue-400/50 transition-all duration-500 group-hover/btn:scale-105">
                                                    @if ($application->profile_photo_url)
                                                        <!-- Image with advanced styling -->
                                                        <div class="relative h-full w-full rounded-2xl overflow-hidden">
                                                            <img src="{{ $application->profile_photo_url }}"
                                                                alt="{{ $application->name }}"
                                                                class="h-full w-full object-cover transition-transform duration-700 group-hover/btn:scale-110">
                                                        </div>
                                                    @else
                                                        <!-- Enhanced default logo -->
                                                        <div class="relative">
                                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 via-purple-500 to-indigo-600 rounded-2xl opacity-90 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
                                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-300 via-purple-400 to-indigo-500 rounded-2xl opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
                                                            <div class="relative rounded-2xl bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-700 p-4 group-hover/btn:scale-105 transition-transform duration-500">
                                                                <span class="material-symbols-outlined text-3xl text-white drop-shadow-sm">business</span>
                                                            </div>
                                                            <!-- Pulsing ring effect -->
                                                            <div class="absolute inset-0 rounded-2xl ring-2 ring-blue-400/30 scale-100 group-hover/btn:scale-110 opacity-0 group-hover/btn:opacity-100 transition-all duration-500"></div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Content with better typography -->
                                                <div class="text-center">
                                                    <h3 class="mb-3 text-xl font-bold text-slate-900 dark:text-white group-hover/btn:text-blue-600 dark:group-hover/btn:text-blue-400 transition-colors duration-300 leading-tight">
                                                        {{ $application->name }}
                                                    </h3>
                                                    <div class="flex items-center justify-center text-sm font-medium text-blue-600 dark:text-blue-400 group-hover/btn:text-blue-700 dark:group-hover/btn:text-blue-300">
                                                        <span class="material-symbols-outlined text-lg mr-2 transition-transform duration-300 group-hover/btn:scale-110">rocket_launch</span>
                                                        <span>{{ __('Accéder') }}</span>
                                                    </div>
                                                </div>

                                                <!-- Enhanced hover indicator -->
                                                <div class="absolute top-4 right-4 opacity-0 transition-all duration-500 group-hover/btn:opacity-100">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                                                        <span class="material-symbols-outlined text-base">arrow_forward</span>
                                                    </div>
                                                </div>

                                                <!-- Bottom accent line -->
                                                <div class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-blue-500 to-purple-600 transform scale-x-0 transition-transform duration-500 group-hover/btn:scale-x-100 origin-left"></div>
                                            </div>
                                        </button>
                                    </form>
                                </div>
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
                                    class="group relative overflow-hidden rounded-2xl bg-white p-6 ring-1 ring-slate-200 transition-all duration-300 hover:shadow-lg dark:bg-slate-800/50 dark:ring-slate-700">
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
                                                        class="h-full w-full " style="object-fit: scale-down;">
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
                                                class="group/btn flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:from-emerald-600 hover:to-green-600 hover:scale-105">
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
                @endif
            </div>
        </main>

    </div>


</x-app-layout>
