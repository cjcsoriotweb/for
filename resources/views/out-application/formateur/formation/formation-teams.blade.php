<x-app-layout>
  <div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-6">
        <a href="{{ route('formateur.formation.show', $formation) }}"
          class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
          <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Retour a la formation
        </a>
      </div>

      <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
          <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
              <h1 class="text-2xl font-bold text-slate-900">Equipes rattachees</h1>
              <p class="text-sm text-slate-500">
                Liste complete des equipes qui utilisent "{{ $formation->title }}".
              </p>
            </div>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-600">
              {{ $formation->teams->count() }} equipe{{ $formation->teams->count() > 1 ? 's' : '' }}
            </span>
          </div>
        </div>

        <div class="px-6 py-6">
          @if ($formation->teams->isEmpty())
            <div class="rounded-lg border border-dashed border-slate-200 px-6 py-10 text-center">
              <p class="text-sm text-slate-500">Aucune equipe rattachee pour le moment.</p>
            </div>
          @else
            <ul class="space-y-4">
              @foreach ($formation->teams as $team)
                <li class="flex items-center justify-between rounded-lg border border-slate-100 px-4 py-3">
                  <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $team->name }}</p>
                    <span class="text-xs text-slate-500">Equipe rattachee</span>
                  </div>
                  <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                    ID #{{ $team->id }}
                  </span>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
