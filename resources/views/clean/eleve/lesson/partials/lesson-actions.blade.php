{{-- Actions de la leçon --}}
<div class="flex justify-end items-center">
    <div class="flex space-x-2">
        {{-- Actions communes aux leçons texte et vidéo --}}
        <form
            method="POST"
            action="{{
                route('eleve.lesson.complete', [
                    $team,
                    $formation,
                    $chapter,
                    $lesson
                ])
            }}"
            class="inline"
        >
            @csrf
            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                @if($lessonType === 'video') Marquer comme terminée @else
                Marquer comme lue @endif
            </button>
        </form>

        {{-- Actions spécifiques au contenu texte --}}
        @if($lessonType === 'text' && $lessonContent->allow_download)
        <a
            href="{{
                route('eleve.lesson.download', [
                    $team,
                    $formation,
                    $chapter,
                    $lesson
                ])
            }}"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-block"
        >
            Télécharger
        </a>
        @endif
    </div>
</div>
