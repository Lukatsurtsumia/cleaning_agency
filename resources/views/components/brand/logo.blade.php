@props(['tone' => 'color', 'compact' => false, 'showTagline' => true, 'full' => false, 'mark' => false])

@php
    // mark  = the wave-and-house icon only (no text) - used in the header
    // full  = the complete lockup (wordmark + tagline + wave) - footer
    // else  = the wide wordmark
    $src = $mark ? 'images/logo-mark.png' : ($full ? 'images/logo-full.png' : 'images/logo-wordmark.png');
    $pad = $mark ? 'p-1.5' : ($full ? 'p-2' : 'px-3.5 py-2.5');
    $height = $mark
        ? ($compact ? 'h-10' : 'h-12')
        : ($full ? ($compact ? 'h-12' : 'h-14') : ($compact ? 'h-8' : 'h-10'));
@endphp

{{-- Tina's brand logo on a soft white badge, so it reads cleanly on any
     surface: the transparent header over the hero photo, the light header once
     scrolled, and the dark footer. --}}
<span {{ $attributes->merge(['class' => 'inline-flex']) }}>
    <span class="inline-flex items-center rounded-2xl bg-white shadow-sm ring-1 ring-azur-900/10 {{ $pad }}">
        <img src="{{ asset($src) }}" alt="{{ config('azurclean.trading_name') }}" decoding="async"
             class="w-auto select-none {{ $height }}">
    </span>
</span>
