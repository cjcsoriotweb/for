<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet" />
<script src="https://unpkg.com/intro.js/minified/intro.min.js" defer></script>

<script>
  window.tailwind = window.tailwind || {};
  window.tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          sans: ['Inter', 'Figtree', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'sans-serif'],
        },
        colors: {
          primary: {
            DEFAULT: '#137fec',
            50: '#e8f3fe',
            100: '#d9ecfe',
            200: '#bfe0fd',
            300: '#8fc8fb',
            400: '#5fb0f7',
            500: '#137fec',
            600: '#0e6ccd',
            700: '#0a56a5',
            800: '#084681',
            900: '#073965',
            950: '#052949',
          },
          accent: {
            DEFAULT: '#d946ef',
            50: '#fdf4ff',
            100: '#fae8ff',
            200: '#f5d0fe',
            300: '#f0abfc',
            400: '#e879f9',
            500: '#d946ef',
            600: '#c026d3',
            700: '#a21caf',
            800: '#86198f',
            900: '#701a75',
            950: '#4a044e',
          },
          success: {
            DEFAULT: '#22c55e',
            50: '#f0fdf4',
            100: '#dcfce7',
            200: '#bbf7d0',
            300: '#86efac',
            400: '#4ade80',
            500: '#22c55e',
            600: '#16a34a',
            700: '#15803d',
            800: '#166534',
            900: '#14532d',
            950: '#052e16',
          },
        },
        animation: {
          'gradient-x': 'gradient-x 15s ease infinite',
          float: 'float 6s ease-in-out infinite',
          'pulse-glow': 'pulse-glow 2s ease-in-out infinite alternate',
          shimmer: 'shimmer 1.5s infinite',
          'bounce-in': 'bounce-in 0.6s ease-out',
          'slide-in-up': 'slide-in-up 0.5s ease-out',
          'skeleton-loading': 'skeleton-loading 1.5s ease-in-out infinite',
        },
        keyframes: {
          'gradient-x': {
            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
          },
          float: {
            '0%, 100%': { transform: 'translateY(0px)' },
            '50%': { transform: 'translateY(-10px)' },
          },
          'pulse-glow': {
            from: { boxShadow: '0 0 5px rgb(34 197 94), 0 0 10px rgb(34 197 94), 0 0 15px rgb(34 197 94)' },
            to: { boxShadow: '0 0 10px rgb(34 197 94), 0 0 20px rgb(34 197 94), 0 0 30px rgb(34 197 94)' },
          },
          shimmer: {
            '0%': { transform: 'translateX(-100%)' },
            '100%': { transform: 'translateX(100%)' },
          },
          'bounce-in': {
            '0%': { opacity: '0', transform: 'scale(0.3)' },
            '50%': { transform: 'scale(1.05)' },
            '100%': { opacity: '1', transform: 'scale(1)' },
          },
          'slide-in-up': {
            '0%': { transform: 'translateY(30px)', opacity: '0' },
            '100%': { transform: 'translateY(0)', opacity: '1' },
          },
          'skeleton-loading': {
            '0%': { 'background-position': '200% 0' },
            '100%': { 'background-position': '-200% 0' },
          },
        },
        boxShadow: {
          'glow-primary': '0 0 20px rgba(14, 165, 233, 0.3)',
          'glow-accent': '0 0 20px rgba(217, 70, 239, 0.3)',
          'glow-success': '0 0 20px rgba(34, 197, 94, 0.3)',
          elevated: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
        },
        backdropBlur: {
          xs: '2px',
        },
      },
    },
  };
</script>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>

<style type="text/tailwindcss">
[x-cloak] {
    display: none;
}

/* Animated gradients */
@keyframes gradient-shimmer {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Glassmorphism effects */
@layer components {
    .glass-card {
        @apply bg-white/70 backdrop-blur-sm border border-white/20 dark:bg-slate-800/70 dark:border-slate-700/50;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .premium-shadow {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .card-hover {
        @apply transition-all duration-300 ease-out hover:shadow-2xl hover:-translate-y-1;
        transform: perspective(1000px);
    }

    .animated-gradient {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
        background-size: 400% 400%;
        animation: gradient-shimmer 4s ease infinite;
    }

    .floating-element {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .pulse-glow {
        animation: pulse-glow 2s ease-in-out infinite alternate;
    }

    @keyframes pulse-glow {
        from { box-shadow: 0 0 5px theme('colors.emerald.400'), 0 0 10px theme('colors.emerald.400'), 0 0 15px theme('colors.emerald.400'); }
        to { box-shadow: 0 0 10px theme('colors.emerald.400'), 0 0 20px theme('colors.emerald.400'), 0 0 30px theme('colors.emerald.400'); }
    }

    .skeleton-loader {
        animation: skeleton-loading 1.5s ease-in-out infinite;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
    }

    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .progress-bar-animated {
        transition: width 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .progress-bar-animated::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .bounce-in {
        animation: bounce-in 0.6s ease-out;
    }

    @keyframes bounce-in {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    .slide-in-up {
        animation: slide-in-up 0.5s ease-out;
    }

    @keyframes slide-in-up {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .magic-bg {
        background: radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3), transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3), transparent 50%),
                    radial-gradient(circle at 40% 80%, rgba(120, 219, 255, 0.3), transparent 50%);
    }

    .button-chip {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all duration-200 hover:scale-105;
    }
}

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

/* Ensure Tailwind utilities are available */
.bg-primary {
  background-color: #137fec;
}

.text-primary {
  color: #137fec;
}

.border-primary {
  border-color: #137fec;
}

.hover\:bg-primary:hover {
  background-color: #137fec;
}

.hover\:text-primary:hover {
  color: #137fec;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/axios@1/dist/axios.min.js"></script>
<script>
  window.axios = axios;
  window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
</script>

@livewireStyles
@stack('head')
