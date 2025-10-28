<div class="space-y-6">


  <a href="{{
                        route('formateur.home', ['team' => $team])
                    }}"
    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl shadow-sm transition-colors">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
      class="size-6">
      <path stroke-linecap="round" stroke-linejoin="round"
        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
    </svg>

    Créer/ Editer des formations
  </a>
  <!-- Formations List -->
  <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6">
      <div class="space-y-4">
        @foreach($formations as $formation)
        <!-- Formation Admin,Partials,Admin-formations -->
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-900">
              {{$formation->title}}
            </h3>
            <p class="text-sm text-gray-600 mt-1">
              {{$formation->description}}
            </p>
            <br />
          </div>

          <div class="flex space-x-2">
            <form method="POST"
              action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}">
              @csrf
              <input type="hidden" name="team_id" value="{{ $team->id }}" />
              <input type="hidden" name="formation_id" value="{{ $formation->id }}" />
              @if($formation->is_visible)
              <button type="submit" name="enabled" value="0"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                {{ __("Actuellement activé") }}
              </button>
              @else
              <button type="submit" name="enabled" value="1"
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                {{ __("Actuellement désactivé") }}
              </button>
              @endif
            </form>
          </div>
        </div>

        @endforeach
      </div>
    </div>
  </div>
</div>