{{-- Contenu vidéo --}}

@if($lessonContent->video_path)
<div class="aspect-video mb-4 bg-black rounded-lg overflow-hidden relative">
    <video
        controls
        controlsList="nodownload"
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
        <source
            src="{{ asset('storage/' . $lessonContent->video_path) }}"
            type="video/webm"
        />
        Votre navigateur ne supporte pas la lecture de vidéos.
    </video>

    {{-- Loader personnalisé --}}
    <div
        id="video-loader"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-75 hidden"
    >
        <div
            class="animate-spin rounded-full h-12 w-12 border-b-2 border-white"
        ></div>
    </div>

    {{-- Message d'erreur --}}
    <div
        id="video-error"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-75 hidden"
    >
        <div class="text-white text-center">
            <p class="mb-2">Erreur de chargement de la vidéo</p>
            <button
                onclick="retryVideo()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
                Réessayer
            </button>
        </div>
    </div>
</div>

{{-- Contrôles vidéo personnalisés --}}
@include('clean.eleve.lesson.partials.video-controls') @endif
@if($lessonContent->duration_minutes)
<div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
    Durée: {{ $lessonContent->duration_minutes }} minutes
</div>
@endif

{{-- Actions vidéo --}}
@include('clean.eleve.lesson.partials.lesson-actions')
