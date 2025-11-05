<x-organisateur-layout :team="$team">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <x-organisateur.parts.breadcrumb :team="$team" :formation="$formation" currentPage="Coût mensuel" />

      <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Coût des inscriptions</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Aperçu détaillé des inscriptions de {{ $formation->title }} pour la période sélectionnée.
          </p>
        </div>

        <x-organisateur.parts.action-buttons :buttons="[
          ['type' => 'back', 'url' => route('organisateur.formations.students', [$team, $formation]), 'text' => 'Retour aux élèves']
        ]" />
      </div>
    </div>

    {{-- Summary --}}
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
      <div
        class="relative overflow-hidden rounded-xl bg-gradient-to-br from-purple-50 to-pink-100 p-6 shadow-lg transition hover:shadow-xl dark:from-gray-800 dark:to-gray-900">
        <div
          class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 opacity-0 transition-opacity hover:opacity-100">
        </div>
        <div class="relative flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Coût total</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($monthlyCost, 0, ',', ' ') }} €</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Système de tarification désactivé</p>
          </div>
          <div
            class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
          Du {{ $periodStart->translatedFormat('d F Y') }} au {{ $periodEnd->translatedFormat('d F Y') }}.
        </p>
      </div>

      <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inscriptions sur la période</p>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $enrollments->count() }}</p>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nombre d'élèves inscrits durant la période.</p>
      </div>
    </div>

    {{-- Filters --}}
    <x-organisateur.parts.filters :selectedMonth="isset($selectedMonth) ? $selectedMonth : null"
      :availableMonths="$availableMonths" routeName="organisateur.formations.students.cost"
      :routeParams="[$team, $formation]" />

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-lg dark:border-gray-700">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Élève
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date
              d'inscription</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Coût
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
          @forelse($enrollments as $student)
          <tr>
            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->name }}</td>
            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</td>
            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
              {{ optional(\Carbon\Carbon::make($student->pivot->enrolled_at))->format('d/m/Y') ?? 'Non renseigné' }}
            </td>
            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
              0 €
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
              Aucune inscription enregistrée pour cette période.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-organisateur-layout>
