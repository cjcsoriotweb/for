<section class="bg-gradient-to-br mb-10 from-primary via-blue-600 to-blue-700 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col md:flex-row items-center justify-between">
      <div class="mb-8 md:mb-0">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">
          Bonjour {{ Auth::user()->name }} ! 👋
        </h1>
        <p class="text-xl text-blue-100 mb-6">
          Prêt à continuer votre apprentissage ?
        </p>
        <div class="flex items-center space-x-6">
          <div class="text-center">
            <div class="text-2xl font-bold ">
              {{ count($currentFormation ?? []) }}
            </div>
            <div
              class="text-sm text-blue-200 bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">
              Formations en cours
            </div>
          </div>
        </div>
      </div>
      <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
        <img src="{{ $team->profile_photo_url }}">
      </div>
    </div>
  </div>
</section>
