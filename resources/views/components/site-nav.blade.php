@php
    $home = route('welcome');

    $links = [
        ['href' => $home.'#services', 'label' => __('site.nav.services')],
        ['href' => $home.'#tarifs', 'label' => __('site.nav.pricing')],
        ['href' => $home.'#realisations', 'label' => __('site.nav.gallery')],
        ['href' => $home.'#a-propos', 'label' => __('site.nav.about')],
        ['href' => $home.'#contact', 'label' => __('site.nav.contact')],
    ];
@endphp

<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 24"
        class="sticky top-0 z-40 transition-colors duration-300"
        :class="scrolled || open ? 'bg-sand-50/95 shadow-sm backdrop-blur' : 'bg-transparent'">

    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:h-24 lg:px-8">
        <a href="{{ $home }}" class="shrink-0 rounded-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-azur-700">
            <span class="sr-only">{{ config('azurclean.trading_name') }}</span>
            {{-- No tagline in the bar: the mark now stacks above the wordmark
                 and a third line would not clear the 5rem header. --}}
            <x-brand.logo :show-tagline="false" class="hidden sm:inline-flex" aria-hidden="true"/>
            <x-brand.logo compact class="sm:hidden" aria-hidden="true"/>
        </a>

        <nav class="hidden items-center gap-8 lg:flex" aria-label="{{ __('site.nav.menu') }}">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                   class="text-sm font-medium tracking-wide text-azur-800 transition hover:text-azur-500">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-4 lg:flex">
            <x-locale-switcher/>

            <div class="flex items-center gap-3 border-l border-azur-900/10 pl-4">
                <a href="tel:{{ config('azurclean.manager.phone_href') }}"
                   aria-label="{{ __('site.common.call') }}"
                   class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-azur-50 text-azur-700 transition hover:bg-azur-100">
                    <x-icon name="phone" class="h-4 w-4"/>
                </a>

                <a href="{{ $home }}#contact" class="btn-primary !px-5 !py-2.5">
                    {{ __('site.nav.book') }}
                    <x-icon name="calendar" class="h-4 w-4"/>
                </a>
            </div>
        </div>

        <button type="button" @click="open = !open"
                :aria-expanded="open ? 'true' : 'false'" aria-controls="mobile-nav"
                class="inline-flex h-11 w-11 items-center justify-center rounded-full text-azur-800 transition hover:bg-azur-50 lg:hidden">
            <span class="sr-only" x-text="open ? '{{ __('site.nav.close') }}' : '{{ __('site.nav.menu') }}'"></span>
            <x-icon name="menu" x-show="!open" class="h-6 w-6"/>
            <x-icon name="close" x-show="open" x-cloak class="h-6 w-6"/>
        </button>
    </div>

    {{-- No x-transition here on purpose: it desynced x-show and left the drawer
         stuck open. A plain toggle is the reliable behaviour on mobile. --}}
    <div id="mobile-nav" x-show="open" x-cloak @click="open = false"
         class="border-t border-azur-900/10 bg-sand-50 lg:hidden">
        <nav class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6" aria-label="{{ __('site.nav.menu') }}">
            @foreach ($links as $link)
                <a href="{{ $link['href'] }}"
                   class="block rounded-xl px-4 py-3.5 text-base font-medium text-azur-900 transition hover:bg-white">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <a href="{{ $home }}#contact" class="btn-primary mt-3 w-full">
                {{ __('site.nav.book') }}
                <x-icon name="calendar" class="h-4 w-4"/>
            </a>

            <a href="tel:{{ config('azurclean.manager.phone_href') }}"
               class="btn-ghost mt-2 w-full">
                <x-icon name="phone" class="h-4 w-4"/>
                {{ __('site.common.call') }}
            </a>

            <div class="pt-3">
                <x-locale-switcher/>
            </div>
        </nav>
    </div>
</header>
