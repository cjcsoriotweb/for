<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="flex items-center gap-3 text-sm text-slate-600">
                <a href="{{ route('formateur.formation.show', $formation) }}"
                    class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux param&egrave;tres
                </a>
                <span>/</span>
                <span>Preview apprenant</span>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 opacity-80">
                    </div>
                    <div class="relative px-8 py-10 text-white">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                            <div class="space-y-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-white/70">Preview apprenant</p>
                                <h1 class="text-3xl font-bold leading-tight">
                                    {{ $formation->title }}
                                </h1>
                                <p class="text-base text-white/80 max-w-3xl">
                                    {{ $formation->description ?: 'Aucune description disponible pour le moment.' }}
                                </p>
                            </div>
                            @if ($formation->cover_image_url)
                                <div class="flex items-center justify-center">
                                    <div class="w-24 h-24 rounded-2xl border border-white/20 overflow-hidden shadow-lg">
                                        <img src="{{ $formation->cover_image_url }}"
                                            alt="Image de couverture"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h2a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm14-1a1 1 0 011 1v16a1 1 0 01-1 1h-2a1 1 0 01-1-1V4a1 1 0 011-1h2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-white/70">Chapitres</p>
                                    <p class="text-xl font-semibold">{{ $formation->chapters->count() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h3m6-13h3a2 2 0 012 2v9a2 2 0 01-2 2h-3m-6 0h6m-6 0a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v11a2 2 0 01-2 2m-6 0a2 2 0 002 2h2a2 2 0 002-2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-white/70">Le&ccedil;ons</p>
                                    <p class="text-xl font-semibold">{{ $lessonCount }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 1M12 22a10 10 0 100-20 10 10 0 000 20z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-white/70">Temps estim&eacute;</p>
                                    @if ($formattedEstimatedDuration)
                                        <p class="text-xl font-semibold">{{ $formattedEstimatedDuration }}</p>
                                        <p class="text-xs text-white/60">{{ $totalDurationMinutes }} min cumul&eacute;s</p>
                                    @else
                                        <p class="text-xl font-semibold">&Agrave; d&eacute;finir</p>
                                        <p class="text-xs text-white/60">Ajoutez la dur&eacute;e &agrave; chaque le&ccedil;on</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="space-y-6">
                @forelse ($formation->chapters as $index => $chapter)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl font-semibold">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $chapter->title ?? 'Chapitre' }}</h3>
                                    <p class="text-sm text-slate-500">
                                        {{ $chapter->lessons->count() }} le&ccedil;on{{ $chapter->lessons->count() > 1 ? 's' : '' }}
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold tracking-widest uppercase text-slate-400">Preview only</span>
                        </div>

                        @if ($chapter->lessons->isEmpty())
                            <div class="p-6 text-sm text-slate-500">
                                Aucune le&ccedil;on n'est associ&eacute;e &agrave; ce chapitre.
                            </div>
                        @else
                            <div class="divide-y divide-slate-100">
                                @foreach ($chapter->lessons as $lesson)
                                    @php
                                        $lessonType = match ($lesson->lessonable_type) {
                                            \App\Models\VideoContent::class => ['label' => 'Vid&eacute;o', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14', 'color' => 'text-rose-600 bg-rose-50'],
                                            \App\Models\TextContent::class => ['label' => 'Texte', 'icon' => 'M19 21H5a2 2 0 01-2-2V7a2 2 0 012-2h4l2-2h4l2 2h4a2 2 0 012 2v12a2 2 0 01-2 2z', 'color' => 'text-indigo-600 bg-indigo-50'],
                                            \App\Models\Quiz::class => ['label' => 'Quiz', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'text-emerald-600 bg-emerald-50'],
                                            default => ['label' => 'Contenu', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16', 'color' => 'text-slate-600 bg-slate-100'],
                                        };

                                        $duration = null;
                                        if ($lesson->lessonable) {
                                            $duration = match ($lesson->lessonable_type) {
                                                \App\Models\VideoContent::class => $lesson->lessonable->duration_minutes,
                                                \App\Models\TextContent::class => $lesson->lessonable->estimated_read_time,
                                                \App\Models\Quiz::class => $lesson->lessonable->estimated_duration_minutes,
                                                default => null,
                                            };
                                        }
                                    @endphp
                                    <div class="p-6">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center {{ $lessonType['color'] }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="{{ $lessonType['icon'] }}" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ $lessonType['label'] }}</p>
                                                    <p class="text-base font-semibold text-slate-900">{{ $lesson->title }}</p>
                                                    @if ($lesson->summary)
                                                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $lesson->summary }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            @if ($duration)
                                                <div class="flex items-center gap-2 text-sm text-slate-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 1m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $duration }} min
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-slate-300">
                        <div class="mx-auto w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun contenu &agrave; pr&eacute;visualiser</h3>
                        <p class="text-sm text-slate-500">Ajoutez des chapitres et des le&ccedil;ons pour activer la preview.</p>
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>
