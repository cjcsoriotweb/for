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
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a9.027 9.027 0 0 1 1.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 0 0-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 0 1 0 9.424m-4.138-5.976a3.736 3.736 0 0 0-.88-1.388 3.737 3.737 0 0 0-1.388-.88m2.268 2.268a3.765 3.765 0 0 1 0 2.528m-2.268-4.796a3.765 3.765 0 0 0-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 0 1-1.388.88m2.268-2.268 4.138 3.448m0 0a9.027 9.027 0 0 1-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0-3.448-4.138m3.448 4.138a9.014 9.014 0 0 1-9.424 0m5.976-4.138a3.765 3.765 0 0 1-2.528 0m0 0a3.736 3.736 0 0 1-1.388-.88 3.737 3.737 0 0 1-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 0 1-1.652-1.306 9.027 9.027 0 0 1-1.306-1.652m0 0 4.138-3.448M4.33 16.712a9.014 9.014 0 0 1 0-9.424m4.138 5.976a3.765 3.765 0 0 1 0-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 0 1 1.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 0 0-1.652 1.306A9.025 9.025 0 0 0 4.33 7.288" />
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
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
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
