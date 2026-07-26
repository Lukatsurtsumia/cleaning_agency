@props(['tone' => 'color', 'compact' => false, 'showTagline' => true])

@php
    $invert = $tone === 'invert';
@endphp

{{-- The mark is a wide horizontal wave, so it stacks above the wordmark rather
     than sitting beside it, which is also how it reads on the printed card. --}}
<span {{ $attributes->merge(['class' => 'inline-flex flex-col items-start leading-none']) }}>
    <x-brand.mark :tone="$invert ? 'light' : 'color'"
                  class="{{ $compact ? 'h-6' : 'h-8' }} w-auto"/>

    <span class="mt-1.5 font-display {{ $compact ? 'text-base' : 'text-xl' }} font-semibold tracking-wide
                 {{ $invert ? 'text-white' : 'text-azur-900' }}">
        Azur Clean <span class="italic">Tinati</span>
    </span>

    @if ($showTagline && ! $compact)
        <span class="mt-1 text-[0.6rem] font-medium uppercase tracking-[0.18em]
                     {{ $invert ? 'text-white/70' : 'text-azur-600' }}">
            {{ __('site.brand.tagline') }}
        </span>
    @endif
</span>
