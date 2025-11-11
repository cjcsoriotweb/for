@if (Auth::check())
    <div class="relative">
        <button popovertarget="user-menu-product" class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900">
            {{ __('Mon compte') }}
            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true"
                class="size-5 flex-none text-gray-400">
                <path
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" fill-rule="evenodd" />
            </svg>
        </button>

        <el-popover id="user-menu-product" anchor="bottom" popover
            class="w-screen max-w-md overflow-hidden rounded-3xl bg-white shadow-lg outline-1 outline-gray-900/5 transition transition-discrete [--anchor-gap:--spacing(3)] backdrop:bg-transparent open:block data-closed:translate-y-1 data-closed:opacity-0 data-enter:duration-200 data-enter:ease-out data-leave:duration-150 data-leave:ease-in">
            <div class="p-4">


                <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                    <div
                        class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            data-slot="icon" aria-hidden="true"
                            class="size-6 text-gray-600 group-hover:text-indigo-600">
                            <path d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="{{ route('user.profile') }}" class="block font-semibold text-gray-900">
                            {{ __('Mon compte') }}
                            <span class="absolute inset-0"></span>
                        </a>

                        <p class="mt-1 text-gray-600">{{ __('Modifier vos informations personnelles') }}</p>
                    </div>
                </div>

                <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                    <div
                        class="flex size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            data-slot="icon" aria-hidden="true"
                            class="size-6 text-gray-600 group-hover:text-indigo-600">
                            <path d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="{{ route('user.tickets') }}" class="block font-semibold text-gray-900">
                            {{ __("Demande d'aide") }}
                            <span class="absolute inset-0"></span>
                        </a>

                    </div>
                </div>

                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <div class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm/6 hover:bg-gray-50">
                        <div
                            class="flex size-11 flex-none items-center justify-center rounded-lg bg-red-50 group-hover:bg-white">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                data-slot="icon" aria-hidden="true"
                                class="size-6 text-gray-600 group-hover:text-indigo-600">
                                <path
                                    d="M15.042 21.672 13.684 16.6m0 0-2.51 2.225.569-9.47 5.227 7.917-3.286-.672ZM12 2.25V4.5m5.834.166-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243-1.59-1.59"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="flex-auto">
                            <button class="block font-semibold text-red-900">
                                Déconnexion
                                <span class="absolute inset-0"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </el-popover>
    </div>
@endif
