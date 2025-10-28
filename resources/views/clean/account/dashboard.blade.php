<x-app-layout>
  <!-- Full Height Container -->
  <div class="min-h-screen flex flex-col">
    <main class="flex-1 relative">
      <!-- Background gradient overlay -->

      <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div
          class="relative mb-16 overflow-hidden rounded-3xl border border-slate-200/70 bg-white/80 p-12 text-center shadow-xl shadow-slate-200/60 backdrop-blur-sm dark:border-slate-700/60 dark:bg-slate-900/40 dark:shadow-none">
          <div
            class="pointer-events-none absolute inset-0 bg-gradient-to-br from-blue-500/10 via-purple-500/10 to-indigo-500/10">
          </div>
          <div
            class="pointer-events-none absolute -right-24 -top-20 h-56 w-56 rounded-full bg-blue-400/20 blur-3xl dark:bg-blue-400/10">
          </div>
          <div class="relative">
            <div
              class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-lg shadow-blue-500/30">
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
              "Gérez vos formations, suivez vos parcours et rejoignez de nouveaux organismes en toute simplicité."
              )
              }}
            </p>
            <div
              class="mt-8 flex flex-wrap justify-center gap-4 text-sm font-medium text-slate-600 dark:text-slate-300">
              <span
                class="inline-flex items-center gap-2 rounded-full bg-slate-100/80 px-4 py-2 backdrop-blur-sm dark:bg-slate-800/70">
                <span class="material-symbols-outlined text-base text-blue-500 dark:text-blue-300">timeline</span>
                {{ __("Suivi en temps réel") }}
              </span>
              <span
                class="inline-flex items-center gap-2 rounded-full bg-slate-100/80 px-4 py-2 backdrop-blur-sm dark:bg-slate-800/70">
                <span
                  class="material-symbols-outlined text-base text-purple-500 dark:text-purple-300">workspace_premium</span>
                {{ __("Collaboration simplifiée") }}
              </span>
            </div>
          </div>
        </div>
        @if ($organisations->count() > 0)
        <!-- Organizations Section -->
        <div class="mb-16">


          <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($organisations as $application)
            <x-account.application.switch :application="$application" />
            @endforeach
          </div>
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
              <button type="button" data-copy-text="{{ auth()->user()->email }}"
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

      <!-- Invitation Component -->
      <x-account.application.invitation />
  </div>
  </main>
  </div>
</x-app-layout>
