        <form method="POST"
          action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}">
          @csrf
          <input type="hidden" name="team_id" value="{{ $team->id }}" />
          <input type="hidden" name="formation_id" value="{{ $formation->id }}" />
          @if($formation->is_visible)
          <button type="submit" name="enabled" value="0"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 rounded-lg transition">
            {{ __('Désactiver') }}
          </button>
          @else
          <button type="submit" name="enabled" value="1"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition">
            {{ __('Activer') }}
          </button>
          @endif
        </form>