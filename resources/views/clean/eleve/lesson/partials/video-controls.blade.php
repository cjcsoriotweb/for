{{-- Contrôles vidéo personnalisés --}}
<div
    id="custom-video-controls"
    class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
>
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center space-x-4">
            <button
                id="play-pause-btn"
                class="text-blue-600 hover:text-blue-800"
            >
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path id="play-icon" d="M8 5v10l8-5-8-5z" />
                    <path
                        id="pause-icon"
                        class="hidden"
                        d="M6 4h2v12H6V4zm6 0h2v12h-2V4z"
                    />
                </svg>
            </button>

            <button id="mute-btn" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        id="unmute-icon"
                        d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.816L4.29 13H2a1 1 0 01-1-1V7a1 1 0 011-1h2.29l4.093-3.816a1 1 0 011.617.816z"
                    />
                    <path
                        id="mute-icon"
                        class="hidden"
                        d="M16.707 14.293a1 1 0 01-1.414 1.414l-1.414-1.414-1.414 1.414a1 1 0 01-1.414-1.414l1.414-1.414-1.414-1.414a1 1 0 011.414-1.414l1.414 1.414 1.414-1.414a1 1 0 011.414 1.414l-1.414 1.414 1.414 1.414z"
                    />
                </svg>
            </button>

            <div class="flex-1 mx-4">
                <div
                    class="bg-gray-200 dark:bg-gray-600 rounded-full h-2 cursor-pointer"
                    id="progress-bar"
                >
                    <div
                        class="bg-blue-600 h-2 rounded-full"
                        style="width: 0%"
                        id="progress"
                    ></div>
                </div>
            </div>

            <span
                id="current-time"
                class="text-sm text-gray-600 dark:text-gray-400"
                >0:00</span
            >
            <span class="text-sm text-gray-400">/</span>
            <span id="duration" class="text-sm text-gray-600 dark:text-gray-400"
                >0:00</span
            >
        </div>

        <div class="flex items-center space-x-2">
            <button
                id="fullscreen-btn"
                class="text-gray-600 hover:text-gray-800"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                    />
                </svg>
            </button>
        </div>
    </div>

    {{-- Contrôles de vitesse --}}
    <div class="flex items-center justify-center space-x-2">
        <span class="text-sm text-gray-600 dark:text-gray-400">Vitesse:</span>
        <select
            id="speed-select"
            class="text-sm bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded px-2 py-1"
        >
            <option value="0.5">0.5x</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected>Normal</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="2">2x</option>
        </select>
    </div>
</div>
