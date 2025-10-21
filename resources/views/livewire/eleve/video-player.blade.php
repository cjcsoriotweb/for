<div>
    <div class="aspect-video mb-4 bg-black rounded-lg overflow-hidden">
        <video
            controls
            autoplay
            class="w-full h-full"
            id="lesson-video"
            preload="auto"
            poster="{{ asset('images/video-poster.jpg') }}"
            oncontextmenu="return false;"
            data-resume-time="{{ $currentTime }}"
            data-lesson-id="{{ $lesson->id ?? '' }}"
            data-lesson-content-id="{{ $lessonContent->id ?? '' }}"
        >
            <source
                src="{{ asset('storage/' . $lessonContent->video_path) }}"
                type="video/mp4"
            />
            Votre navigateur ne supporte pas la lecture de vidéos.
        </video>
    </div>

    <!-- Current time display -->
    <div class="mb-4">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Temps actuel:
            <span id="current-time" class="font-mono">0:00 / 0:00</span>
        </div>
    </div>

    <!-- Save notification -->
    @if($showSaveNotification && $lastSavedTime)
    <div
        class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-pulse"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                    fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"
                ></path>
            </svg>
            <span class="font-medium">
                Durée sauvegardée : {{ $lastSavedTime }}
            </span>
        </div>
    </div>
    @endif

    <!-- Completion notification -->
    @if($showCompletionNotification)
    <div
        class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg animate-bounce"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                ></path>
            </svg>
            <span class="font-medium">
                Vidéo terminée ! Leçon marquée comme complétée.
            </span>
        </div>
    </div>
    @endif @script
    <script>
        const video = document.getElementById("lesson-video");
        const currentTimeDisplay = document.getElementById("current-time");
        if (!video || !currentTimeDisplay) return;

        // Function to format time in MM:SS
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds.toString().padStart(2, "0")}`;
        }

        let lastUpdateTime = 0;
        const UPDATE_INTERVAL = 5000; // 5 seconds in milliseconds

        // Update current time display
        function updateCurrentTime() {
            const currentTime = formatTime(video.currentTime);
            const duration = formatTime(video.duration || 0);
            currentTimeDisplay.textContent = `${currentTime} / ${duration}`;

            // Send time update to Livewire every 5 seconds
            const now = Date.now();
            if (now - lastUpdateTime >= UPDATE_INTERVAL) {
                lastUpdateTime = now;

                // Dispatch custom event that Livewire can listen to
                const timeEvent = new CustomEvent("videoTimeUpdate", {
                    currentTime: video.currentTime,
                });
                $wire.dispatch("videoTimeUpdate", { currentTime: currentTime });
            }
        }

        // Function to run when video ends
        function onVideoEnd() {
            console.log("Vidéo terminée");

            // Dispatch custom event that Livewire can listen to
            const endEvent = new CustomEvent("videoEnded", {
                detail: {
                    totalTime: video.duration || 0,
                    lessonId: video.getAttribute("data-lesson-id") || "",
                    lessonContentId:
                        video.getAttribute("data-lesson-content-id") || "",
                },
            });
            document.dispatchEvent(endEvent);

            // Add your custom logic here - could dispatch an event, make an API call, etc.
            // For example: mark lesson as completed, show next lesson button, etc.
        }

        // Event listeners
        video.addEventListener("timeupdate", updateCurrentTime);
        video.addEventListener("loadedmetadata", function () {
            updateCurrentTime();
            // Resume from saved position if available
            const resumeTime =
                parseFloat(video.getAttribute("data-resume-time")) || 0;
            if (resumeTime > 0 && resumeTime < video.duration) {
                video.currentTime = resumeTime;
            }
        });
        video.addEventListener("ended", onVideoEnd);

        // Initialize display
        updateCurrentTime();
    </script>
    @endscript
</div>
