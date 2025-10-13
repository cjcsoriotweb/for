<div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition duration-300">
    <h3 class="text-2xl font-bold text-blue-600 mb-4">{{ $titre }}</h3>

    @if(isset($description))
    <p class="text-gray-600 mb-6">
        {{ $description }}
    </p>
    @endif

    @if(isset($image))
        <img src="{{ $image }}" alt="{{ $titre }}" class="w-full h-auto rounded-lg">
    @endif

    <a href="{{ $url }}"
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 inline-block">
        {{ $button ?? 'Accéder' }}
    </a>
</div>