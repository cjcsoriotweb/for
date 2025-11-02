@props([
    'notifications' => 0,
    'enable' => true,
    'locked' => false,
])

@php
    use App\Models\AiTrainer;
    use App\Models\Formation;

    if (! $enable || ! auth()->check()) {
        return;
    }

    $trainers = AiTrainer::query()
        ->active()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    // Ajouter le trainer de la formation si contexte formation
    $currentRoute = request()->route();
    if ($currentRoute && str_contains($currentRoute->getName(), 'formation')) {
        $formationParam = $currentRoute->parameter('formation');
        $formation = null;
        if ($formationParam) {
            if (is_numeric($formationParam)) {
                $formation = Formation::find($formationParam);
            } elseif ($formationParam instanceof Formation) {
                $formation = $formationParam;
            } elseif (is_object($formationParam) && property_exists($formationParam, 'id')) {
                $formation = Formation::find($formationParam->id);
            } elseif (is_array($formationParam) && isset($formationParam['id'])) {
                $formation = Formation::find($formationParam['id']);
            }
        }
        if ($formation && $formation->primaryTrainer) {
            $trainers->prepend($formation->primaryTrainer);
        }
    }
    $trainers = $trainers->unique('id')->values();
@endphp


<div x-data="{ drawer: false, active: null }" class="fixed bottom-6 right-6 z-50">
    <!-- Bouton flottant -->
    <button @click="drawer = true" aria-label="Ouvrir l'assistant IA" class="w-16 h-16 rounded-full bg-indigo-600 text-white shadow-2xl flex items-center justify-center text-3xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" />
        </svg>
    </button>

    <!-- Drawer latéral -->
    <div x-show="drawer" x-cloak class="fixed inset-0 z-50 flex justify-end">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30" @click="drawer = false; active = null"></div>
        <!-- Panneau -->
        <div class="relative w-full max-w-xs h-full bg-white shadow-2xl flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b">
                <span class="font-bold text-lg text-indigo-700">Assistants IA</span>
                <button @click="drawer = false; active = null" class="text-gray-400 hover:text-red-500 text-2xl" aria-label="Fermer">&times;</button>
            </div>
            <template x-if="!active">
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @foreach ($trainers as $trainer)
                        <button @click="active = '{{ $trainer->slug }}'" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border border-indigo-100 hover:bg-indigo-50 focus:bg-indigo-100 transition group">
                            <span class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xl">{{ strtoupper(mb_substr($trainer->name,0,2)) }}</span>
                            <span class="flex flex-col items-start">
                                <span class="text-gray-900 font-semibold text-base group-hover:text-indigo-700">{{ $trainer->name }}</span>
                                @if(!empty($trainer->description))
                                    <span class="text-xs text-gray-500 mt-1">{{ $trainer->description }}</span>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>
            </template>
            @foreach ($trainers as $trainer)
                <template x-if="active === '{{ $trainer->slug }}'">
                    <div class="flex-1 flex flex-col">
                        <div class="flex items-center gap-2 px-5 py-3 border-b bg-indigo-50">
                            <button @click="active = null" class="text-indigo-600 hover:text-indigo-900 text-xl mr-2" aria-label="Retour">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                            <span class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">{{ strtoupper(mb_substr($trainer->name,0,2)) }}</span>
                            <span class="font-semibold text-indigo-900 text-lg">{{ $trainer->name }}</span>
                        </div>
                        <div class="flex-1 p-4 text-gray-700 overflow-y-auto">
                            <p class="italic">Ceci est une fausse chatbox pour test visuel.<br>Slug : <b>{{ $trainer->slug }}</b></p>
                        </div>
                        <div class="p-3 border-t">
                            <input type="text" class="w-full rounded-lg border px-3 py-2" placeholder="Tapez un message... (test)" />
                        </div>
                    </div>
                </template>
            @endforeach
        </div>
    </div>
</div>
