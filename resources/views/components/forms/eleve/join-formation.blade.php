@props(['formation', 'label' => null])

@php
    $buttonLabel = $label ?? ($formation['enroll_button_label'] ?? __('Rejoindre cette formation'));
@endphp

<form method="POST" action="{{ $formation['enroll_route'] }}" {{ $attributes->merge(['class' => 'w-full']) }}>
    @csrf
    <button
        type="submit"
        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:scale-[1.01] hover:shadow-indigo-500/45"
    >
        {{ $buttonLabel }}
    </button>
</form>
