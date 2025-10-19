<form
    action="{{ route('application.admin.formations.store', ['team' => $team->id]) }}"
    method="POST"
>
    @csrf
    <p>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
            Titre de la formation
        </label>
    </p>
    <input
        name="formation[title]"
        type="text"
        class="border @error('formation.title') border-red-300 @enderror rounded-md p-2 w-full"
    />
    <p>
        <label
            for="description"
            class="block text-sm font-medium text-gray-700 mb-1"
        >
            Description de la formation
        </label>
    </p>
    <input
        name="formation[description]"
        type="text"
        class="border @error('formation.description') border-red-300 @enderror rounded-md p-2 w-full"
    />

    <div class="mt-4">
        <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
        >
            Créer la formation
        </button>
    </div>
</form>
