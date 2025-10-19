<div>
    @if($confirm)
    <button
        type="submit"
        class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
    >
        Confirmer {{ $confirmText }}
    </button>
    @else
    <a
        wire:click="$set('confirm', true)"
        class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200"
    >
        {{ $confirmText }}
    </a>
    @endif
</div>
