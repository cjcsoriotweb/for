import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './app/Livewire/**/*Table.php',
    './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
    './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
      },

      colors: {
        // Palette "brand" corrigée + DEFAULT
        primary: {
          DEFAULT: '#137fec', // utilisé par `from-primary`, `text-primary`, etc.
          50:  '#e8f3fe',
          100: '#d9ecfe',
          200: '#bfe0fd',
          300: '#8fc8fb',
          400: '#5fb0f7',
          500: '#137fec', // ton bleu central
          600: '#0e6ccd',
          700: '#0a56a5',
          800: '#084681',
          900: '#073965',
          950: '#052949',
        },

        accent: {
          DEFAULT: '#d946ef',
          50:  '#fdf4ff',
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
          50:  '#f0fdf4',
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

  plugins: [forms, typography],
};
