@props(['tone' => 'color', 'compact' => false, 'showTagline' => true, 'full' => false, 'mark' => false])

@php
    // mark  = the wave-and-house icon only, transparent PNG (no frame) - header
    // full  = the complete lockup (wordmark + tagline + wave) - footer
    // else  = the wide wordmark
    $src = $mark ? 'images/logo-mark.png' : ($full ? 'images/logo-full.png' : 'images/logo-wordmark.png');
    $height = $mark
        ? ($compact ? 'h-11' : 'h-12')
        : ($full ? ($compact ? 'h-12' : 'h-14') : ($compact ? 'h-8' : 'h-10'));
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex']) }}>
    @if ($mark)
        {{-- Transparent icon, no frame - reads on the hero photo and the header. --}}
        <img src="{{ asset($src) }}?v=3" alt="{{ config('azurclean.trading_name') }}" decoding="async"
             class="w-auto select-none {{ $height }}">
    @else
        {{-- Wordmark/full lockup sit on a soft white badge so the dark text reads
             on any surface (light header, dark footer). --}}
        <span class="inline-flex items-center rounded-2xl bg-white shadow-sm ring-1 ring-azur-900/10 {{ $full ? 'p-2' : 'px-3.5 py-2.5' }}">
            <img src="{{ asset($src) }}?v=3" alt="{{ config('azurclean.trading_name') }}" decoding="async"
                 class="w-auto select-none {{ $height }}">
        </span>
    @endif
</span>
