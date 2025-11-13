<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<link href="https://unpkg.com/intro.js/minified/introjs.min.css" rel="stylesheet" />
<script src="https://unpkg.com/intro.js/minified/intro.min.js" defer></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script src="https://cdn.jsdelivr.net/npm/axios@1/dist/axios.min.js"></script>
<script>
  window.axios = axios;
  window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
</script>

@livewireStyles
@stack('head')
