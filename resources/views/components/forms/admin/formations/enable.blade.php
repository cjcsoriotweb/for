@props(['team', 'formation'])

<form method="POST" action="{{ route('application.admin.formations.enable', [$team]) }}" {{ $attributes->merge(['class' => 'inline']) }}>
    @csrf
    <input type="hidden" name="formation_id" value="{{ $formation->id }}">

    <button
        type="submit"
        class="w-full focus:outline-none text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300 font-medium rounded-lg text-sm px-4 py-2.5 transition-colors duration-200"
    >
        <span class="material-symbols-outlined text-sm mr-2">check_circle</span>
        {{ __('Activer') }}
    </button>
</form>
