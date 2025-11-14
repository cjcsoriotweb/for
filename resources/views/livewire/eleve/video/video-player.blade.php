<div>
    <style>
        [data-fullscreen-root].video-player--fake-fullscreen {
            position: fixed;
            inset: 0;
            z-index: 10001;
            padding: clamp(1rem, 3vw, 1.5rem);
            background-color: #030712;
            width: 100vw;
            height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: clamp(1rem, 2vw, 1.5rem);
            border-radius: 0;
        }
        [data-fullscreen-root].video-player--fake-fullscreen [data-player-video-wrapper] {
            flex: 1;
            min-height: 0;
        }
        [data-fullscreen-root].video-player--fake-fullscreen [data-player-video-panel] {
            aspect-ratio: auto !important;
            height: 100%;
        }
        [data-fullscreen-root].video-player--fake-fullscreen [data-player-video-panel] video {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }
        [data-fullscreen-root].video-player--fake-fullscreen [data-player-controls] {
            width: 100%;
        }
    </style>

    <main class="flex flex-1 justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="layout-content-container flex flex-col w-full max-w-5xl flex-1">
            <div class="flex flex-col gap-6" data-fullscreen-root>
                <div class="relative group" data-player-video-wrapper>

                    <div class="relative flex items-center justify-center bg-black bg-cover bg-center aspect-video rounded-xl overflow-hidden shadow-lg"
                        data-alt="Abstract gradient background for video placeholder"
                        data-player-video-panel
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC8uTSDep5uWpkw20gfP_oegiJ9Sz_XPzoDXWLXm5VgUB4rOfJV32Vw3rYBIR3IxKonTtVGIp1QRrdxf2BIDXgHfhhr4kDfX7evvAPSTW_jIbGBKlqkAACVXHNEnhs4WDuil3uNEiP4zVpGjoyaO3FtaTbCHu0mg5IAlfnRuGvZnzcjjUV1NGLu-PQcivjrp2H88e5L1BhWkaOLDaN63UV_piT4lDTNKF4LZbpKl9FevxmaS7OLf9UjyJAGK8XEjTH076805Qn4tmk");'>
                        @php
                            $videoSource = null;

                            if (! empty($lessonContent->video_path)) {
                                $videoSource = Storage::disk('public')->url($lessonContent->video_path);
                            } elseif (! empty($lessonContent->video_url)) {
                                $videoSource = $lessonContent->video_url;
                            }
                        @endphp
                        @if ($videoSource)

                        <video id="video" wire:ignore class="w-full h-full" playsinline preload="metadata"
                            poster="{{ asset('images/video-poster.jpg') }}" oncontextmenu="return false;"
                            data-initialized="false" data-resume-time="{{ $resumeTime ?? 0 }}"
                            data-lesson-id="{{ $lesson->id ?? '' }}"
                            data-lesson-content-id="{{ $lessonContent->id ?? '' }}"
                            style="display: absolute;width:100%;height:100%;">
                            <source src="{{ $videoSource }}"
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
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center gap-2 bg-black/60 text-white p-6">
                                <span class="text-lg font-semibold">{{ __("Impossible de charger la vidéo") }}</span>
                                <p class="text-sm text-white/80 max-w-md">
                                    {{ __("Aucun fichier ou lien vidéo n'est associé à cette leçon pour le moment.") }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="absolute inset-0 pointer-events-none p-4 md:p-6">
                        <div class="flex justify-between">
                            @if (!$isPlaying)
                                <h1 class="text-xl md:text-2xl font-bold text-white tracking-tight">{{$lesson->getname()}}</h1>
                            @endif

                            <p id="toast"
                                class="opacity-0 text-white text-sm font-normal leading-normal py-1 px-3 bg-black/40 rounded-md backdrop-blur-sm">
                                Lecture enregistrée <span id="time"></span></p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-4" data-player-controls>

                    @php
                        $formatTime = function ($seconds) {
                            $seconds = max(0, (int) $seconds);
                            return $seconds >= 3600 ? gmdate('H:i:s', $seconds) : gmdate('i:s', $seconds);
                        };
                        $progressWidth = $duration > 0 ? number_format($watchedPercentage, 2, '.', '') : 0;
                    @endphp
                    @php
                        $maxAccessibleSeconds = max(0, (int) ($maxWatchedSeconds ?? 0));
                        $allowedWidth = $duration > 0 ? number_format(min(100, ($maxAccessibleSeconds / $duration) * 100), 2, '.', '') : 0;
                        $primaryControl =
                            'relative flex items-center justify-center size-12 rounded-full border border-primary/30 bg-gradient-to-br from-primary to-primary/80 text-white transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40';
                        $secondaryControl =
                            'relative flex items-center justify-center size-11 rounded-full border border-gray-200/70 dark:border-gray-700/70 bg-white/80 dark:bg-gray-900/70 text-gray-700 dark:text-gray-200 transition-colors hover:text-primary dark:hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/30';
                    @endphp
                    <div
                        class="rounded-2xl border border-gray-100/70 dark:border-gray-800 bg-white/80 dark:bg-gray-900/70 p-5 backdrop-blur">
                        <div class="flex items-center gap-4">
                            <span data-progress-current
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 tabular-nums min-w-[3.5rem] text-right">
                                {{ $formatTime($currentTime) }}
                            </span>
                            <div class="flex-1 group cursor-pointer select-none" data-progress-container
                                data-max-watched="{{ $maxAccessibleSeconds }}">
                                <div class="h-2.5 rounded-full bg-gray-100/80 dark:bg-gray-800/80 transition-colors duration-200 relative overflow-hidden"
                                    data-progress-bar>
                                    <div class="absolute inset-0 pointer-events-none">
                                        <div data-progress-allowed
                                            class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-emerald-400/60 to-emerald-500/80 transition-[width] duration-300"
                                            style="width: {{ $allowedWidth }}%;"></div>
                                        <div data-progress-fill
                                            class="absolute inset-y-0 left-0 rounded-full bg-gradient-to-r from-primary to-primary/70 transition-none z-10"
                                            style="width: {{ $progressWidth }}%;"></div>
                                    </div>
                                    <div data-progress-handle
                                        class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 size-5 rounded-full bg-white ring-4 ring-white/70 dark:ring-gray-900/80 dark:bg-gray-100 transition-transform duration-200 group-hover:scale-110 z-20"
                                        style="left: {{ $progressWidth }}%;"></div>
                                </div>
                            </div>
                            <span data-progress-total
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300 tabular-nums min-w-[3.5rem]">
                                {{ $formatTime($duration) }}
                            </span>
                        </div>
                        <div class="mt-5 flex flex-wrap items-center gap-4 justify-between">
                            <div class="flex items-center gap-3">
                                <button type="button" aria-label="Reculer de 10 secondes" wire:click="seekBy(-10)"
                                    wire:loading.class="opacity-50"
                                    class="{{ $secondaryControl }}">
                                    <span class="material-symbols-outlined text-2xl">replay_10</span>
                                </button>
                                <button type="button" aria-label="{{ $isPlaying ? 'Mettre en pause' : 'Lire la vidéo' }}"
                                    wire:click="togglePlayback" class="{{ $primaryControl }}">
                                    <span class="material-symbols-outlined text-3xl" wire:loading.class="opacity-50">
                                        {{ $isPlaying ? 'pause' : 'play_arrow' }}
                                    </span>
                                </button>
                                <button type="button" aria-label="Avancer de 10 secondes" wire:click="seekBy(10)"
                                    wire:loading.class="opacity-50"
                                    class="{{ $secondaryControl }}">
                                    <span class="material-symbols-outlined text-2xl">forward_10</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex items-center gap-3 rounded-full border border-gray-100/60 dark:border-gray-800/80 bg-gray-50/80 dark:bg-gray-800/60 px-4 py-1.5">
                                    <button type="button" aria-label="Activer ou couper le son" data-volume-toggle
                                        class="text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined text-2xl" data-volume-icon>volume_up</span>
                                    </button>
                                    <input type="range" data-volume-slider min="0" max="1" step="0.05" value="1"
                                        aria-label="Volume"
                                        class="w-32 h-1.5 cursor-pointer accent-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 rounded-full bg-transparent">
                                </div>
                                <button type="button" aria-label="Basculer en plein écran" data-fullscreen-toggle
                                    class="{{ $secondaryControl }}">
                                    <span class="material-symbols-outlined text-2xl" data-fullscreen-icon>fullscreen</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>

@script
    <script>
        const getVideo = () => document.getElementById('video');
        const getProgressContainer = () => document.querySelector('[data-progress-container]');
        const parseToNumber = (value) => {
            const numberValue = Number(value);
            return Number.isFinite(numberValue) ? numberValue : 0;
        };
        const parseSecondsPayload = (value, depth = 0) => {
            if (Number.isFinite(value)) return value;
            if (typeof value === 'string' && value.trim() !== '') {
                const parsed = Number(value);
                return Number.isFinite(parsed) ? parsed : 0;
            }
            if (value && typeof value === 'object' && depth < 3) {
                if ('seconds' in value) return parseSecondsPayload(value.seconds, depth + 1);
                if ('data' in value) return parseSecondsPayload(value.data, depth + 1);
                if ('value' in value) return parseSecondsPayload(value.value, depth + 1);
            }
            return 0;
        };
        let maxWatchedSeconds = (() => {
            const container = getProgressContainer();
            return container ? parseToNumber(container.dataset.maxWatched) : 0;
        })();
        const getMaxWatchedSeconds = () => maxWatchedSeconds;
        const setMaxWatchedSeconds = (value = 0) => {
            const sanitized = Math.max(0, parseToNumber(value));
            if (sanitized <= maxWatchedSeconds) {
                return maxWatchedSeconds;
            }
            maxWatchedSeconds = sanitized;
            const container = getProgressContainer();
            if (container) {
                container.dataset.maxWatched = sanitized;
            }
            return maxWatchedSeconds;
        };
        const getProgressElements = () => ({
            container: getProgressContainer(),
            current: document.querySelector('[data-progress-current]'),
            total: document.querySelector('[data-progress-total]'),
            fill: document.querySelector('[data-progress-fill]'),
            handle: document.querySelector('[data-progress-handle]'),
            allowed: document.querySelector('[data-progress-allowed]'),
            bar: document.querySelector('[data-progress-bar]'),
        });
        const getVolumeElements = () => ({
            slider: document.querySelector('[data-volume-slider]'),
            toggle: document.querySelector('[data-volume-toggle]'),
            icon: document.querySelector('[data-volume-icon]'),
        });
        const getFullscreenElements = () => ({
            button: document.querySelector('[data-fullscreen-toggle]'),
            icon: document.querySelector('[data-fullscreen-icon]'),
        });
        const getFullscreenTarget = () => {
            const explicitTarget = document.querySelector('[data-fullscreen-root]');
            if (explicitTarget) {
                return explicitTarget;
            }
            const video = getVideo();
            return video ? video.parentElement : null;
        };
        const FAKE_FULLSCREEN_CLASS = 'video-player--fake-fullscreen';
        let previousDocumentOverflow = '';
        let fakeFullscreenKeyListenerBound = false;
        const isFakeFullscreenActive = () => {
            const target = getFullscreenTarget();
            return Boolean(target && target.classList.contains(FAKE_FULLSCREEN_CLASS));
        };
        const applyDocumentOverflowState = (enabled) => {
            const rootElement = document.documentElement;
            if (!rootElement) return;
            if (enabled) {
                previousDocumentOverflow = rootElement.style.overflow;
                rootElement.style.overflow = 'hidden';
            } else {
                rootElement.style.overflow = previousDocumentOverflow || '';
                previousDocumentOverflow = '';
            }
        };
        const updateFullscreenIcon = () => {
            const { icon } = getFullscreenElements();
            if (!icon) return;
            icon.textContent = isFakeFullscreenActive() ? 'fullscreen_exit' : 'fullscreen';
        };
        const toggleFakeFullscreen = () => {
            const target = getFullscreenTarget();
            if (!target) return;
            const entering = !target.classList.contains(FAKE_FULLSCREEN_CLASS);
            target.classList.toggle(FAKE_FULLSCREEN_CLASS, entering);
            applyDocumentOverflowState(entering);
            updateFullscreenIcon();
        };
        const bindFakeFullscreenKeyListener = () => {
            if (fakeFullscreenKeyListenerBound) return;
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && isFakeFullscreenActive()) {
                    toggleFakeFullscreen();
                }
            });
            fakeFullscreenKeyListenerBound = true;
        };
        let lastKnownVolume = 1;
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
        const updateVolumeIcon = (volume = 1, muted = false) => {
            const { icon } = getVolumeElements();
            if (!icon) return;

            if (muted || volume <= 0.01) {
                icon.textContent = 'volume_off';
            } else if (volume < 0.5) {
                icon.textContent = 'volume_down';
            } else {
                icon.textContent = 'volume_up';
            }
        };
        const syncVolumeControls = (video = null) => {
            const targetVideo = video || getVideo();
            const { slider } = getVolumeElements();
            if (!targetVideo) return;

            const volume = targetVideo.muted ? 0 : Number(targetVideo.volume ?? 1);
            if (slider) {
                slider.value = volume;
            }
            updateVolumeIcon(volume, targetVideo.muted);
        };
        const updateAllowedTrack = (video = null) => {
            const targetVideo = video || getVideo();
            const { allowed } = getProgressElements();
            if (!allowed || !targetVideo) return;

            const duration = parseToNumber(targetVideo.duration);
            if (!duration) return;

            const allowedSeconds = Math.min(getMaxWatchedSeconds(), duration);
            const percentage = duration > 0 ? (allowedSeconds / duration) * 100 : 0;

            allowed.style.width = `${Math.min(100, Math.max(0, percentage))}%`;
        };
        const getComponent = () => {
            const video = getVideo();
            if (!video || !window.Livewire) return null;
            const root = video.closest('[wire\\:id]');
            if (!root) return null;
            return window.Livewire.find(root.getAttribute('wire:id'));
        };
        const emitProgressUpdate = (value = 0) => {
            const component = getComponent();
            if (!component || !Number.isFinite(value)) {
                return;
            }

            const seconds = Math.max(0, Math.floor(value));
            component.call('post', seconds);
        };

        setInterval(() => {
            const videoElement = getVideo();
            if (!videoElement) {
                return;
            }

            emitProgressUpdate(videoElement.currentTime || 0);
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

            updateAllowedTrack(video);
        };
        const bindVolumeControls = () => {
            const video = getVideo();
            const { slider, toggle } = getVolumeElements();
            if (!video) return;

            if (slider && slider.dataset.bound !== 'true') {
                slider.value = video.muted ? 0 : Number(video.volume ?? 1);
                slider.addEventListener('input', (event) => {
                    const value = Number(event.target.value);
                    const sanitized = Number.isFinite(value) ? Math.min(1, Math.max(0, value)) : 1;
                    if (sanitized > 0) {
                        lastKnownVolume = sanitized;
                        video.muted = false;
                    } else {
                        video.muted = true;
                    }
                    video.volume = sanitized;
                    updateVolumeIcon(video.volume, video.muted);
                });
                slider.dataset.bound = 'true';
            }

            if (toggle && toggle.dataset.bound !== 'true') {
                toggle.addEventListener('click', () => {
                    if (video.muted || video.volume <= 0.01) {
                        video.muted = false;
                        video.volume = lastKnownVolume > 0 ? lastKnownVolume : 1;
                    } else {
                        lastKnownVolume = video.volume > 0 ? video.volume : lastKnownVolume;
                        video.muted = true;
                    }
                    syncVolumeControls(video);
                });
                toggle.dataset.bound = 'true';
            }

            if (video.dataset.volumeBound !== 'true') {
                video.addEventListener('volumechange', () => {
                    if (!video.muted && video.volume > 0) {
                        lastKnownVolume = video.volume;
                    }
                    syncVolumeControls(video);
                });
                video.dataset.volumeBound = 'true';
            }

            syncVolumeControls(video);
        };
        const bindFullscreenControl = () => {
            const { button } = getFullscreenElements();
            if (!button || !getFullscreenTarget()) return;

            if (button.dataset.bound !== 'true') {
                button.addEventListener('click', toggleFakeFullscreen);
                button.dataset.bound = 'true';
            }

            bindFakeFullscreenKeyListener();
            updateFullscreenIcon();
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

                    emitProgressUpdate(currentVideo.currentTime || 0);
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

        const getAllowedSeekTime = (video) => {
            if (!video) return 0;
            const duration = parseToNumber(video.duration);
            if (!duration) return 0;

            return Math.min(duration, Math.max(0, getMaxWatchedSeconds()));
        };

        const bindProgressBarInteractions = () => {
            const { bar } = getProgressElements();
            if (!bar || bar.dataset.seekBound === 'true') return;

            let isDragging = false;

            const seekFromEvent = (event) => {
                const video = getVideo();
                if (!video) return;

                const duration = parseToNumber(video.duration);
                if (!duration) return;

                const rect = bar.getBoundingClientRect();
                if (!rect.width) return;

                const ratio = (event.clientX - rect.left) / rect.width;
                const clampedRatio = Math.min(1, Math.max(0, ratio));
                const desiredTime = clampedRatio * duration;
                const allowedLimit = getAllowedSeekTime(video);
                const targetTime = Math.min(desiredTime, allowedLimit);

                video.currentTime = targetTime;
                dispatchProgress(video, true);
            };

            const stopDragging = (event) => {
                if (!isDragging) return;
                isDragging = false;
                if (event) {
                    bar.releasePointerCapture?.(event.pointerId);
                }
            };

            bar.addEventListener('pointerdown', (event) => {
                if (event.pointerType === 'mouse' && event.button !== 0) return;
                event.preventDefault();
                isDragging = true;
                bar.setPointerCapture?.(event.pointerId);
                seekFromEvent(event);
            });

            bar.addEventListener('pointermove', (event) => {
                if (!isDragging) return;
                seekFromEvent(event);
            });

            ['pointerup', 'pointercancel', 'pointerleave'].forEach((type) => {
                bar.addEventListener(type, (event) => {
                    if (!isDragging) return;
                    stopDragging(event);
                });
            });

            bar.dataset.seekBound = 'true';
            bar.style.touchAction = 'none';
        };

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
                const component = getComponent();
                component?.call('ended');
            });

            if (video.readyState >= 1) {
                sendProgress(true);
            }

            video.dataset.progressBound = 'true';
            updateProgressUI(video);
            startPeriodicSave();
        };

        Livewire.on('updated', (payload) => {
            const seconds = Math.max(0, Math.floor(parseSecondsPayload(payload) || 0));
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                toast.classList.add('opacity-100', 'scale-100');

                setTimeout(() => {
                    toast.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                    toast.classList.remove('opacity-100', 'scale-100');
                }, 1000);
            }

            const timeElement = document.getElementById('time');
            if (timeElement) {
                timeElement.textContent = formatTime(seconds);
            }

            const previousMax = getMaxWatchedSeconds();
            const newMax = setMaxWatchedSeconds(seconds);
            if (newMax !== previousMax) {
                updateAllowedTrack(getVideo());
            }
        });

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
            const allowedLimit = getAllowedSeekTime(video);
            const clampedTarget = Math.min(targetTime, allowedLimit);

            video.currentTime = duration !== null ? Math.min(duration, clampedTarget) : clampedTarget;
            dispatchProgress(video, true);
        });

        bindProgressListeners();
        bindProgressBarInteractions();
        bindVolumeControls();
        bindFullscreenControl();
        updateAllowedTrack(getVideo());
        document.addEventListener('livewire:navigated', () => {
            bindProgressListeners();
            bindProgressBarInteractions();
            bindVolumeControls();
            bindFullscreenControl();
            updateProgressUI(getVideo());
        });
        Livewire.hook('message.processed', () => {
            bindProgressListeners();
            bindProgressBarInteractions();
            bindVolumeControls();
            bindFullscreenControl();
            updateProgressUI(getVideo());
        });
    </script>
@endscript
