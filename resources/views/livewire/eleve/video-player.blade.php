<div>
    <div class="aspect-video mb-4 bg-black rounded-lg overflow-hidden" wire:ignore>
        <video
            controls
            autoplay
            class="w-full h-full"
            id="lesson-video"
            preload="auto"
            poster="{{ asset('images/video-poster.jpg') }}"
            oncontextmenu="return false;"
            data-resume-time="{{ $resumeTime }}"
            data-lesson-id="{{ $lesson->id ?? '' }}"
            data-lesson-content-id="{{ $lessonContent->id ?? '' }}"
        >
            <source
                src="{{ Storage::disk('public')->url($lessonContent->video_path) }}"
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

    <div
        id="save-notification"
        class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg animate-pulse hidden"
        role="status"
        aria-live="polite"
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
                Durée sauvegardée : <span id="saved-duration"></span>
            </span>
        </div>
    </div>
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
    @endif
    @once
    @script
    <script>
        const video = document.getElementById("lesson-video");
        const currentTimeDisplay = document.getElementById("current-time");
        if (!video || !currentTimeDisplay) return;

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds.toString().padStart(2, "0")}`;
        }

        const UPDATE_INTERVAL = 5000;
        let lastUpdateTime = 0;
        const lessonId = video.dataset.lessonId || null;
        const storageKey = lessonId ? `lesson-progress-${lessonId}` : null;
        const serverResumeTime = Number.parseFloat(@json($resumeTime ?? 0)) || 0;

        const readStoredProgress = () => {
            if (!storageKey || typeof localStorage === "undefined") {
                return 0;
            }
            const storedValue = localStorage.getItem(storageKey);
            const parsedValue = Number.parseFloat(storedValue);
            return Number.isFinite(parsedValue) ? parsedValue : 0;
        };

        const writeStoredProgress = (value) => {
            if (!storageKey || typeof localStorage === "undefined") {
                return;
            }
            if (!Number.isFinite(value)) {
                return;
            }
            localStorage.setItem(storageKey, value.toString());
        };

        let resumeHint = Math.max(serverResumeTime, readStoredProgress());

        function updateCurrentTime() {
            const currentSeconds = video.currentTime || 0;
            const durationSeconds = video.duration || 0;
            currentTimeDisplay.textContent = `${formatTime(currentSeconds)} / ${formatTime(durationSeconds)}`;

            const now = Date.now();
            if (now - lastUpdateTime >= UPDATE_INTERVAL) {
                lastUpdateTime = now;
                $wire.dispatch("videoTimeUpdate", { currentTime: currentSeconds });
            }
        }

        video.addEventListener("timeupdate", updateCurrentTime);

        video.addEventListener("loadedmetadata", function () {
            const datasetResume = parseFloat(video.dataset.resumeTime) || 0;
            resumeHint = Math.max(resumeHint, datasetResume);
            if (resumeHint > 0 && resumeHint < video.duration) {
                video.currentTime = resumeHint;
            }
            updateCurrentTime();
        });

        video.addEventListener("ended", () => {
            $wire.dispatch("videoEnded", { endEvent: true });
        });

        $wire.on("leave", () => window.location.reload());

        const saveNotification = document.getElementById("save-notification");
        const savedDuration = document.getElementById("saved-duration");
        let hideNotificationTimeout;

        const showSaveNotification = (timeText) => {
            if (!saveNotification || !savedDuration) return;
            savedDuration.textContent = timeText || "";
            saveNotification.classList.remove("hidden");
            clearTimeout(hideNotificationTimeout);
            hideNotificationTimeout = setTimeout(() => {
                saveNotification.classList.add("hidden");
            }, 3000);
        };

        $wire.on("progressSaved", (payload) => {
            const seconds = Number.parseFloat(payload?.seconds);
            if (Number.isFinite(seconds)) {
                resumeHint = Math.max(resumeHint, seconds);
                writeStoredProgress(seconds);
            }
            const timeText = payload?.time ?? "";
            showSaveNotification(timeText);
        });

        updateCurrentTime();
    </script>
    @endscript
    @endonce
</div>
