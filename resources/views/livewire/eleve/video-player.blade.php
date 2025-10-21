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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
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
                        detail: {
                            currentTime: video.currentTime,
                            duration: video.duration || 0,
                            percentage: video.duration
                                ? (video.currentTime / video.duration) * 100
                                : 0,
                        },
                    });
                    document.dispatchEvent(timeEvent);
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
        });
    </script>
</div>
