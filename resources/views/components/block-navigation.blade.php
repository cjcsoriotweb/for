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

<<<<<<< Updated upstream
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-1 py-2">
@foreach($navigation as $nav)
    @php
        $params    = array_merge(['team' => $team], $nav['params'] ?? []);
        $inspect   = RouteAccess::inspect($nav['route'], $params, auth()->user(), false);

        $allowed   = (bool) ($inspect['allowed'] ?? false);
        $hasGuards = collect($inspect['details'] ?? [])->whereIn('type', ['can','permission','role'])->isNotEmpty();
        $label     = $hasGuards ? implode(' <br> ', $inspect['labels'] ?? []) : null;

        $debug        = (bool) config('app.debug');
        $shouldRender = $allowed || ($debug && !$allowed);
        $isDisabled   = !$allowed;
        $isActive     = request()->routeIs($nav['route']);

        // ✅ calcule les props une fois
        $urlProp      = $isDisabled ? null : route($nav['route'], $params);
        $disabledProp = $isDisabled;
        $tooltipProp  = $isDisabled ? ($label ? 'Accès requis : '.$label : 'Accès non autorisé') : null;
    @endphp

    @if($shouldRender)
        <x-button-block
            :active="$isActive"
            :titre="$nav['title']"
            :description="$nav['description']"
            :image="$nav['image'] ?? null"
            :label="$label"
            :url="$urlProp"
            :disabled="$disabledProp"
            :tooltip="$tooltipProp"
        />
=======
    @if(isset($nav['hasTeamRoleOrPermission']))
        {{ $nav['hasTeamRoleOrPermission'] }}
        @if(!Auth::user()->hasTeamRole($team, $nav['hasTeamRoleOrPermission']) || !Auth::user()->hasTeamPermission($team, $nav['hasTeamRoleOrPermission']))
            {{ $visible = false }}
        @endif
>>>>>>> Stashed changes
    @endif
@endforeach
</section>

<hr>