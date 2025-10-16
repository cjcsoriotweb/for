<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased">

<head>
    <title>
        @hasSection('title')
            @yield('title') —
        @endif{{ config('app.name', 'Application') }}
    </title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @livewireStyles
    <x-header />
    @stack('head')
</head>

<body>
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50
       focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:text-blue-700 dark:focus:bg-gray-800">
        Aller au contenu
    </a>

    <x-banner />

    <div class="min-h-screen flex flex-col">

        {{-- En-tête de page optionnel --}}
        @isset($header)
            {{ $header }}
        @else
            <header
                class="sticky top-0 z-40 w-full border-b border-slate-200 bg-white/80 backdrop-blur-md dark:border-slate-700 dark:bg-slate-900/80">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <!-- Logo and Title -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-purple-600">
                                <span class="material-symbols-outlined text-lg text-white">school</span>
                            </div>
                            <div>
                                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Formation') }}</h1>
                                <a href="{{ route('vous.index') }}" class="text-xs text-slate-500 dark:text-slate-400">{{ __('Espace personnel') }}</a>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- User Info -->
                            <div class="hidden sm:block text-right">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Account Actions Dropdown -->
                            <div class="relative" id="user-menu">
                                <button onclick="toggleUserMenu()"
                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                </button>

                                <!-- Dropdown Menu -->
                                <div id="user-menu-dropdown"
                                    class="absolute right-0 mt-2 w-56 origin-top-right scale-95 transform rounded-lg bg-white py-2 shadow-lg ring-1 ring-slate-200 opacity-0 transition-all focus:outline-none dark:bg-slate-800 dark:ring-slate-700 hidden">
                                    <!-- User Info Header -->
                                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ auth()->user()->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}
                                        </p>
                                    </div>

                                    <!-- Menu Items -->
                                    <div class="py-1">
                                        <a href="{{ route('profile.show') }}"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-base mr-3">manage_accounts</span>
                                            {{ __('Mon compte') }}
                                        </a>
                                        <a href="{{ route('profile.show') }}#settings"
                                            class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">
                                            <span class="material-symbols-outlined text-base mr-3">settings</span>
                                            {{ __('Paramètres') }}
                                        </a>
                                    </div>

                                    <!-- Logout -->
                                    <div class="border-t border-slate-200 pt-1 dark:border-slate-700">
                                        <form method="POST" action="{{ route('logout') }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                                <span class="material-symbols-outlined text-base mr-3">logout</span>
                                                {{ __('Déconnexion') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
        @endisset


        {{-- 👉 Slot BLOCK optionnel (carte centrale au-dessus du contenu) --}}
        @isset($block)
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-700 dark:text-gray-100">
                        {{ $block }}
                    </div>
                </div>
            </div>
        @endisset

        {{-- Flash status éventuel --}}
        @if (session('status'))
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/40 dark:text-green-100">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main id="main-content" class="flex-1 py-8">
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="w-full">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <footer class="border-t border-gray-200 dark:border-gray-800 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col space-y-4 text-center">
                <p><b>© {{ now()->year }} {{ config('app.name') }} — {{ __('Tous droits réservés.') }}</b></p>
                <p>
                    <a href="{{ route('policy') }}"
                        class="text-blue-500 hover:text-blue-700">{{ __('Mentions légales') }}</a>
                </p>
            </div>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-center">
                <a href="{{ route('superadmin.home') }}" type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-4 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('Superadmin') }}</a>
            </div>
        </footer>


    </div>

    {{-- Panneau de debug Auth (Gate/Policy) --}}

    @stack('modals')
    @livewireScripts
    @stack('scripts')
    <x-auth-debug-panel />

    <!-- JavaScript for enhanced interactions -->
    <script>
        // Toggle info section
        function toggleInfo() {
            const info = document.getElementById("info");
            if (info) {
                info.classList.toggle("hidden");
            }
        }

        // Auto-refresh functionality
        let refreshTimeout;

        function startAutoRefresh() {
            refreshTimeout = setTimeout(function() {
                const refreshForm = document.getElementById("refresh-form");
                const refreshIcon = document.getElementById("refresh-icon");

                if (refreshForm) {
                    refreshForm.classList.remove("hidden");
                }
                if (refreshIcon) {
                    refreshIcon.classList.add("hidden");
                }
            }, 5000);
        }

        // Copy email to clipboard functionality
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    // Show success feedback
                    showToast('Adresse e-mail copiée !');
                }).catch(function(err) {
                    console.error('Erreur lors de la copie: ', err);
                    fallbackCopyTextToClipboard(text);
                });
            } else {
                fallbackCopyTextToClipboard(text);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showToast('Adresse e-mail copiée !');
                } else {
                    showToast('Erreur lors de la copie', 'error');
                }
            } catch (err) {
                console.error('Erreur lors de la copie: ', err);
                showToast('Erreur lors de la copie', 'error');
            }

            document.body.removeChild(textArea);
        }

        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-sm font-medium shadow-lg transition-all duration-300 ${
                type === 'success'
                    ? 'bg-green-500 text-white'
                    : 'bg-red-500 text-white'
            }`;
            toast.textContent = message;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.add('translate-x-0', 'opacity-100');
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                toast.classList.remove('translate-x-0', 'opacity-100');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        // Enhanced button interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers for copy buttons
            const copyButtons = document.querySelectorAll('[onclick*="navigator.clipboard.writeText"]');
            copyButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const email = '{{ auth()->user()->email }}';
                    copyToClipboard(email);
                });
            });

            // Start auto-refresh timer
            startAutoRefresh();

            // Add smooth scrolling for better UX
            document.documentElement.style.scrollBehavior = 'smooth';
        });

        // Refresh form submission
        document.getElementById('refresh-form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading state
            const button = this.querySelector('button');
            const originalText = button.innerHTML;
            button.innerHTML =
                '<span class="material-symbols-outlined text-base animate-spin">refresh</span> Actualisation...';
            button.disabled = true;

            // Simulate refresh (you can replace this with actual AJAX call)
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                showToast('Page actualisée');
            }, 1000);
        });

        // User menu toggle functionality
        function toggleUserMenu() {
            const dropdown = document.getElementById('user-menu-dropdown');
            const button = document.getElementById('user-menu');

            if (dropdown.classList.contains('hidden')) {
                // Show dropdown
                dropdown.classList.remove('hidden', 'scale-95', 'opacity-0');
                dropdown.classList.add('scale-100', 'opacity-100');

                // Add click outside listener
                setTimeout(() => {
                    document.addEventListener('click', closeUserMenuOnClickOutside);
                }, 100);
            } else {
                // Hide dropdown
                closeUserMenu();
            }
        }

        function closeUserMenu() {
            const dropdown = document.getElementById('user-menu-dropdown');
            dropdown.classList.add('hidden', 'scale-95', 'opacity-0');
            dropdown.classList.remove('scale-100', 'opacity-100');
            document.removeEventListener('click', closeUserMenuOnClickOutside);
        }

        function closeUserMenuOnClickOutside(event) {
            const dropdown = document.getElementById('user-menu-dropdown');
            const button = document.getElementById('user-menu');

            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                closeUserMenu();
            }
        }

        // Close dropdown when clicking on links inside it
        document.getElementById('user-menu-dropdown')?.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.closest('button[type="submit"]')) {
                setTimeout(closeUserMenu, 150);
            }
        });
    </script>

    <style>
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</body>

</html>
