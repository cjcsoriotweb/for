{{-- Actions de la le&ccedil;on --}}
<div class="flex justify-end items-center">
    <div class="flex space-x-2">
        @php
            $downloadableAttachments = collect();
            if ($lessonType === 'text') {
                $downloadableAttachments = ($lessonContent->attachments ?? collect())->where('display_mode', 'download');
            }
            $isLessonCompleted = optional(optional($lessonProgress ?? null)->pivot)->status === 'completed';
            $canDownloadResources = $lessonType === 'text'
                && $isLessonCompleted
                && ($lessonContent->allow_download || $downloadableAttachments->isNotEmpty());
        @endphp
        {{-- Actions communes aux le&ccedil;ons texte et vid&eacute;o --}}
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
                @if($lessonType === 'video') Marquer comme termin&eacute;e @else
                Marquer comme lue @endif
            </button>
        </form>

        {{-- Actions sp&eacute;cifiques au contenu texte --}}
        @if($canDownloadResources)
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
            T&eacute;l&eacute;charger tout
        </a>
        @elseif($lessonType === 'text' && ($lessonContent->allow_download || $downloadableAttachments->isNotEmpty()))
        <span
            class="bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-block cursor-not-allowed"
            title="Terminez la le&ccedil;on pour t&eacute;l&eacute;charger les ressources"
        >
            T&eacute;l&eacute;charger tout
        </span>
        @endif
    </div>
</div>
