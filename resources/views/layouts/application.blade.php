<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <x-header />
</head>

<body class="font-sans antialiased">
    <x-banner />


    <div class="min-h-screen bg-gray-100">
        
        <x-team-navigation-menu :team="$team" />

            @if(isset($header))
            {{  $header }}
            @endif





        <x-error-display />

        <main>
            
  
        @if(isset($block))
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <p class="text-gray-700">
                        {{ $block}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>