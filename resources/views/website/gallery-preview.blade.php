@php
    // Placeholder art (no real jobs photographed yet): each category gets its
    // own icon and gradient, echoing the service family it belongs to, so the
    // four cards read as distinct rather than four copies of the same tile.
    $placeholders = [
        'hotelier' => ['icon' => 'bed', 'gradient' => 'from-azur-700 to-azur-400'],
        'immeubles' => ['icon' => 'building', 'gradient' => 'from-azur-800 to-azur-500'],
        'bureaux' => ['icon' => 'briefcase', 'gradient' => 'from-azur-900 to-azur-600'],
        'specifiques' => ['icon' => 'spray', 'gradient' => 'from-azur-600 to-azur-300'],
    ];

    $serviceNames = collect(__('site.services.items'))->pluck('name', 'key');
@endphp

<section id="realisations" class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8 lg:py-28">
    {{-- No "view all" link: the cards themselves are the way in — click a photo
         to open that section's gallery. --}}
    <div class="reveal max-w-2xl">
        <p class="section-eyebrow">{{ __('site.gallery.eyebrow') }}</p>
        <h2 class="section-title">{{ __('site.gallery.title') }}</h2>
        <p class="mt-4 text-base leading-relaxed text-azur-800/70">{{ __('site.gallery.lead') }}</p>
    </div>

    @if ($galleries->isEmpty())
        <div class="mt-10 rounded-2xl border border-dashed border-azur-900/20 bg-white/60 px-6 py-16 text-center">
            <x-icon name="sparkle" class="mx-auto h-8 w-8 text-azur-400"/>
            <p class="mt-3 text-sm text-azur-800/60">{{ __('site.gallery.empty') }}</p>
        </div>
    @else
        {{-- Fewer, larger cards (two across) for a more premium feel. Each card
             is one link to that project's own gallery page. The overlay is a
             soft bottom gradient so the photo stays bright rather than murky. --}}
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:gap-8">
            @foreach ($galleries as $gallery)
                @php
                    $placeholder = $placeholders[$gallery->category] ?? $placeholders['specifiques'];
                    // Real client photos come from cover_image; until then each
                    // category shows a representative stock photo (public/images/gallery/).
                    $catPhoto = 'images/gallery/'.$gallery->category.'.jpg';
                @endphp
                <div class="reveal" style="--reveal-delay: {{ $loop->index * 100 }}ms">
                <a href="{{ route('gallery.show', $gallery) }}"
                   class="group relative block overflow-hidden rounded-3xl bg-azur-900 shadow-md ring-1 ring-azur-900/5 transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                    <div class="aspect-[16/10] overflow-hidden">
                        @if ($gallery->cover_image)
                            <img src="{{ asset('storage/'.$gallery->cover_image) }}"
                                 alt="{{ $gallery->title }}" loading="lazy"
                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        @elseif (file_exists(public_path($catPhoto)))
                            <img src="{{ asset($catPhoto) }}"
                                 alt="{{ $gallery->title }}" loading="lazy"
                                 class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        @else
                            <div class="relative flex h-full w-full items-center justify-center overflow-hidden bg-gradient-to-br {{ $placeholder['gradient'] }}">
                                <x-icon :name="$placeholder['icon']" class="h-20 w-20 text-white/25 transition duration-500 group-hover:scale-110" aria-hidden="true"/>
                            </div>
                        @endif
                    </div>

                    {{-- Soft, bottom-weighted scrim — keeps the image bright. --}}
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-azur-950/80 via-azur-950/15 to-transparent" aria-hidden="true"></div>

                    <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-4 p-6 sm:p-7">
                        <div>
                            <p class="text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-azur-200">
                                {{ $serviceNames[$gallery->category] ?? $gallery->category }}
                            </p>
                            <h3 class="mt-1.5 font-display text-2xl font-semibold leading-tight text-white">{{ $gallery->title }}</h3>
                        </div>

                        <span class="mb-1 inline-flex h-10 w-10 shrink-0 translate-y-1 items-center justify-center rounded-full bg-white/15 text-white opacity-0 backdrop-blur transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            <x-icon name="arrow-right" class="h-5 w-5"/>
                        </span>
                    </div>
                </a>
                </div>
            @endforeach
        </div>
    @endif
</section>
