<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOR Academy</title>
    <x-header/>
    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-layout {
            min-height: 100vh;
            background: #f8fafc;
            position: relative;
        }

        .header {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .logo span {
            color: #0f172a;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
        }

        .nav-link:hover {
            color: #334155;
            background: #f1f5f9;
        }

        .buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-primary {
            background: #1e293b;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 4px rgba(30, 41, 59, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(30, 41, 59, 0.2);
        }

        .btn-secondary {
            background: white;
            color: #475569;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            text-decoration: none;
            transition: border-color 0.3s ease, background 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem 2rem;
        }

        .content-container {
            width: 100%;
            max-width: 1200px;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #374151;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .buttons {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .content {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-layout">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <a href="{{ route('home') }}">{{ config('app.name') }}</a>
            </div>

            <nav class="nav">
             
            </nav>

            <div class="buttons">
                @if(Auth::check())
          <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary">{{ __('Deconnexion')}}</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn-primary">Connexion</a>
                <a href="{{ route('register') }}" class="btn-secondary">S'inscrire</a>
                @endif

            </div>

            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </header>

        <!-- Main Content -->
        <main class="content">
            <div class="content-container">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
