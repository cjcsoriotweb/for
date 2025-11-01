<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet" />
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@stack('head')

@livewireScripts
