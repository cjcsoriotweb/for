<article
      class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
      <div class="space-y-3">
        <div class="flex items-center gap-3">
          <span
            class="inline-flex items-center justify-center size-8 rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">
            {{ $loop->iteration }}
          </span>
          <h3 class="text-lg font-semibold text-gray-900">
            {{ $formation->title }}
          </h3>
        </div>

        @if($formation->description)
        <p class="text-sm leading-relaxed text-gray-600">
          {{ $formation->description }}
        </p>
        @else
        <p class="text-sm italic text-gray-500">
          Aucune description renseignée pour le moment.
        </p>
        @endif

        <a
          href="{{ route('application.admin.formations.revenue', ['team' => $team, 'formation' => $formation]) }}"
          class="inline-flex items-center gap-3 rounded-lg border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 transition hover:-translate-y-0.5 hover:bg-purple-100 hover:shadow"
        >
          <span class="material-symbols-outlined text-lg text-purple-500">token</span>
          <span class="flex flex-col leading-tight">
            <span class="text-xs font-medium text-purple-500 uppercase tracking-widest">
              Revenu {{ $currentMonthLabel }}
            </span>
            <span class="text-base font-semibold text-purple-700">
              {{ number_format($monthlyRevenue->get($formation->id, 0), 0, ',', ' ') }} jetons
            </span>
          </span>
          <span class="material-symbols-outlined text-base text-purple-400">arrow_outward</span>
        </a>
      </div>

      <div class="flex items-center gap-3 md:self-stretch md:flex-col md:justify-between">
        <span
          class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $formation->is_visible ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }}">
          {{ $formation->is_visible ? 'Visible' : 'Masquée' }}
        </span>

        @include('in-application.admin.formations.parts.buttons.forms.edit-visibility-formation')
      </div>
    </article>