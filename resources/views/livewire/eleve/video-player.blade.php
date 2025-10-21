<div>
    {{-- Indicateurs de débogage visuel --}}
    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">
            🔍 État du lecteur vidéo Livewire :
        </h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-medium">Composant Livewire :</span>
                <span class="text-green-600">✅ Oui</span>
            </div>
            <div>
                <span class="font-medium">Données reçues :</span>
                <span class="text-green-600">✅ Oui</span>
            </div>
            <div>
                <span class="font-medium">Temps actuel :</span>
                <span class="text-gray-600">{{ $currentTime }}</span>
            </div>
            <div>
                <span class="font-medium">Durée totale :</span>
                <span class="text-gray-600">{{ $duration }}</span>
            </div>
            <div>
                <span class="font-medium">Progression :</span>
                <span class="text-gray-600">{{ $progressPercent }}%</span>
            </div>
            <div>
                <span class="font-medium">Vidéo en lecture :</span>
                <span
                    class="{{ $isPlaying ? 'text-green-600' : 'text-red-600' }}"
                >
                    {{ $isPlaying ? "✅ Oui" : "❌ Non" }}
                </span>
            </div>
            <div>
                <span class="font-medium">Vidéo terminée :</span>
                <span
                    class="{{
                        $isCompleted ? 'text-green-600' : 'text-red-600'
                    }}"
                >
                    {{ $isCompleted ? "✅ Oui" : "❌ Non" }}
                </span>
            </div>
            <div>
                <span class="font-medium">Message de succès :</span>
                <span
                    class="{{
                        $showSuccessMessage ? 'text-green-600' : 'text-red-600'
                    }}"
                >
                    {{ $showSuccessMessage ? "✅ Oui" : "❌ Non" }}
                </span>
            </div>
        </div>
    </div>

    {{-- Actions vidéo --}}
    <div class="flex justify-end items-center">
        {{-- Bouton automatique après la vidéo --}}
        @if($showSuccessMessage)
        <div class="mr-4">
            <div
                class="bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg p-4 mb-4"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg
                            class="h-5 w-5 text-green-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3
                            class="text-sm font-medium text-green-800 dark:text-green-200"
                        >
                            Leçon terminée automatiquement !
                        </h3>
                        <p
                            class="mt-1 text-sm text-green-700 dark:text-green-300"
                        >
                            Vous serez redirigé vers la formation dans quelques
                            secondes...
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Bouton manuel --}}
        @if($showManualButton)
        <form
            method="POST"
            action="{{
                route('eleve.lesson.complete', [
                    $team,
                    $formation,
                    $chapter,
                    $lesson
                ])
            }}"
            class="inline"
        >
            @csrf
            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
            >
                Marquer comme terminée
            </button>
        </form>
        @endif
    </div>

    {{-- JavaScript pour la gestion vidéo --}}
    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("🎬 Livewire VideoPlayer initialisé");

            const video = document.getElementById("lesson-video");
            if (!video) {
                console.error("❌ Élément vidéo non trouvé");
                return;
            }

            // Écouter les événements Livewire
            Livewire.on("update-debug-indicators", (data) => {
                console.log("📊 Mise à jour indicateurs:", data);

                // Mettre à jour les éléments DOM
                const elements = {
                    "debug-current-time": data[0].currentTime,
                    "debug-duration": data[0].duration,
                    "debug-progress": data[0].progress,
                    "debug-playing": data[0].isPlaying ? "✅ Oui" : "❌ Non",
                    "debug-completed": data[0].isCompleted
                        ? "✅ Oui"
                        : "❌ Non",
                };

                Object.entries(elements).forEach(([id, value]) => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = value;
                        element.className =
                            data[0].isPlaying || data[0].isCompleted
                                ? "text-green-600"
                                : "text-red-600";
                    }
                });
            });

            // Gestionnaire d'événement pour la fin de vidéo
            video.addEventListener("ended", function () {
                console.log("🎬 Vidéo terminée - appel Livewire");

                // Émettre l'événement Livewire pour marquer comme terminé
                Livewire.emit("markVideoAsCompleted");
            });

            // Vérification périodique pour détecter la fin avec avance rapide
            setInterval(() => {
                if (!video.duration) return;

                const currentTime = video.currentTime;
                const duration = video.duration;

                // Si la vidéo est à plus de 95% et n'est pas en pause
                if (
                    currentTime > 0 &&
                    currentTime >= duration * 0.95 &&
                    !video.paused
                ) {
                    console.log("🏁 Fin détectée par vérification périodique");
                    Livewire.emit("markVideoAsCompleted");
                }
            }, 2000);

            console.log("✅ Gestionnaire vidéo Livewire initialisé");
        });
    </script>
    @endpush
</div>
