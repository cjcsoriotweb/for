<x-app-layout>

    {{-- NAV : ton menu d’équipe --}}
    <x-slot name="nav">
        <x-team-navigation-menu :team="$team" />
    </x-slot>

    {{-- HEADEROptionnel (titre de page) --}}
    @isset($header)
        <x-slot name="header">
            <h1 class="text-center text-xl font-semibold">{{ $header }}</h1>
        </x-slot>
    @endisset

    {{-- BLOCK optionnel (carte au-dessus du contenu principal) --}}
    @isset($block)
        <x-slot name="block">
            {{-- ton “p .text-gray-700” d’origine --}}
            <p class="text-gray-700 dark:text-gray-100">
                {{ $block }}
            </p>
        </x-slot>
    @endisset

    @isset($slot)
        {{ $slot }}
    @endisset
    

    {{-- Zone d’erreurs / alertes personnalisées si tu en as une --}}
    <x-error-display />

    {{-- CONTENU PRINCIPAL DE LA PAGE --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Mets ici le contenu “{{ $slot }}” de ton ancienne page --}}
        {{-- Exemple: --}}
        @yield('content') {{-- si tu utilises des sections --}}
        {{-- ou bien ton contenu Blade direct --}}
    </div>

</x-app-layout>
