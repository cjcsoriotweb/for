<x-app-layout>
    <!-- Formation Details -->
    @if($errors->any())
    <div
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
        role="alert"
    >
        <strong class="font-bold">Erreur!</strong>
        <span class="block sm:inline"
            >Veuillez corriger les erreurs suivantes:</span
        >
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Édition du Chapitre {{ $chapter->position }}:
                        {{ $chapter->title }}
                    </h2>

                    <form
                        action="{{
                            route('formateur.formation.chapter.update.put', [
                                $formation,
                                $chapter
                            ])
                        }}"
                        method="POST"
                    >
                        @csrf @method('PUT')

                        <div class="mb-4">
                            <label
                                for="title"
                                class="block text-gray-700 font-medium mb-2"
                                >Titre du Chapitre</label
                            >
                            <input
                                type="text"
                                name="title"
                                id="title"
                                value="{{ old('title', $chapter->title) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>

                        <div class="mb-4">
                            <label
                                for="position"
                                class="block text-gray-700 font-medium mb-2"
                                >Position</label
                            >
                            <input
                                type="number"
                                name="position"
                                id="position"
                                value="{{ old('position', $chapter->position) }}"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                        >
                            Enregistrer les modifications
                        </button>
                    </form>
                    @if(request()->has('delete'))
                    <form
                        action="{{
                            route('formateur.formation.chapter.delete.post', [
                                $formation,
                                $chapter
                            ])
                        }}"
                        method="post"
                    >
                        @method('POST') @csrf
                        <button
                            type="submit"
                            class="mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
                        >
                            <input
                                type="hidden"
                                name="chapter_id"
                                value="{{ $chapter->id }}"
                            />
                            {{ __("Confirmer la suppression") }}
                        </button>
                    </form>
                    @else
                    <a
                        class="inline-block my-2 py-2 px-4 rounded-lg transition duration-200 bg-red-500 text-white hover:text-red-700"
                        href="?delete=1"
                        >{{ __("Supprimer") }}</a
                    >
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
