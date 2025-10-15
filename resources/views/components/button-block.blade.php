@props([
'titre',
'description' => null,
'url' => null,
'image' => null,
'button' => 'Accéder',
'label' => null, // ex: "can:updateTeamMember(team, member)"
'active' => false,
'disabled' => false,
'tooltip' => null,
])

@php
$cardBase = 'rounded-xl  transition duration-300 flex flex-col p-8';
$card = $disabled
? $cardBase . ' bg-gray-100 text-gray-500 opacity-60 cursor-not-allowed'
: $cardBase . ' bg-white';
if ($active && !$disabled) {
$card .= ' ring-2 ring-blue-500';
}
@endphp

<div class="{{ $card }}" @if($tooltip) title="{{ $tooltip }}" @endif aria-disabled="{{ $disabled ? 'true' : 'false' }}">

    <div>
        <h3 class="font-bold text-blue-600 mb-4">{!! $titre !!}</h3>
        @if(isset($description))
        <p class="text-gray-600 mb-6">{!! $description !!}</p>
        <br>
        @endif
    </div>

    @if($image)
    <div class="mt-auto self-center">
        <img style="height:100px;width:100px;object-fit:scale-down;" src="{{ $image }}" alt="{{ strip_tags($titre) }}"
            class="w-full h-auto rounded-lg">
        <br>
    </div>
    @endif

    <div class="mt-auto self-start">
        @if(!$disabled && $url)
        <a href="{{ $url }}"
            class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 inline-block">
            {!! $button !!}
        </a>
        @else
        <span
            class="bg-gray-300 text-gray-600 font-semibold py-3 px-6 rounded-lg inline-block pointer-events-none select-none">
            {!! $button !!}
        </span>
        @endif
    </div>
    @if($label && config('app.debug'))
    <div class="mb-3">
        <span class="inline-flex items-center rounded-full bg-gray-200 text-gray-700 px-2 py-0.5 text-xs"
            title="Conditions d'accès">
            🔒 <br>{!! $label !!}
        </span>
    </div>
    @endif

</div>