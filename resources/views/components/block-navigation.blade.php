@if($back)
<p class="mb-5">
    <a href="{{ $back }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">{{
        $backTitle ?? 'Retour' }}</a>
</p>
@endif
<section style="position:relative;" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">

    @foreach($navigation as $nav)
    @php
    $visible = true;
    @endphp


    @if(isset($nav['hasTeamRole']))
        @if(!Auth::user()->hasTeamRole($team, $nav['hasTeamRole']))
        {{ $visible = false }}
        @endif
    @endif

    @if(config('app.debug'))
        @if(isset($nav['hasTeamRole']))
        {{ $nav['hasTeamRole'] }}
        @else
        X
        @endif
    @endif

    @if($visible)
    <x-button-block titre="{{ $nav['title'] }}" description="{{ $nav['description'] }}"
        url="{{ route($nav['route'], ['team'=>$team]) }}" image="{{ $nav['image'] ?? null }}" />
    @endif

    @endforeach

</section>