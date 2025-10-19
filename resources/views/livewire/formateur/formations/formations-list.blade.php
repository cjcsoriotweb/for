<div>
    @foreach ($formations as $formation)
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-2
                >
                {{ $formation->title }}
            </h3>
            <p class="text-gray-600 mb-4">
                {{ Str::limit($formation->description, 100) }}
            </p>
            <a
                href="{{ url('formateur.formations.show', $formation) }}"
                class="text-blue-500 hover:underline"
            >
                Voir les détails    
            </a>
        </div>
    </div>
    @endforeach
</div>
