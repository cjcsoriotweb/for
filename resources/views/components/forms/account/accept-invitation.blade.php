@props(['invitation'])

<form method="POST" action="{{ route('user.invitation.accept', $invitation->id) }}" {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    @csrf
    @method('PATCH')

    <button
        type="submit"
        class="group/btn flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-2.5 text-sm font-medium text-white transition-all duration-300 hover:from-emerald-600 hover:to-green-600 hover:scale-105"
    >
        <span class="material-symbols-outlined text-base transition-transform group-hover/btn:scale-110">check</span>
        {{ __('Accepter') }}
    </button>
</form>
