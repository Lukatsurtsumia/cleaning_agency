@php
    // Slide 0 carries the general pitch over a bright, aspirational clean-interior
    // photo. The rest reuse the four service families from site.services.items so
    // the carousel teases what's covered in detail further down the page, each
    // paired with its own real photo (public/images/hero/).
    $slides = [
        [
            'image' => 'hero/intro.jpg',
            'eyebrow' => __('site.hero.eyebrow'),
            'title_line1' => __('site.hero.title_line1'),
            'title_line2' => __('site.hero.title_line2'),
            'lead' => __('site.hero.lead'),
            'founder' => true,
        ],
        ...collect($services)
            ->reject(fn ($service) => $service['key'] === 'specifiques')
            ->values()->map(fn ($service, $i) => [
            'image' => "hero/{$service['key']}.jpg",
            'eyebrow' => __('site.services.eyebrow'),
            'title_line1' => $service['name'],
            'title_line2' => null,
            'lead' => $service['summary'],
            'founder' => false,
        ])->all(),
    ];

    $total = count($slides);
@endphp

<section id="hero"
         x-data="slideshow({{ $total }})"
         @mouseenter="stop()" @mouseleave="play()"
         @focusin="stop()" @focusout="play()"
         @touchstart.passive="onTouchStart($event)" @touchend="onTouchEnd($event)"
         aria-roledescription="carousel" aria-label="{{ __('site.hero.eyebrow') }}"
         class="relative isolate overflow-hidden bg-azur-950">

    <div class="relative min-h-[36rem] sm:min-h-[40rem] lg:min-h-[46rem]">
        @foreach ($slides as $i => $slide)
            {{-- Only Alpine controls opacity/z-index — no static opacity class, or
                 it would conflict with the :class binding and never hide slide 0.
                 Non-first slides are x-cloaked so they don't flash before Alpine. --}}
            {{-- Opacity/z-index are driven by inline :style rather than Tailwind
                 classes: utilities used only inside an Alpine expression string
                 get purged from the build, so `opacity-0` silently did nothing.
                 Non-first slides are x-cloaked so they don't flash before Alpine. --}}
            <div @if ($i > 0) x-cloak @endif
                 class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                 :style="index === {{ $i }} ? 'opacity:1;z-index:10' : 'opacity:0;z-index:0;pointer-events:none'"
                 :aria-hidden="index === {{ $i }} ? 'false' : 'true'"
                 role="group" aria-roledescription="slide" aria-label="{{ $i + 1 }} / {{ $total }}">

                {{-- Background layer: a real photo on every slide (an aspirational
                     clean interior on slide one, a photo matching the service
                     family on the rest) rather than a flat brand gradient. --}}
                <img src="{{ asset('images/'.$slide['image']) }}" alt="" aria-hidden="true"
                     fetchpriority="{{ $i === 0 ? 'high' : 'low' }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async"
                     class="absolute inset-0 h-full w-full select-none object-cover object-center">

                {{-- Scrim: a flat wash first (source photos range from a dark
                     teal hotel room to a near-white staircase, so text needs a
                     contrast floor regardless of which one is showing), then a
                     directional gradient — bottom-heavy on mobile/tablet,
                     side-heavy on desktop — weighted where the text sits. --}}
                <div class="absolute inset-0 bg-azur-950/35" aria-hidden="true"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-azur-950/90 via-azur-950/40 to-transparent lg:bg-gradient-to-r lg:from-azur-950/85 lg:via-azur-950/50 lg:to-transparent" aria-hidden="true"></div>

                <div class="relative flex h-full items-end lg:items-center">
                    <div class="mx-auto w-full max-w-7xl px-4 pb-14 sm:px-6 sm:pb-16 lg:px-8">
                        <div class="max-w-xl lg:max-w-2xl">
                            <p class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-white backdrop-blur">
                                <x-icon name="pin" class="h-3.5 w-3.5"/>
                                {{ $slide['eyebrow'] }}
                            </p>

                            <h1 class="mt-6 font-display text-[2.5rem] font-semibold leading-[1.05] text-white sm:text-5xl lg:text-7xl">
                                {{ $slide['title_line1'] }}
                                @if ($slide['title_line2'])
                                    <br><span class="italic text-azur-200">{{ $slide['title_line2'] }}</span>
                                @endif
                            </h1>

                            <p class="mt-5 max-w-xl text-base leading-relaxed text-white/80 sm:mt-6 sm:text-lg">
                                {{ $slide['lead'] }}
                            </p>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                                <a href="#contact" class="btn-primary w-full sm:w-auto"
                                   :tabindex="index === {{ $i }} ? 0 : -1">
                                    {{ __('site.hero.cta_book') }}
                                    <x-icon name="arrow-right" class="h-4 w-4"/>
                                </a>

                                <a href="#tarifs" class="btn-ghost w-full sm:w-auto"
                                   :tabindex="index === {{ $i }} ? 0 : -1">
                                    {{ __('site.hero.cta_quote') }}
                                </a>
                            </div>

                            @if ($slide['founder'])
                                <div class="mt-8 inline-flex items-center gap-3.5 rounded-2xl border border-white/15 bg-white/10 py-3 pl-3 pr-6 backdrop-blur">
                                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-azur-700">
                                        <x-icon name="sparkle" class="h-5 w-5"/>
                                    </span>
                                    <span class="leading-tight">
                                        <span class="block text-base font-semibold tracking-tight text-white">{{ config('azurclean.manager.name') }}</span>
                                        <span class="mt-0.5 block text-xs text-white/60">{{ __('site.brand.manager_role') }}</span>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Prev/next --}}
    <button type="button" @click="prev()" aria-label="{{ __('site.hero.prev') }}"
            class="absolute inset-y-0 left-0 z-20 hidden w-14 items-center justify-center text-white/70 transition hover:text-white sm:flex">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-black/10 backdrop-blur">
            <x-icon name="chevron-left" class="h-5 w-5"/>
        </span>
    </button>

    <button type="button" @click="next()" aria-label="{{ __('site.hero.next') }}"
            class="absolute inset-y-0 right-0 z-20 hidden w-14 items-center justify-center text-white/70 transition hover:text-white sm:flex">
        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-black/10 backdrop-blur">
            <x-icon name="chevron-right" class="h-5 w-5"/>
        </span>
    </button>

    {{-- Dots. The <button> carries generous padding for a ~40px tap target on
         touch screens; the thin bar itself is an inner span. --}}
    <div class="absolute inset-x-0 bottom-2 z-20 flex justify-center gap-0.5 sm:bottom-4">
        @for ($i = 0; $i < $total; $i++)
            <button type="button" @click="go({{ $i }})"
                    aria-label="{{ __('site.hero.goto', ['n' => $i + 1]) }}"
                    :aria-current="index === {{ $i }} ? 'true' : 'false'"
                    class="group flex items-center px-2 py-3">
                <span class="block h-1.5 rounded-full transition-all"
                      :class="index === {{ $i }} ? 'w-8 bg-white' : 'w-2.5 bg-white/40 group-hover:bg-white/60'"></span>
            </button>
        @endfor
    </div>
</section>
