@props([])

<div class="flex items-center justify-between mb-12 pt-8">
  <div class="flex-1">
    <h1 class="text-4xl font-bold text-gray-900">Mes formations</h1>
    <p class="text-gray-600 mt-1 text-lg">Gérez vos formations et contenus pédagogiques</p>
  </div>
  <a href="{{ route('formateur.formations.create') }}"
    class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
    Créer une formation
  </a>
</div>