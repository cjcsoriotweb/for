{{-- Contenu video modernisé --}}
@php
    $hasDescription = $lessonContent && filled($lessonContent->description);
    $estimatedTime = $lessonContent->estimated_read_time ?? null;
@endphp

<div class="space-y-8 lg:space-y-10">
    {{-- En-tête visuel --}}
    <section
        class="relative overflow-hidden rounded-3xl border border-indigo-100/70 dark:border-indigo-900/40 bg-gradient-to-br from-white via-indigo-50/40 to-purple-50/60 dark:from-gray-900 dark:via-indigo-950/40 dark:to-purple-950/40 p-6 sm:p-8 shadow-lg"
    >
        <div class="absolute inset-0 opacity-20 dark:opacity-30 pointer-events-none">
            <div class="w-72 h-72 bg-white dark:bg-indigo-500/20 blur-3xl -top-24 -right-16 rounded-full absolute"></div>
            <div class="w-64 h-64 bg-purple-300/30 dark:bg-purple-900/40 blur-3xl -bottom-20 -left-10 rounded-full absolute"></div>
        </div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/70 dark:bg-gray-900/60 px-4 py-1 text-xs font-semibold tracking-[0.2em] uppercase text-indigo-600 dark:text-indigo-300 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('Leçon vidéo') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $lesson->getName() }}
                </h2>
          
            </div>

            <div class="flex flex-wrap gap-3 text-sm">
                <span class="inline-flex items-center gap-2 rounded-2xl bg-white/80 dark:bg-gray-900/70 px-4 py-2 text-gray-700 dark:text-gray-200 shadow">
                    <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2h2m10-5h-6a2 2 0 00-2 2v3h10V5a2 2 0 00-2-2z"></path>
                    </svg>
                    <span>{{ $chapter->title }}</span>
                </span>

                @if($estimatedTime)
                    <span class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600/10 text-indigo-700 dark:text-indigo-200 border border-indigo-200/80 dark:border-indigo-800 px-4 py-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $estimatedTime }} {{ __('min') }}
                    </span>
                @endif

       
            </div>
        </div>
    </section>

    {{-- Composant Livewire (plein écran sur mobile) --}}
    <div class="-mx-6 sm:-mx-8 lg:-mx-10">
        @livewire('eleve.video-player', [
            'team' => $team,
            'formation' => $formation,
            'chapter' => $chapter,
            'lesson' => $lesson,
            'lessonContent' => $lessonContent,
        ])
    </div>

    {{-- Informations complémentaires --}}
    <section class="rounded-3xl border border-gray-200/70 dark:border-gray-700/70 bg-white dark:bg-gray-900/70 shadow-lg p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-3">
                <div class="inline-flex items-center gap-2 text-xs font-semibold tracking-[0.3em] uppercase text-gray-500 dark:text-gray-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    {{ __('À propos de cette vidéo') }}
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    @if($hasDescription)
                        {{ $lessonContent->description }}
                    @else
                        {{ __('Cette leçon vidéo s’intègre à votre parcours de formation. Utilisez les contrôles du lecteur pour avancer en douceur, revoir un passage clé ou passer en plein écran selon vos besoins.') }}
                    @endif
                </h3>
            </div>
            <div class="space-y-4 rounded-2xl border border-indigo-100 dark:border-indigo-900/40 bg-gradient-to-br from-indigo-50/90 via-white to-purple-50/80 dark:from-indigo-950/20 dark:via-gray-900 dark:to-purple-950/30 p-5">
                <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-200 uppercase tracking-wide">{{ __('Conseils rapides') }}</p>
                <ul class="space-y-3 text-sm text-indigo-900/80 dark:text-indigo-100">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('Passez en plein écran pour une immersion totale.') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('Reprenez exactement là où vous avez arrêté grâce à la sauvegarde automatique.') }}
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        {{ __('Utilisez la barre de progression pour revoir facilement les passages importants.') }}
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>
