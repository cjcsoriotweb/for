{{-- Contenu vidéo --}}
@if($lessonContent->video_path)
<div class="aspect-video mb-4 bg-black rounded-lg overflow-hidden">
    <video
        controls
        class="w-full h-full"
        id="lesson-video"
        preload="metadata"
        poster="{{ asset('images/video-poster.jpg') }}"
        oncontextmenu="return false;"
    >
        <source
            src="{{ asset('storage/' . $lessonContent->video_path) }}"
            type="video/mp4"
        />
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>
</div>
@endif @if($lessonContent->duration_minutes)
<div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
    Durée: {{ $lessonContent->duration_minutes }} minutes
</div>
@endif

{{-- Composant Livewire pour la gestion vidéo --}}
@livewire('eleve.video-player', [ 'team' => $team, 'formation' => $formation,
'chapter' => $chapter, 'lesson' => $lesson, 'lessonContent' => $lessonContent ],
key('video-player-' . $lesson->id))
