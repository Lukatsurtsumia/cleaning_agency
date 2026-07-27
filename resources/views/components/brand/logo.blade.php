@props(['tone' => 'color', 'compact' => false, 'showTagline' => true, 'full' => false])

{{-- Tina's real brand logo (script wordmark + "Propreté & Sérénité" + the
     wave-and-house mark). It sits on a white background, so it rides in a soft
     white badge that reads cleanly on any surface: the transparent header over
     the hero photo, the light header once scrolled, and the dark footer.
     `full` shows the complete lockup (with the wave); the default is the wide
     wordmark, which fits a horizontal header without shrinking the text. --}}
<span {{ $attributes->merge(['class' => 'inline-flex']) }}>
    <span class="inline-flex items-center rounded-2xl bg-white shadow-sm ring-1 ring-azur-900/10
                 {{ $full ? 'p-2' : 'px-3.5 py-2.5' }}">
        <img src="{{ asset($full ? 'images/logo-full.png' : 'images/logo-wordmark.png') }}"
             alt="{{ config('azurclean.trading_name') }}" decoding="async"
             class="w-auto select-none
                    {{ $full ? ($compact ? 'h-12' : 'h-14') : ($compact ? 'h-8' : 'h-10') }}">
    </span>
</span>
