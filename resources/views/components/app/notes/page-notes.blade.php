<div class="fixed bottom-6 left-6 z-50">
    <button
        type="button"
        class="bubble-button relative h-16 w-16 rounded-full bg-blue-500 text-white shadow-lg"
    >
        <span class="relative z-10 text-xl">??</span>
        <span class="bubble-liquid"></span>
    </button>
</div>

@push('styles')
<style>
    .bubble-button {
        overflow: hidden;
        display: grid;
        place-items: center;
        animation: bubble-pulse 3s ease-in-out infinite;
    }

    .bubble-liquid {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.2) 40%, transparent 60%),
            radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.7) 0%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        mix-blend-mode: screen;
        opacity: 0.9;
        transform: translateY(10%);
        animation: bubble-wave 4s ease-in-out infinite;
    }

    @keyframes bubble-pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.45);
        }
        50% {
            transform: scale(1.07);
            box-shadow: 0 15px 35px rgba(37, 99, 235, 0.35);
        }
    }

    @keyframes bubble-wave {
        0%, 100% {
            transform: translateY(10%) scale(1);
        }
        50% {
            transform: translateY(-6%) scale(1.05);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const bubble = document.querySelector('.bubble-button');
        if (!bubble) {
            return;
        }

        bubble.addEventListener('click', () => {
            window.alert('Hello depuis la bulle ?');
        });
    });
</script>
@endpush
