@props(['tone' => 'color'])

{{-- Tina's own painted double-crested wave, lifted off the visit card with the
     paper keyed out. `light` is the same artwork re-inked pale for dark panels,
     where the original dark teal would disappear. --}}
<img src="{{ asset($tone === 'light' ? 'images/logo-wave-light.webp' : 'images/logo-wave.webp') }}"
     alt="" aria-hidden="true" decoding="async"
     width="564" height="198"
     {{ $attributes->merge(['class' => 'h-10 w-auto select-none']) }}>
