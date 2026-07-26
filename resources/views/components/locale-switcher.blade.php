@php
    $locales = ['fr' => 'FR', 'en' => 'EN'];
    $current = app()->getLocale();
@endphp

{{-- Segmented FR / EN toggle. The gap keeps the two labels from mashing into
     "FREN", and the active segment gets a solid pill so the current language
     reads at a glance. --}}
<div class="inline-flex items-center gap-1 rounded-full border border-azur-900/10 bg-white/80 p-1 shadow-sm backdrop-blur"
     role="group" aria-label="{{ __('site.nav.lang_label') }}">
    @foreach ($locales as $code => $label)
        <a href="{{ route('locale.switch', $code) }}"
           @if ($code === $current) aria-current="true" @endif
           class="inline-flex min-w-[2.25rem] items-center justify-center rounded-full px-3 py-1.5 text-xs font-semibold tracking-wide transition
                  {{ $code === $current
                       ? 'bg-azur-700 text-white shadow-sm'
                       : 'text-azur-700/70 hover:bg-azur-50 hover:text-azur-900' }}">
            {{ $label }}
        </a>
    @endforeach
</div>
