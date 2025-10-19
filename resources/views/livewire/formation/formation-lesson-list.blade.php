<div class="w-full">
    <!-- Loading State -->
    <div
        wire:loading
        class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50"
    >
        <div
            class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-lg"
        >
            <div
                class="animate-spin rounded-full h-6 w-6 border-b-2 border-green-600"
            ></div>
            <span class="text-gray-700">Traitement en cours...</span>
        </div>
    </div>

    <!-- Header Section -->
    @include('livewire.formation.formation-lesson-list.header')

    <!-- Lessons List -->
    <div class="bg-white" wire:loading.remove>
        @forelse($lessons as $lesson)
        @include('livewire.formation.formation-lesson-list.lesson-item',
        ['lesson' => $lesson]) @empty
        @include('livewire.formation.formation-lesson-list.empty-state')
        @endforelse
    </div>

    <!-- Success Messages -->
    @if (session()->has('success'))
    <div class="mt-4 mx-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <span class="material-symbols-outlined text-green-600 text-lg mr-2"
                >check_circle</span
            >
            <span class="text-green-800">{{ session("success") }}</span>
        </div>
    </div>
    @endif

    <!-- Livewire Success Messages -->
    @if ($showSuccess && $successMessage)
    <div
        class="mt-4 mx-6 p-4 bg-green-50 border border-green-200 rounded-lg"
        wire:transition
        x-data="{ show: true }"
        x-show="show"
        x-init="
                $wire.on('auto-hide-message', () => {
                    setTimeout(() => show = false, 3000);
                    setTimeout(() => $wire.call('clearSuccessMessage'), 3500);
                });
            "
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span
                    class="material-symbols-outlined text-green-600 text-lg mr-2"
                    >check_circle</span
                >
                <span class="text-green-800">{{ $successMessage }}</span>
            </div>
            <button
                wire:click="clearSuccessMessage"
                class="text-green-600 hover:text-green-800 p-1"
                title="Fermer"
            >
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if (session()->has('error'))
    <div class="mt-4 mx-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-center">
            <span class="material-symbols-outlined text-red-600 text-lg mr-2"
                >error</span
            >
            <span class="text-red-800">{{ session("error") }}</span>
        </div>
    </div>
    @endif

    <!-- Livewire Error Messages -->
    @if ($showError && $errorMessage)
    <div
        class="mt-4 mx-6 p-4 bg-red-50 border border-red-200 rounded-lg"
        wire:transition
        x-data="{ show: true }"
        x-show="show"
        x-init="
                $wire.on('auto-hide-error', () => {
                    setTimeout(() => show = false, 5000);
                    setTimeout(() => $wire.call('clearErrorMessage'), 5500);
                });
            "
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span
                    class="material-symbols-outlined text-red-600 text-lg mr-2"
                    >error</span
                >
                <span class="text-red-800">{{ $errorMessage }}</span>
            </div>
            <button
                wire:click="clearErrorMessage"
                class="text-red-600 hover:text-red-800 p-1"
                title="Fermer"
            >
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    </div>
    @endif
</div>
