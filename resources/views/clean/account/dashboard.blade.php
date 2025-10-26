<x-app-layout>
  <!-- Full Height Container -->
  <div class="min-h-screen flex flex-col">
    <main class="flex-1 relative">
      <!-- Background gradient overlay -->
      <div
        class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 pointer-events-none">
      </div>
      <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="mb-16 text-center">
          <div
            class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg">
            <span class="material-symbols-outlined text-3xl text-white">auto_awesome</span>
          </div>
          <h1 class="mb-4 text-4xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
            {{ __("Bienvenue sur votre") }}
            <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
              {{ __("espace de formation") }}
            </span>
          </h1>
          <p class="mx-auto max-w-2xl text-lg text-slate-600 dark:text-slate-300">
            {{
            __(
            "Gérez vos formations et rejoignez de nouveaux organismes en toute simplicité"
            )
            }}
          </p>
        </div>
        @if ($organisations->count() > 0)
        <!-- Organizations Section -->
        <div class="mb-16">
          <div class="mb-8 text-center">
            <h2 class="mb-4 text-3xl font-bold text-slate-900 dark:text-white">
              {{ __("Vos organismes") }}
            </h2>
            <p class="text-slate-600 dark:text-slate-300">
              {{
              __(
              "Sélectionnez l'organisme pour accéder à votre espace de formation"
              )
              }}
            </p>
          </div>


          @foreach ($organisations as $application)
          <x-account.application.switch :application="$application" />
          @endforeach
        </div>
      </div>
      @else
      <!-- Empty State -->
      <div class="mb-16">
        <div
          class="mx-auto max-w-md rounded-2xl bg-white p-12 text-center shadow-lg ring-1 ring-slate-200 dark:bg-slate-800/50 dark:ring-slate-700">
          <div
            class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/20 dark:to-orange-900/20">
            <span class="material-symbols-outlined text-2xl text-amber-600 dark:text-amber-400">school</span>
          </div>
          <h3 class="mb-4 text-xl font-semibold text-slate-900 dark:text-white">
            {{ __("Aucun organisme") }}
          </h3>
          <p class="mb-6 text-slate-600 dark:text-slate-300">
            {{
            __(
            "Vous n'êtes actuellement rattaché à aucun organisme de formation."
            )
            }}
          </p>

          <!-- Email sharing section -->
          <div class="rounded-lg bg-slate-50 p-4 dark:bg-slate-700/50">
            <p class="mb-2 text-sm font-medium text-slate-700 dark:text-slate-300">
              {{ __("Votre adresse e-mail de connexion :") }}
            </p>
            <div class="flex items-center justify-between rounded-md bg-white px-3 py-2 dark:bg-slate-800">
              <span class="text-sm font-mono text-slate-900 dark:text-slate-100">{{ auth()->user()->email }}</span>
              <button onclick="navigator.clipboard.writeText('{{ auth()->user()->email }}')"
                class="flex h-6 w-6 items-center justify-center rounded text-slate-500 hover:bg-slate-100 hover:text-slate-700 dark:hover:bg-slate-600 dark:hover:text-slate-300">
                <span class="material-symbols-outlined text-sm">content_copy</span>
              </button>
            </div>
            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
              {{
              __(
              "Partagez cette adresse avec un organisme pour rejoindre leur équipe"
              )
              }}
            </p>
          </div>
        </div>
      </div>
      @endif
      <!-- Superadmin -->
      @if(Auth::user()->superadmin)
      <div class="mb-8 text-center">
        <h2 class="mb-4 text-3xl font-bold text-slate-900 dark:text-white">
          {{ __("Organisations") }}
        </h2>
        <p class="text-slate-600 dark:text-slate-300">
          {{ __("Gérez les organismes de formation") }}
        </p>

        <!-- Button to create team -->
        <div class="mt-6 flex justify-center">
          <a href="{{ route('teams.create') }}"
            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            {{ __("Créer une application") }}
          </a>
        </div>
      </div>
      @endif

      <!-- Invitation Component -->
      <x-account.application.invitation />
  </div>
  </main>
  </div>
</x-app-layout>