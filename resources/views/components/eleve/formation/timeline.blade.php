@props(['formation'])

@php
    use App\Models\Quiz;
    use App\Models\TextContent;
    use App\Models\VideoContent;

    $chapters = $formation->chapters ?? collect();
@endphp

<div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200/60 dark:border-gray-700/60">
    <div class="px-6 py-5 border-b border-gray-200/60 dark:border-gray-700/60">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-500 dark:text-gray-400 mb-1">
            Parcours
        </p>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Timeline du parcours
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
            Visualisez les étapes qui jalonnent la formation et suivez facilement où vous en êtes.
        </p>
    </div>

    <div class="px-6 py-6">
        @if($chapters->isEmpty())
            <div class="text-sm text-center text-gray-500 dark:text-gray-400">
                Aucun chapitre défini pour le moment.
            </div>
        @else
            <div class="space-y-8">
                @foreach($chapters as $index => $chapter)
                    @php
                        $lessonCount = $chapter->lessons ? $chapter->lessons->count() : 0;
                        $statusLabel = 'Verrouillé';
                        $statusBadgeClasses = 'text-gray-500 bg-gray-100/80 dark:text-gray-400 dark:bg-gray-800/70';
                        $iconWrapperClasses = 'bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700';
                        $iconTextClasses = 'text-gray-600 dark:text-gray-300';
                        $iconType = 'lock';

                        if ($chapter->is_completed) {
                            $statusLabel = 'Terminé';
                            $statusBadgeClasses = 'text-emerald-700 bg-emerald-100/80 dark:text-emerald-300 dark:bg-emerald-900/50';
                            $iconWrapperClasses = 'bg-emerald-100/80 border border-emerald-300 dark:bg-emerald-900/40 dark:border-emerald-500';
                            $iconTextClasses = 'text-emerald-700 dark:text-emerald-200';
                            $iconType = 'check';
                        } elseif ($chapter->is_current) {
                            $statusLabel = 'En cours';
                            $statusBadgeClasses = 'text-indigo-700 bg-indigo-100/80 dark:text-indigo-300 dark:bg-indigo-900/50';
                            $iconWrapperClasses = 'bg-indigo-100/80 border border-indigo-300 dark:bg-indigo-900/40 dark:border-indigo-500';
                            $iconTextClasses = 'text-indigo-700 dark:text-indigo-200';
                            $iconType = 'play';
                        } elseif ($chapter->is_accessible) {
                            $statusLabel = 'Disponible';
                            $statusBadgeClasses = 'text-sky-700 bg-sky-100/80 dark:text-sky-300 dark:bg-sky-900/40';
                            $iconWrapperClasses = 'bg-sky-100/80 border border-sky-300 dark:bg-sky-900/40 dark:border-sky-500';
                            $iconTextClasses = 'text-sky-700 dark:text-sky-200';
                            $iconType = 'dot';
                        }

                        $statusDescription = match (true) {
                            $chapter->is_completed => 'Chapitre déjà terminé',
                            $chapter->is_current => 'Chapitre en cours',
                            $chapter->is_accessible => 'Chapitre accessible',
                            default => 'Chapitre verrouillé',
                        };
                    @endphp

                    <div class="flex items-start gap-4 sm:gap-6">
                        <div class="flex flex-col items-center">
                            <div class="flex items-center justify-center w-11 h-11 rounded-full border-2 {{ $iconWrapperClasses }}">
                                @switch($iconType)
                                    @case('check')
                                        <svg class="w-5 h-5 {{ $iconTextClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        @break
                                    @case('play')
                                        <svg class="w-5 h-5 {{ $iconTextClasses }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6 4l10 6-10 6V4z"></path>
                                        </svg>
                                        @break
                                    @case('dot')
                                        <span class="inline-flex items-center justify-center w-3 h-3 rounded-full bg-current {{ $iconTextClasses }}"></span>
                                        @break
                                    @default
                                        <svg class="w-5 h-5 {{ $iconTextClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 4h.01M5.5 5a8 8 0 0113 5.5c0 4.418-3.582 8-8 8s-8-3.582-8-8A8 8 0 015.5 5zM6 10h12"></path>
                                        </svg>
                                @endswitch
                            </div>
                            @if(! $loop->last)
                                <span class="mt-2 block w-px flex-1 bg-gradient-to-b from-transparent via-gray-300 to-transparent dark:via-gray-700"></span>
                            @endif
                        </div>

                        <div class="flex-1 space-y-1">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="text-[11px] tracking-[0.3em] uppercase text-gray-500 dark:text-gray-400">
                                    Chapitre {{ $chapter->position ?? ($index + 1) }}
                                </span>
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $statusBadgeClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $chapter->title ?: 'Chapitre ' . ($chapter->position ?? ($index + 1)) }}
                            </h4>

                            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ $lessonCount }} leçon{{ $lessonCount > 1 ? 's' : '' }} · {{ $statusDescription }}
                            </p>

                            @php
                                $chapterLessons = $chapter->lessons ?? collect();
                            @endphp

                            @if($chapterLessons->isNotEmpty())
                                <div class="mt-3 space-y-2 border-t border-gray-100/80 dark:border-gray-800/60 pt-3">
                                    <ul class="space-y-2">
                                        @foreach($chapterLessons as $lesson)
                                            @php
                                                $lessonTypeLabel = match ($lesson->lessonable_type) {
                                                    VideoContent::class => 'Vidéo',
                                                    TextContent::class => 'Texte',
                                                    Quiz::class => 'Quiz',
                                                    default => class_basename($lesson->lessonable_type ?? '') ?: 'Leçon',
                                                };

                                                $lessonStatusLabel = 'À venir';
                                                $lessonStatusClasses = 'text-gray-500 bg-gray-100/70 dark:text-gray-400 dark:bg-gray-800/60';
                                                $lessonDotClasses = 'bg-gray-300 dark:bg-gray-600';

                                                if ($lesson->is_completed ?? false) {
                                                    $lessonStatusLabel = 'Terminé';
                                                    $lessonStatusClasses = 'text-emerald-700 bg-emerald-100/90 dark:text-emerald-300 dark:bg-emerald-900/40';
                                                    $lessonDotClasses = 'bg-emerald-500';
                                                } elseif ($lesson->is_current ?? false) {
                                                    $lessonStatusLabel = 'En cours';
                                                    $lessonStatusClasses = 'text-indigo-700 bg-indigo-100/80 dark:text-indigo-300 dark:bg-indigo-900/40';
                                                    $lessonDotClasses = 'bg-indigo-500';
                                                } elseif ($lesson->is_accessible ?? false) {
                                                    $lessonStatusLabel = 'Disponible';
                                                    $lessonStatusClasses = 'text-sky-700 bg-sky-100/80 dark:text-sky-300 dark:bg-sky-900/40';
                                                    $lessonDotClasses = 'bg-sky-500';
                                                }
                                            @endphp

                                            <li class="flex items-center justify-between gap-3 text-sm text-gray-800 dark:text-gray-100">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex h-2 w-2 rounded-full {{ $lessonDotClasses }}"></span>
                                                    <span class="font-medium">{{ $lesson->getName() }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded-full text-[11px] uppercase tracking-[0.2em] text-gray-600 dark:text-gray-300 bg-gray-100/70 dark:bg-gray-800/60">
                                                        {{ $lessonTypeLabel }}
                                                    </span>
                                                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $lessonStatusClasses }}">
                                                        {{ $lessonStatusLabel }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
