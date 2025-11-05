@php
    $usageConsumed = $usagePivot->usage_consumed ?? 0;
    $currentQuota = $usagePivot->usage_quota ?? max($usageConsumed, 1);
@endphp

@if($formation->is_visible)
  <div class="flex flex-col gap-3 w-full">
    <form
      method="POST"
      action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}"
      class="flex flex-col gap-2"
    >
      @csrf
      <input type="hidden" name="team_id" value="{{ $team->id }}" />
      <input type="hidden" name="formation_id" value="{{ $formation->id }}" />
      <input type="hidden" name="enabled" value="1" />

      <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">
        Quota total autorisé
      </label>
      <div class="flex items-center gap-2">
        <input
          type="number"
          name="usage_quota"
          min="{{ max($usageConsumed, 1) }}"
          value="{{ old('usage_quota', max($usageConsumed, $currentQuota)) }}"
          class="w-28 rounded-lg border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500"
          required
        />
        <button
          type="submit"
          class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition"
        >
          Mettre à jour
        </button>
      </div>
      <p class="text-xs text-slate-500">
        Minimum {{ $usageConsumed }} (utilisations déjà consommées).
      </p>
    </form>

    <form
      method="POST"
      action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}"
    >
      @csrf
      <input type="hidden" name="team_id" value="{{ $team->id }}" />
      <input type="hidden" name="formation_id" value="{{ $formation->id }}" />
      <input type="hidden" name="enabled" value="0" />
      <button
        type="submit"
        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition"
      >
        Désactiver
      </button>
    </form>
  </div>
@else
  <form
    method="POST"
    action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}"
    class="flex flex-col gap-2"
  >
    @csrf
    <input type="hidden" name="team_id" value="{{ $team->id }}" />
    <input type="hidden" name="formation_id" value="{{ $formation->id }}" />
    <input type="hidden" name="enabled" value="1" />

    <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">
      Nombre d'utilisations autorisées
    </label>
    <div class="flex items-center gap-2">
      <input
        type="number"
        name="usage_quota"
        min="1"
        value="{{ old('usage_quota', max($usageConsumed, 1)) }}"
        class="w-28 rounded-lg border-gray-300 text-sm focus:border-purple-500 focus:ring-purple-500"
        required
      />
      <button
        type="submit"
        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition"
      >
        Activer
      </button>
    </div>
    <p class="text-xs text-slate-500">
      Ce quota pourra être ajusté après activation.
    </p>
  </form>
@endif
