@if($back)
<p class="mb-5">
    <a href="{{ $back }}"
       class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
       {{ $backTitle ?? 'Retour' }}
    </a>
</p>
@endif

@if(isset($title))
<h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>
@endif

@php use App\Support\RouteAccess; @endphp

    @if(isset($nav['hasTeamRoleOrPermission']))
        {{ $nav['hasTeamRoleOrPermission'] }}
        @if(!Auth::user()->hasTeamRole($team, $nav['hasTeamRoleOrPermission']) || !Auth::user()->hasTeamPermission($team, $nav['hasTeamRoleOrPermission']))
            {{ $visible = false }}
        @endif
    @endif
@endforeach
</section>

<hr>