@if (Auth::user()->superadmin)
    <div class="relative">
        <button popovertarget="admin-menu-product"
            class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
            {{__("Administration")}}
            <!-- Chevron -->
            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                class="size-5 flex-none text-gray-400">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M5.22 7.22a.75.75 0 0 1 1.06 0L10 10.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 8.28a.75.75 0 0 1 0-1.06Z" />
            </svg>
        </button>

        <el-popover id="admin-menu-product" anchor="bottom" popover
            class="w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-lg outline-1 outline-gray-900/5 transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-black/40 backdrop-blur-sm open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">
            <div class="p-4">

                <!-- Accueil -->
                <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                    <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                        <!-- Icône maison -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                             class="size-6 text-gray-600 group-hover:text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m3 10.5 9-7.5 9 7.5M4.5 9.75v9a2.25 2.25 0 0 0 2.25 2.25h10.5A2.25 2.25 0 0 0 19.5 18.75v-9" />
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="{{ route('superadmin.overview') }}" class="block font-semibold text-gray-900">
                            {{ __('Accueil') }}
                            <span class="absolute inset-0"></span>
                        </a>
                        <p class="mt-1 text-gray-600">Vue d’ensemble et statistiques clés.</p>
                    </div>
                </div>

                <!-- Support & Tickets -->
                <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                    <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                        <!-- Icône assistance (bouée) -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                             class="size-6 text-gray-600 group-hover:text-indigo-600">
                            <circle cx="12" cy="12" r="9" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.93 4.93 7.76 7.76m8.48 8.48 2.83 2.83M16.24 7.76l2.83-2.83M4.93 19.07l2.83-2.83"/>
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="{{ route('superadmin.support.index') }}" class="block font-semibold text-gray-900">
                            {{ __('Support & Tickets') }}
                            <span class="absolute inset-0"></span>
                        </a>
                        <p class="mt-1 text-gray-600">Gérer les demandes et suivre les tickets.</p>
                    </div>
                </div>

                <!-- Utilisateurs -->
                <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                    <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                        <!-- Icône utilisateurs -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                             class="size-6 text-gray-600 group-hover:text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 19.5a6 6 0 1 0-12 0m18 0a6 6 0 0 0-9-5.197M8.25 9.75a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.5 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="{{ route('superadmin.users.index') }}" class="block font-semibold text-gray-900">
                            {{ __('Utilisateurs') }}
                            <span class="absolute inset-0"></span>
                        </a>
                        <p class="mt-1 text-gray-600">Créer, modifier et gérer les comptes.</p>
                    </div>
                </div>

                <!-- Équipes -->
                <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                    <div class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                        <!-- Icône équipes (utilisateurs groupés) -->
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"
                             class="size-6 text-gray-600 group-hover:text-indigo-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M7.5 14.25a4.5 4.5 0 0 0-4.5 4.5h6m7.5-4.5a4.5 4.5 0 0 1 4.5 4.5h-6M8.25 8.25a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7.5 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="{{ route('superadmin.teams.index') }}" class="block font-semibold text-gray-900">
                            {{ __('Équipes') }}
                            <span class="absolute inset-0"></span>
                        </a>
                        <p class="mt-1 text-gray-600">Organiser les équipes et les rôles.</p>
                    </div>
                </div>

            </div>

            <!-- Pied de popover (liens secondaires) -->
            <div class="grid grid-cols-2 divide-x divide-gray-900/5 bg-gray-50">
                <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-100">
                    <!-- Icône lecture / démo -->
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                         class="size-5 flex-none text-gray-400">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M2 10a8 8 0 1 1 16 0 8 8 0 0 1-16 0Zm6.39-2.908a.75.75 0 0 1 .766.027l3.5 2.25a.75.75 0 0 1 0 1.262l-3.5 2.25A.75.75 0 0 1 8 12.25v-4.5a.75.75 0 0 1 .39-.658Z"/>
                    </svg>
                    Documentation
                </a>
                <a href="#" class="flex items-center justify-center gap-x-2.5 p-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-100">
                    <!-- Icône contact -->
                    <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                         class="size-5 flex-none text-gray-400">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M2 3.5A1.5 1.5 0 0 1 3.5 2h13A1.5 1.5 0 0 1 18 3.5v13a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 2 16.5v-13Zm2.06 2.56a.75.75 0 0 0 0 1.06l3.75 3.75a2.5 2.5 0 0 0 3.54 0l3.75-3.75a.75.75 0 1 0-1.06-1.06L9.79 9.31a1 1 0 0 1-1.41 0L4.94 6.06a.75.75 0 0 0-1.06 0Z"/>
                    </svg>
                    Nous contacter
                </a>
            </div>
        </el-popover>
    </div>
@endif
