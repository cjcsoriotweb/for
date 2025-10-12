<nav class="bg-blue-600 border-b border-blue-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.index') }}" class="text-white font-bold text-xl hover:text-blue-100 transition duration-150">
                        Admin Panel
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        $adminItems = [
                        ['route' => 'admin.index', 'label' => 'Dashboard'],
                        ['route' => 'admin.formations.index', 'label' => 'Formations'],
                        ];
                    @endphp
                    @foreach($adminItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs($item['route']) ? 'border-white text-white' : 'border-transparent text-blue-100 hover:text-white hover:border-blue-200 focus:text-white focus:border-blue-200' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-100 hover:text-white hover:bg-blue-500 focus:outline-none focus:bg-blue-500 focus:text-white transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-data="{ open: false }" :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 bg-blue-700">
            @php
                $adminItems = [
                ['route' => 'admin.index', 'label' => 'Dashboard'],
                ['route' => 'admin.formations.index', 'label' => 'Formations'],
                ];
            @endphp
            @foreach($adminItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="block pl-3 pr-4 py-2 text-base font-medium transition duration-150 ease-in-out {{ request()->routeIs($item['route']) ? 'text-white bg-blue-800 border-r-4 border-blue-200' : 'text-blue-100 hover:text-white hover:bg-blue-500 focus:text-white focus:bg-blue-500 focus:outline-none' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</nav>
