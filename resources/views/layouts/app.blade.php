<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Layout</title>
    <x-header />
    @livewireStyles
</head>

<body>
    <div class="main-layout">
        <x-out-app-navigation-menu />

        <!-- Main Content -->
        <main class="content">
            <div class="content-container">
                <div class="full width">
                    {{ $slot }}
                </div>
            </div>
        </main>
        @livewireScripts
    </div>
</body>

</html>