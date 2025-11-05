<article
  class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between p-6 bg-white border border-gray-200 rounded-xl shadow-sm">
  <div class="space-y-3">
    @php
      $usageQuota = $usagePivot->usage_quota ?? null;
      $usageConsumed = $usagePivot->usage_consumed ?? 0;
      $usageRemaining = is_null($usageQuota) ? null : max($usageQuota - $usageConsumed, 0);
    @endphp
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

    <div
      class="inline-flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-700">
      <span class="material-symbols-outlined text-lg text-purple-500">counter_3</span>
      <span class="flex flex-col leading-tight">
        <span class="text-xs font-medium text-slate-500 uppercase tracking-widest">
          Gestion des utilisations
        </span>
        @if($formation->is_visible)
          @if(is_null($usageQuota))
            <span class="text-base font-semibold text-slate-800">
              Utilisation illimitée
            </span>
          @else
            <span class="text-base font-semibold text-slate-800">
              {{ $usageRemaining }} restant{{ $usageRemaining === 1 ? '' : 's' }}
              <span class="text-xs text-slate-500">
                / {{ $usageQuota }} autorisée{{ $usageQuota === 1 ? '' : 's' }}
              </span>
            </span>
          @endif
          <span class="text-xs text-slate-500">
            {{ $usageConsumed }} utilisation{{ $usageConsumed === 1 ? '' : 's' }} consommée{{ $usageConsumed === 1 ? '' : 's' }}
          </span>
        @else
          <span class="text-base font-semibold text-slate-800">
            Formation non activée
          </span>
        @endif
      </span>
    </div>
  </div>

  <div class="flex items-center gap-3 md:self-stretch md:flex-col md:justify-between">
    <span
      class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $formation->is_visible ? 'bg-green-100 text-green-700' : 'bg-rose-100 text-rose-700' }}">
      {{ $formation->is_visible ? 'Visible' : 'Masquée' }}
    </span>

    @include('in-application.admin.formations.parts.buttons.forms.edit-visibility-formation', [
        'usagePivot' => $usagePivot,
    ])
  </div>
</article>
