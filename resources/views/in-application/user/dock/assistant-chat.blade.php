<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant IA - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-6">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center mb-6">
                    <svg class="w-16 h-16 text-slate-900 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6M12 21c-4.871 0-8-3.129-8-8s3.129-8 8-8 8 3.129 8 8c0 2.315-.738 4.26-2.016 5.646.142 1.08.43 1.89.929 2.593.182.262.008.617-.31.617-.77 0-1.977-.285-3.021-.83A8.79 8.79 0 0 1 12 21z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Assistant IA</h2>
                    <p class="text-gray-600">Posez vos questions, l'assistant est là pour vous aider !</p>
                </div>

                <livewire:ai.assistant-chat />
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
