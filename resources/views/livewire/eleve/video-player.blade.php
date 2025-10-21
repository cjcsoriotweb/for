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

            // Update current time display
            function updateCurrentTime() {
                const currentTime = formatTime(video.currentTime);
                const duration = formatTime(video.duration || 0);
                currentTimeDisplay.textContent = `${currentTime} / ${duration}`;

                // Send current time to Livewire
                @this.call('handleVideoTimeUpdate', {
                    currentTime: video.currentTime,
                    duration: video.duration || 0,
                    percentage: video.duration ? (video.currentTime / video.duration) * 100 : 0
                });
            }

            // Function to run when video ends
            function onVideoEnd() {
                console.log("Vidéo terminée");

                // Send video end event to Livewire
                @this.call('handleVideoEnded', {
                    totalTime: video.duration || 0,
                    lessonId: '{{ $lesson->id ?? '' }}',
                    lessonContentId: '{{ $lessonContent->id ?? '' }}'
                });

                // Add your custom logic here - could dispatch an event, make an API call, etc.
                // For example: mark lesson as completed, show next lesson button, etc.
            }

            // Event listeners
            video.addEventListener("timeupdate", updateCurrentTime);
            video.addEventListener("loadedmetadata", function() {
                updateCurrentTime();
                // Resume from saved position if available
                const resumeTime = parseFloat(video.getAttribute('data-resume-time')) || 0;
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
