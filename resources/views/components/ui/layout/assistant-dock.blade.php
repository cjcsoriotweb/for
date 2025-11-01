<x-dock>
  <x-dock-slot>

    <x-slot:chatia>
      <div class="h-full flex flex-col bg-white dark:bg-slate-900">
        @livewire('chat-box', [
          'trainer' => 'default',
          'title' => __('Assistant IA'),
          'isOpen' => true,
        ], key('dock-chat-box'))
      </div>
    </x-slot:chatia>

    <x-slot:tutor>
      <div class="h-full overflow-y-auto bg-gradient-to-b from-amber-50 to-amber-100/60 dark:from-slate-900 dark:to-slate-950 px-6 py-8">
        <div class="mx-auto max-w-3xl">
          <div class="rounded-3xl border border-amber-200/70 bg-white/95 p-8 shadow-2xl shadow-amber-500/10 dark:border-amber-500/40 dark:bg-slate-900/90">
            <div class="text-center">
              <svg class="mx-auto mb-6 h-16 w-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 10s-1 .5-2 0-1.5-1.5-1.5-1.5"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 10s1 .5 2 0 1.5-1.5 1.5-1.5"></path>
              </svg>
              <h2 class="text-3xl font-semibold text-amber-600 dark:text-amber-200">{{ __('Professeur virtuel') }}</h2>
              <p class="mt-3 text-base text-amber-700/80 dark:text-amber-100/80">
                {{ __('Votre professeur personnel pour vous accompagner dans vos apprentissages arrivera bientot.') }}
              </p>
            </div>

            <div class="mt-8 space-y-4 text-left text-sm text-amber-900/80 dark:text-amber-100/80">
              <p>{{ __('Nous preparons une experience complete melant coaching, ressources ciblees et suivi personnalise.') }}</p>
              <p>{{ __('Vous serez notifie des que le professeur virtuel sera disponible dans votre espace.') }}</p>
            </div>
          </div>
        </div>
      </div>
    </x-slot:tutor>

    <x-slot:support>
      <div class="h-full flex flex-col">
        @livewire('support.ticket-reporter', [
          'originPath' => request()->fullUrl(),
          'originLabel' => __('Dock Signaler un bug'),
        ], key('dock-support'))
      </div>
    </x-slot:support>

    <x-slot:search>
      <div class="h-full overflow-y-auto bg-gradient-to-b from-indigo-50 to-white px-6 py-8 dark:from-slate-900 dark:to-slate-950">
        <div class="mx-auto max-w-3xl">
          <div class="rounded-3xl border border-indigo-200/60 bg-white/95 p-8 shadow-2xl shadow-indigo-500/10 dark:border-indigo-500/40 dark:bg-slate-900/90">
            <div class="text-center">
              <svg class="mx-auto mb-6 h-16 w-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7" stroke-width="1.6"></circle>
                <path stroke-linecap="round" stroke-width="1.6" d="M21 21l-4.35-4.35"></path>
              </svg>
              <h2 class="text-3xl font-semibold text-indigo-600 dark:text-indigo-200">{{ __('Recherche avancee') }}</h2>
              <p class="mt-3 text-base text-indigo-700/80 dark:text-indigo-100/80">
                {{ __('Trouvez rapidement du contenu, des formations et des ressources au sein de votre plateforme.') }}
              </p>
            </div>

            <div class="mt-8 grid gap-4 text-left text-sm text-indigo-900/80 dark:text-indigo-100/80">
              <p>{{ __('Une recherche intelligente arrive bientot pour vous aider a naviguer et filtrer en quelques secondes.') }}</p>
              <p>{{ __('En attendant, utilisez l assistant IA pour obtenir des reponses ciblees sur vos contenus.') }}</p>
            </div>
          </div>
        </div>
      </div>
    </x-slot:search>

  </x-dock-slot>
</x-dock>
