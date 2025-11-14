@props(['formation'])

<div
  class="group relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/60 hover:border-blue-200/60 hover:-translate-y-2">
  <!-- Decorative layers -->
  <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-indigo-50/30 to-purple-50/50"></div>
  <div
    class="absolute inset-0 bg-gradient-to-r from-blue-500/3 via-transparent to-purple-500/3 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
  </div>

  <!-- Animated background elements -->
  <div
    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-full blur-2xl transform translate-x-16 -translate-y-16 group-hover:translate-x-12 group-hover:-translate-y-12 transition-transform duration-700">
  </div>
  <div
    class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-indigo-400/10 to-cyan-400/10 rounded-full blur-xl transform -translate-x-12 translate-y-12 group-hover:-translate-x-8 group-hover:translate-y-8 transition-transform duration-700">
  </div>

  <div class="relative z-10 h-44 w-full overflow-hidden border-b border-white/40 bg-white/50">
    <img
      src="{{ $formation->cover_image_url }}"
      alt="Image de couverture de {{ $formation->title }}"
      class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
      loading="lazy"
      onerror="this.src='{{ asset('images/formation-placeholder.svg') }}';"
    />
  </div>

  <div class="relative z-10 p-8">
    <div class="flex items-start justify-between mb-6">
      <div class="flex-1">
        <div class="flex items-center space-x-3 mb-3">
          <h3
            class="font-bold text-3xl text-gray-900 leading-tight group-hover:text-blue-900 transition-colors duration-300">
            {{ $formation->title }}
          </h3>
          <div class="flex items-center space-x-2">
            <span>
              @if($formation->user_id !== Auth::user()->id )
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border bg-red-100 text-red-800 border-red-200">
                  <div class="w-2 h-2 rounded-full mr-2 animate-pulse"></div>
                  {{ App\Models\User::find($formation->user_id)->name }}
                </span>
              @endif
            </span>
          </div>
        </div>
        <div class="flex items-center space-x-3 text-sm text-gray-500">
          <div class="flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $formation->card_created_label ?? '--' }}</span>
          </div>
          <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
          <div class="flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>{{ $formation->card_learners_count }} etudiants</span>
          </div>
        </div>
      </div>
      
    </div>

    <div>
       <a href="{{ route('formateur.formation.show', $formation) }}"
          class="inline-flex items-center px-6 py-3 bg-blue-600 mb-5 text-white font-semibold rounded-xl hover:bg-blue-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
          Voir les details
        </a>
    </div>


  </div>

  <div
    class="absolute inset-0 rounded-3xl ring-2 ring-blue-500/0 group-hover:ring-blue-500/30 transition-all duration-500 pointer-events-none">
  </div>
  <div
    class="absolute inset-0 rounded-3xl bg-gradient-to-r from-blue-600/5 via-purple-600/5 to-indigo-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
  </div>
</div>
