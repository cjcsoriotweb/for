{{-- Contenu texte --}}


@livewire('eleve.formation.readtext', ['requiredTime' =>
$lessonContent->estimated_read_time, 'team'=> $team, 'formation'=>$formation, 'lesson' => $lesson])

@php
    $attachments = $lessonContent->attachments ?? collect();
    $inlineAttachment = $attachments->firstWhere('display_mode', 'inline');
    $downloadAttachments = $attachments->where('display_mode', 'download');
    $isLessonCompleted = optional(optional($lessonProgress ?? null)->pivot)->status === 'completed';
@endphp

<div class="prose dark:prose-invert max-w-none mb-6">
  {!! nl2br(e($lessonContent->content)) !!}
</div>

@if($attachments->isNotEmpty())
  @if($isLessonCompleted)
    <details class="bg-gray-50 border border-gray-200 rounded-lg mb-6">
      <summary class="flex items-center justify-between cursor-pointer px-4 py-3 text-gray-800 font-semibold">
        <span>Ressources compl&eacute;mentaires</span>
        <span class="text-sm text-gray-500">Cliquer pour afficher</span>
      </summary>
      <div class="px-4 pb-4 pt-2 space-y-4">
        @if($inlineAttachment)
        <div>
          <h4 class="text-sm font-semibold text-gray-700 mb-2">Document principal</h4>
          <iframe
            src="{{ Storage::disk('public')->url($inlineAttachment->file_path) }}"
            title="Document de la le&ccedil;on"
            class="w-full h-[600px] rounded-lg border border-gray-200 shadow-sm"
          ></iframe>
        </div>
        @endif

        @if($downloadAttachments->isNotEmpty())
        <div>
          <h4 class="text-sm font-semibold text-gray-700 mb-2">Fichiers &agrave; t&eacute;l&eacute;charger</h4>
          <ul class="space-y-2">
            @foreach($downloadAttachments as $attachment)
            <li class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <span class="text-gray-700 break-all">
                {{ $attachment->name }}
              </span>
              <a
                href="{{ Storage::disk('public')->url($attachment->file_path) }}"
                target="_blank"
                class="mt-1 sm:mt-0 text-indigo-600 hover:text-indigo-800 font-medium"
              >
                T&eacute;l&eacute;charger
              </a>
            </li>
            @endforeach
          </ul>
        </div>
        @endif
      </div>
    </details>
  @else
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg px-4 py-3 mb-6">
      Terminez la le&ccedil;on pour d&eacute;bloquer les documents compl&eacute;mentaires.
    </div>
  @endif
@endif

{{-- Actions texte --}}
