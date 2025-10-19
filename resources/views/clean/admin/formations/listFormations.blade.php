<div class="space-y-6">
    <!-- Formations List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <div class="space-y-4">
                @foreach($formations as $formation)
                <!-- Formation Admin,Partials,Admin-formations -->
                <div
                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                >
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{$formation->title}}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{$formation->description}}
                        </p>
                        <br />
                        @if(Auth::user()->superadmin())
                        <a href="">Editer</a>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        <form
                            method="POST"
                            action="{{ route('application.admin.formations.editVisibility', ['team' => $team->id, 'formation' => $formation->id]) }}"
                        >
                            @csrf
                            <input
                                type="hidden"
                                name="team_id"
                                value="{{ $team->id }}"
                            />
                            <input
                                type="hidden"
                                name="formation_id"
                                value="{{ $formation->id }}"
                            />
                            @if($formation->is_visible)
                            <button
                                type="submit"
                                name="enabled"
                                value="0"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                            >
                                {{ __("Actuellement activé") }}
                            </button>
                            @else
                            <button
                                type="submit"
                                name="enabled"
                                value="1"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                            >
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
