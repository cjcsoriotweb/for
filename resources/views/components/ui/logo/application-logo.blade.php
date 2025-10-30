{{-- resources/views/components/logo-img.blade.php --}}
@props([
    // Fichier dans public/ (ex: logo.png, images/brand.png)
    'src'   => 'logo.png',

    // Taille prédéfinie OU valeur numérique en px (ex: '3xl' ou 256)
    'size'  => 'md',  // xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|full | int

    // Texte alternatif
    'label' => config('app.name', 'App').' logo',

    // Mode de remplissage CSS object-fit
    'fit'   => 'contain', // contain|cover|fill|none|scale-down

    // Coins arrondis (optionnel)
    'rounded' => 'none', // none|sm|md|lg|xl|2xl|full
])

@php
    $presets = [
        'xs'   => ['w' => 20,  'h' => 20 ],   // 5 * 4
        'sm'   => ['w' => 24,  'h' => 24 ],
        'md'   => ['w' => 32,  'h' => 32 ],
        'lg'   => ['w' => 48,  'h' => 48 ],
        'xl'   => ['w' => 64,  'h' => 64 ],
        '2xl'  => ['w' => 96,  'h' => 96 ],
        '3xl'  => ['w' => 128, 'h' => 128],
        '4xl'  => ['w' => 192, 'h' => 192],
        '5xl'  => ['w' => 256, 'h' => 256],
        'full' => ['w' => null, 'h' => null],
    ];

    $isNumeric = is_numeric($size);
    $dim = $isNumeric
        ? ['w' => (int) $size, 'h' => (int) $size]
        : ($presets[$size] ?? $presets['md']);

    // Classes utilitaires
    $objectFit = match($fit) {
        'cover' => 'object-cover',
        'fill' => 'object-fill',
        'none' => 'object-none',
        'scale-down' => 'object-scale-down',
        default => 'object-contain',
    };

    $roundClass = match($rounded) {
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        'full' => 'rounded-full',
        default => '',
    };

    // Style width/height si taille numérique, sinon Tailwind gèrera via classes externes si besoin
    $style = '';
    if ($isNumeric) {
        $style = "width: {$dim['w']}px; height: {$dim['h']}px;";
    } elseif ($size !== 'full' && $dim['w'] && $dim['h']) {
        $style = "width: {$dim['w']}px; height: {$dim['h']}px;";
    }

    // URL publique
    $url = asset($src);
@endphp

<img
    src="{{ $url }}"
    alt="{{ $label }}"
    loading="lazy"
    decoding="async"
    fetchpriority="auto"
    {{ $attributes->merge([
        'class' => trim("inline-block {$objectFit} {$roundClass}"),
        'style' => $style,
    ]) }}
/>
