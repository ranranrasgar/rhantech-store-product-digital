@props([
    'user' => null,
    'src' => null,
    'name' => null,
    'size' => null,
])

@php
    $u = $user ?? auth()->user();
    $userName = $name ?? ($u->name ?? 'User');
    $initial = strtoupper(mb_substr($userName, 0, 1) ?: 'U');
    
    // Resolve primary avatar source
    $avatarSrc = $src;
    if (!$avatarSrc && $u) {
        $avatarSrc = $u->avatar_url;
    } elseif (!$avatarSrc) {
        $avatarSrc = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0284c7&color=fff&bold=true';
    }

    $fallbackUi = 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=0284c7&color=fff&bold=true';
    $fallbackSvg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><rect width="40" height="40" rx="20" fill="#ea580c"/><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-family="sans-serif" font-weight="bold" font-size="18">' . $initial . '</text></svg>');
@endphp

<img 
    src="{{ $avatarSrc }}" 
    alt="{{ $userName }}"
    referrerpolicy="no-referrer"
    loading="lazy"
    onerror="if(!this.dataset.fallbackState){this.dataset.fallbackState='ui';this.src='{{ $fallbackUi }}';}else if(this.dataset.fallbackState==='ui'){this.dataset.fallbackState='svg';this.src='{{ $fallbackSvg }}';}else{this.onerror=null;}"
    {{ $attributes->merge(['class' => 'rounded-full object-cover ' . ($size ?? 'w-8 h-8')]) }}
>
