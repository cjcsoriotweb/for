@props(['formations'])

<div class="mb-12">
  <div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-bold text-gray-900 flex items-center">
      <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
      </svg>
      Aperçu des formations
    </h2>
    
  </div>

  @if($formations->count() > 0)
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($formations->take(3) as $formation)
    <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200">

      <!-- Cover image -->
      <div class="relative h-32 overflow-hidden">
        <img src="{{ $formation->cover_image_url }}"
             alt="Image de couverture de {{ $formation->title }}"
             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
             loading="lazy"
             onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>

        
      </div>

      <!-- Content -->
      <div class="p-6">
        <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-900 transition-colors">
          {{ $formation->title }}
        </h3>

        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
          {{ $formation->card_description }}
        </p>

        <!-- Stats -->
        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
          <div class="flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>{{ $formation->card_learners_count }} étudiants</span>
          </div>
          <div class="flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span>{{ $formation->card_lessons_count }} Modules</span>
          </div>
        </div>



        <!-- Action button -->
        <a href="{{ route('formateur.formation.show', $formation) }}"
           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 text-sm">
          {{ __('Gérer cette formation') }}
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
      </div>

      <!-- Hover effect overlay -->
      <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-2xl"></div>
    </div>
    @endforeach
  </div>
  @else
  <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-blue-50/50 rounded-2xl border border-gray-100">
    <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
      <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
      </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune formation à mettre en avant</h3>
    <p class="text-gray-600 mb-6">Créez votre première formation pour commencer à former vos étudiants.</p>
    <a href="{{ route('formateur.formations.create') }}"
       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
      Créer ma première formation
    </a>
  </div>
  @endif
</div>
