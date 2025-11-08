@props(['formation', 'label' => null])

@php
    $hasEntryQuiz = data_get($formation, 'has_entry_quiz', false);
    $entryQuizRoute = data_get($formation, 'entry_quiz_route');
    $defaultLabel = $hasEntryQuiz
        ? __('Vérifier que la formation est adaptée à moi')
        : __('Rejoindre cette formation');
    $buttonLabel = $label ?? ($formation['enroll_button_label'] ?? $defaultLabel);
    $buttonClasses = 'inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-sky-500 via-indigo-500 to-purple-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition hover:scale-[1.01] hover:shadow-indigo-500/45';
@endphp

@if($hasEntryQuiz && $entryQuizRoute)
    <a href="{{ $entryQuizRoute }}" {{ $attributes->merge(['class' => $buttonClasses]) }}>
        {{ $buttonLabel }}
    </a>
@else
    <form method="POST" action="{{ $formation['enroll_route'] }}" {{ $attributes->merge(['class' => 'w-full']) }}>
        @csrf
        <button type="submit" class="{{ $buttonClasses }}">
            {{ $buttonLabel }}
        </button>
    </form>
@endif
