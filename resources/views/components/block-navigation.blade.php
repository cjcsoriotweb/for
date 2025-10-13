<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

    @if($back)
    <x-button-block titre="Retour" description="{{ url()->previous() }}" button="<---" url="{{ url()->previous() }}" />
    @endif

    @foreach($navigation as $nav)
    <x-button-block titre="{{ $nav['title'] }}" description="{{ $nav['description'] }}"
        url="{{ route($nav['route'], ['team'=>$team]) }}" />
    @endforeach
</section>