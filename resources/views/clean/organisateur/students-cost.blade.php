<x-organisateur-layout :team="$team">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-8">
      <nav class="mb-6 flex" aria-label="Fil d'Ariane">
        <ol class="flex items-center space-x-4">
          <li>
            <a href="{{ route('organisateur.index', $team) }}"
              class="group flex items-center gap-2 text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
              <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 01-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0"></path>
              </svg>
              Formations
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
                class="ml-4 text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                {{ $formation->title }}
              </a>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                viewBox="0 0 20 20" aria-hidden="true">
                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
              </svg>
              <span class="ml-4 text-gray-700 dark:text-gray-200">Coût mensuel</span>
            </div>
          </li>
        </ol>
      </nav>

      <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Coût des inscriptions</h1>
          <p class="mt-2 text-gray-600 dark:text-gray-400">
            Aperçu détaillé des inscriptions de {{ $formation->title }} pour la période sélectionnée.
          </p>
        </div>
        <a href="{{ route('organisateur.formations.students', [$team, $formation]) }}"
          class="group inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
          <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
          </svg>
          Retour aux élèves
        </a>
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
            <p class="text-3xl font-bold text-gray-900 dark:text-white">
              {{ number_format($monthlyCost, 0, ',', ' ') }} €
            </p>
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
    <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800">
      <form method="GET" class="grid gap-6 md:grid-cols-3">
        <div class="space-y-2">
          <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mois</label>
          <select id="month" name="month"
            class="block w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            @foreach($availableMonths as $month)
            <option value="{{ $month }}" @selected($selectedMonth === $month)>
              {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}
            </option>
            @endforeach
          </select>
        </div>

        <div class="flex items-end">
          <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z">
              </path>
            </svg>
            Filtrer
          </button>
        </div>
      </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-lg dark:border-gray-700">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Élève
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
              Email</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
              Date d'inscription</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">
              Coût</th>
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
              {{ number_format($student->pivot->enrollment_cost ?? $formation->money_amount ?? 0, 0, ',', ' ') }} €
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
