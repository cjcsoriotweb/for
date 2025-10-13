<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

    @if($back)
    <x-button-block titre="{{ $backTitle ?? 'Retour' }}" description="Retourner à la page precedente" button='
        <svg class="w-6 h-6  text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"> <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"></path> </svg>
    ' url="{{ $back }}" />

    @endif

    @foreach($navigation as $nav)


    <x-button-block titre="{{ $nav['title'] }}" description="{{ $nav['description'] }}"
        url="{{ route($nav['route'], ['team'=>$team]) }}" image="{{ $nav['image'] ?? null }}" />
    @endforeach
</section>