@props([
    // Taille prédéfinie OU valeur numérique en pixels
    // Ex: size="3xl" | size="full" | size=256
    'size' => 'md',          // xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|full
    'label' => config('app.name', 'App').' logo',
])

@php
    // Classes Tailwind prêtes à l'emploi
    $presets = [
        'xs'   => 'w-5 h-5',
        'sm'   => 'w-6 h-6',
        'md'   => 'w-8 h-8',
        'lg'   => 'w-12 h-12',
        'xl'   => 'w-16 h-16',    // 64px
        '2xl'  => 'w-24 h-24',    // 96px
        '3xl'  => 'w-32 h-32',    // 128px
        '4xl'  => 'w-48 h-48',    // 192px
        '5xl'  => 'w-64 h-64',    // 256px
        'full' => 'w-full h-auto' // s’adapte au conteneur (responsive)
    ];

    $svgPath = public_path('logo.svg');
    $svg = is_file($svgPath) ? file_get_contents($svgPath) : null;

    // Détermine classe/inline-style selon le type de "size"
    $dimClass = '';
    $dimStyle = '';

    if (is_numeric($size)) {
        // Taille exacte en pixels (ex: size=512)
        $px = (int) $size;
        $dimStyle = "width: {$px}px; height: {$px}px;";
    } else {
        $dimClass = $presets[$size] ?? $presets['md'];
    }

    if ($svg) {
        // Injecte classes + style + a11y dans la 1ère <svg>
        $classAttr = trim(($dimClass ? $dimClass.' ' : '').'fill-current');
        $styleAttr = $dimStyle ? ' style="'.$dimStyle.'"' : '';

        $svg = preg_replace(
            '/<svg\b([^>]*)>/i',
            '<svg$1 class="'.$classAttr.'" role="img" aria-label="'.e($label).'"'.$styleAttr.'>',
            $svg,
            1
        );
        // Option : décommenter pour forcer toute la couleur à suivre currentColor
        // $svg = preg_replace('/\sfill="[^"]*"/i', ' fill="currentColor"', $svg);
    }
@endphp

@if($svg)
    <span {{ $attributes->merge(['class' => 'inline-block leading-none']) }}>
        {!! $svg !!}
    </span>
@else
    <div {{ $attributes->merge(['class' => ($presets['md']).' bg-slate-300/20 rounded']) }} aria-label="Logo placeholder"></div>
@endif
