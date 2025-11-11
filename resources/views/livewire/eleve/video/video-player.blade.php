<div>

    <main class="flex flex-1 justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="layout-content-container flex flex-col w-full max-w-5xl flex-1">
            <div class="flex flex-col gap-6">
                <div class="relative group">

                    <div class="relative flex items-center justify-center bg-black bg-cover bg-center aspect-video rounded-xl overflow-hidden shadow-lg"
                        data-alt="Abstract gradient background for video placeholder"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC8uTSDep5uWpkw20gfP_oegiJ9Sz_XPzoDXWLXm5VgUB4rOfJV32Vw3rYBIR3IxKonTtVGIp1QRrdxf2BIDXgHfhhr4kDfX7evvAPSTW_jIbGBKlqkAACVXHNEnhs4WDuil3uNEiP4zVpGjoyaO3FtaTbCHu0mg5IAlfnRuGvZnzcjjUV1NGLu-PQcivjrp2H88e5L1BhWkaOLDaN63UV_piT4lDTNKF4LZbpKl9FevxmaS7OLf9UjyJAGK8XEjTH076805Qn4tmk");'>


                        <video id="video" wire:ignore class="w-full h-full" playsinline preload="metadata"
                            poster="{{ asset('images/video-poster.jpg') }}" oncontextmenu="return false;"
                            data-initialized="false" data-resume-time="{{ $resumeTime ?? 0 }}"
                            data-lesson-id="{{ $lesson->id ?? '' }}"
                            data-lesson-content-id="{{ $lessonContent->id ?? '' }}"
                            style="display: absolute;width:100%;height:100%;">
                            <source src="{{ Storage::disk('public')->url($lessonContent->video_path) }}"
                                type="video/mp4" />
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                        @if (!$isPlaying)
                            <button type="button" wire:click="togglePlayback" style="position: absolute"
                                class="flex shrink-0 items-center justify-center rounded-full size-16 bg-black/50 text-white backdrop-blur-sm transition-transform group-hover:scale-110">
                                <span class="material-symbols-outlined text-4xl">
                                    {{ $isPlaying ? 'pause' : 'play_arrow' }}
                                </span>
                            </button>
                        @endif
                    </div>

                    <div class="absolute inset-0 pointer-events-none p-4 md:p-6">
                        <div class="flex justify-between">
                            @if (!$isPlaying)
                                <h1 class="text-xl md:text-2xl font-bold text-white tracking-tight">Introduction to
                                    Frontend
                                    Design</h1>
                            @endif

                            <p id="toast"
                                class="opacity-0 text-white text-sm font-normal leading-normal py-1 px-3 bg-black/40 rounded-md backdrop-blur-sm">
                                Lecture enregistrée <span id="time"></span></p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4">

                    @php
                        $formatTime = function ($seconds) {
                            $seconds = max(0, (int) $seconds);
                            return $seconds >= 3600 ? gmdate('H:i:s', $seconds) : gmdate('i:s', $seconds);
                        };
                        $progressWidth = $duration > 0 ? number_format($watchedPercentage, 2, '.', '') : 0;
                    @endphp
                    <div class="flex items-center gap-4">
                        <span data-progress-current class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ $formatTime($currentTime) }}
                        </span>
                        <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full group cursor-pointer">
                            <div class="relative h-full">
                                <div data-progress-fill class="absolute h-full bg-primary rounded-full"
                                    style="width: {{ $progressWidth }}%;"></div>
                                <div data-progress-handle
                                    class="absolute size-4 bg-white dark:bg-gray-300 rounded-full -translate-y-1/2 top-1/2 -translate-x-1/2 shadow-md transition-transform group-hover:scale-110"
                                    style="left: {{ $progressWidth }}%;"></div>
                            </div>
                        </div>
                        <span data-progress-total class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            {{ $formatTime($duration) }}
                        </span>
                    </div>
                    <div class="flex justify-center items-center gap-4">
                        <button type="button" wire:click="seekBy(-10)" wire:loading.remove
                            class="p-3 text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-2xl">replay_10</span>
                        </button>
                        <button type="button" wire:click="togglePlayback" 
                            class="p-4 text-white bg-primary rounded-full hover:bg-primary/90 transition-colors shadow-md" >
                            <span class="material-symbols-outlined text-3xl">
                                {{ $isPlaying ? 'pause' : 'play_arrow' }}
                            </span>
                        </button>
                        <button type="button" wire:click="seekBy(10)" wire:loading.remove
                            class="p-3 text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-2xl">forward_10</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

@script
    <script>
        $wire.on('updated', (data) => {
            console.log('updated', data)
            const el = document.getElementById('toast');
            el.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
            el.classList.add('opacity-100', 'scale-100');

            setTimeout(() => {
                el.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                el.classList.remove('opacity-100', 'scale-100');
            }, 1000);

        });

        document.addEventListener('livewire:init', () => {

            const getVideo = () => document.getElementById('video');
            const getProgressElements = () => ({
                current: document.querySelector('[data-progress-current]'),
                total: document.querySelector('[data-progress-total]'),
                fill: document.querySelector('[data-progress-fill]'),
                handle: document.querySelector('[data-progress-handle]'),
            });
            const formatTime = (seconds) => {
                const value = Math.max(0, Math.floor(seconds || 0));
                const hrs = Math.floor(value / 3600);
                const mins = Math.floor((value % 3600) / 60);
                const secs = value % 60;
                if (hrs > 0) {
                    return [hrs, mins, secs].map((n) => n.toString().padStart(2, '0')).join(':');
                }
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            };

            setInterval(() => {
                const currentTime = Math.floor(video.currentTime || 0);

                $wire.dispatch('post', {
                    data: currentTime
                });
            }, 5000);

            const updateProgressUI = (video) => {
                if (!video) return;
                const {
                    current,
                    total,
                    fill,
                    handle
                } = getProgressElements();
                const duration = Math.floor(video.duration || 0);
                const currentTime = Math.floor(video.currentTime || 0);
                const percentage = duration > 0 ? (currentTime / duration) * 100 : 0;

                if (current) current.textContent = formatTime(currentTime);
                if (total) total.textContent = formatTime(duration);
                if (fill) fill.style.width = `${percentage}%`;
                if (handle) handle.style.left = `${percentage}%`;


            };
            const getComponent = () => {
                const video = getVideo();
                if (!video || !window.Livewire) return null;
                const root = video.closest('[wire\\:id]');
                if (!root) return null;
                return window.Livewire.find(root.getAttribute('wire:id'));
            };

            const startPeriodicSave = (() => {
                let intervalId = null;
                return () => {
                    const video = getVideo();
                    const component = getComponent();
                    if (!video || !component) return;
                    if (intervalId) return;

                    intervalId = setInterval(() => {
                        const currentVideo = getVideo();
                        const currentComponent = getComponent();

                        if (!currentVideo || !currentComponent) {
                            clearInterval(intervalId);
                            intervalId = null;
                            return;
                        }

                        const seconds = Math.floor(currentVideo.currentTime || 0);
                        currentComponent.call('saveme', seconds);
                    }, 5000);
                };
            })();

            const dispatchProgress = (() => {
                let lastSent = 0;
                let pending = false;
                return (video, force = false) => {
                    if (!video) return;
                    updateProgressUI(video);
                    const component = getComponent();
                    if (!component) return;

                    const now = Date.now();
                    if (!force && now - lastSent < 1000) return; // throttle to ~1s
                    if (pending) return;

                    pending = true;
                    lastSent = now;

                    component.call(
                        'handleVideoProgress',
                        Math.floor(video.currentTime || 0),
                        Math.floor(video.duration || 0)
                    ).finally(() => {
                        pending = false;
                    });
                };
            })();

            const bindProgressListeners = () => {
                const video = getVideo();
                if (!video || video.dataset.progressBound === 'true') return;

                const sendProgress = (force = false) => dispatchProgress(video, force);

                video.addEventListener('timeupdate', () => sendProgress());
                video.addEventListener('seeking', () => sendProgress(true));
                video.addEventListener('seeked', () => sendProgress(true));
                video.addEventListener('loadedmetadata', () => sendProgress(true));
                video.addEventListener('play', () => sendProgress(true));
                video.addEventListener('pause', () => sendProgress(true));
                video.addEventListener('ended', () => {
                    $wire.dispatch('ended');

                });

                if (video.readyState >= 1) {
                    sendProgress(true);
                }

                video.dataset.progressBound = 'true';
                updateProgressUI(video);
                startPeriodicSave();
            };

            Livewire.on('video-play', () => {
                const video = getVideo();
                if (!video) return;

                const playPromise = video.play();
                if (playPromise?.catch) {
                    playPromise.catch(() => {
                        /* autoplay blocked or other issue; ignore */
                    });
                }
            });

            Livewire.on('video-pause', () => {
                const video = getVideo();
                if (video) {
                    video.pause();
                }
            });

            Livewire.on('video-seek', (seconds = 0) => {
                const video = getVideo();
                if (!video) return;

                const offset = Number(seconds) || 0;
                const current = Number(video.currentTime || 0);
                const hasDuration = Number.isFinite(video.duration) && video.duration > 0;
                const duration = hasDuration ? video.duration : null;
                const targetTime = Math.max(0, current + offset);

                video.currentTime = duration !== null ? Math.min(duration, targetTime) : targetTime;
                dispatchProgress(video, true);
            });

            bindProgressListeners();
            document.addEventListener('livewire:navigated', () => {
                bindProgressListeners();
                updateProgressUI(getVideo());
            });
            Livewire.hook('message.processed', () => {
                bindProgressListeners();
                updateProgressUI(getVideo());
            });
        });
    </script>
@endscript
