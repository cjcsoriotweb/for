<div class="divide-y divide-gray-200">
    @forelse($chapters as $chapter)
    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900">
                        <div wire:key="chapter-{{ $chapter->id }}">
                            @if($chapterEdition === $chapter->id)
                            <input
                                type="text"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                wire:model.defer="chaptersById.{{ $chapter->id }}"
                            />
                            <div class="mt-2 flex gap-2">
                                <button
                                    wire:click="saveChapter({{ $chapter->id }})"
                                    class="px-3 py-1 rounded-lg bg-blue-600 text-white"
                                >
                                    Enregistrer
                                </button>
                                <button
                                    wire:click="$set('chapterEdition', null)"
                                    class="px-3 py-1 rounded-lg bg-gray-100"
                                >
                                    Annuler
                                </button>
                            </div>
                            @else
                            {{ $chaptersById[$chapter->id] ?? $chapter->title }}
                            @endif
                        </div>
                    </h4>
                    {{-- … le reste de ton bloc (leçons, badges, etc.) --}}
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button
                    wire:click="editChapter({{ $chapter->id }})"
                    class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                    title="Modifier le chapitre"
                >
                    <span class="material-symbols-outlined text-sm">edit</span>
                </button>
                {{-- autres boutons --}}
            </div>
        </div>
        {{-- preview leçons … --}}
    </div>
    @empty
    {{-- état vide … --}}
    @endforelse
</div>
