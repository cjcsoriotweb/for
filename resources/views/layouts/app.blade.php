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
                <span>{{ config('app.name') }}</span>
            </div>

            <nav class="nav">
             
            </nav>

            <div class="buttons">
                @if(Auth::check())
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
