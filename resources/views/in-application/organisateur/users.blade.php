<x-organisateur-layout :team="$team">

  {{-- Header --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
          Gérer les utilisateurs
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400">
          Invitez de nouveaux membres et gérez les rôles de votre équipe
        </p>
      </div>
      <div class="hidden md:block">
        <div class="relative">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full blur opacity-30">
          </div>
          <div class="relative bg-gradient-to-r from-blue-500 to-indigo-600 p-4 rounded-full">
            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
              </path>
            </svg>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Team Member Manager --}}
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
      @livewire('teams.team-member-manager', ['team' => $team])
    </div>
  </div>

</x-organisateur-layout>