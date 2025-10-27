{{-- Documents de la formation --}}
<details class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <summary class="flex items-center justify-between cursor-pointer px-6 py-4 text-gray-900 dark:text-gray-100 font-semibold">
        <span>Documents de la formation</span>
        <span class="text-sm text-gray-500 dark:text-gray-400">Cliquer pour {{ $isFormationCompleted ? 'afficher' : 'voir les conditions' }}</span>
    </summary>

    <div class="px-6 pb-6">
        @if(! $isFormationCompleted)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg px-4 py-3">
                Terminez la formation pour d&eacute;bloquer l'acc&egrave;s aux documents finaux.
            </div>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($formationDocuments as $document)
                    <li class="py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex-1 pr-0 sm:pr-4">
                            <p class="text-gray-900 dark:text-gray-100 font-medium break-words">
                                {{ $document->title ?? $document->original_name }}
                            </p>
                            @if($document->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $document->description }}
                                </p>
                            @endif
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <a
                                href="{{ route('eleve.formation.documents.download', [$team, $formation, $document]) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors"
                            >
                                T&eacute;l&eacute;charger
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</details>
