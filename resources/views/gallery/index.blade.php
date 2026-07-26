@php
    // Category keys match the service families, so reuse their translated names.
    $label = fn (string $key) => collect(__('site.services.items'))->firstWhere('key', $key)['name']
        ?? Str::headline($key);
@endphp

<x-site-layout :title="__('site.gallery.title')">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <p class="section-eyebrow">{{ __('site.gallery.eyebrow') }}</p>
                <h1 class="section-title">{{ __('site.gallery.title') }}</h1>
                <p class="mt-4 text-base leading-relaxed text-azur-800/70">{{ __('site.gallery.lead') }}</p>
            </div>

            @auth
                <a href="{{ route('gallery.create') }}" class="btn-primary shrink-0">
                    Ajouter un chantier
                </a>
            @endauth
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-azur-300 bg-azur-50 px-5 py-3.5 text-sm text-azur-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 flex flex-wrap gap-2">
            <a href="{{ route('gallery.index') }}"
               class="rounded-full px-4 py-2 text-sm font-medium transition
                      {{ ! $activeCategory ? 'bg-azur-700 text-white' : 'bg-white text-azur-800 ring-1 ring-azur-900/10 hover:bg-azur-50' }}">
                {{ __('site.gallery.all') }}
            </a>

            @foreach ($categories as $category)
                <a href="{{ route('gallery.index', ['category' => $category]) }}"
                   class="rounded-full px-4 py-2 text-sm font-medium transition
                          {{ $activeCategory === $category ? 'bg-azur-700 text-white' : 'bg-white text-azur-800 ring-1 ring-azur-900/10 hover:bg-azur-50' }}">
                    {{ $label($category) }}
                </a>
            @endforeach
        </div>

        @if ($galleries->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-azur-900/20 bg-white/60 px-6 py-16 text-center">
                <x-icon name="sparkle" class="mx-auto h-8 w-8 text-azur-400"/>
                <p class="mt-3 text-sm text-azur-800/60">{{ __('site.gallery.empty') }}</p>
            </div>
        @else
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($galleries as $gallery)
                    <a href="{{ route('gallery.show', $gallery) }}"
                       class="group relative block overflow-hidden rounded-2xl bg-azur-900 shadow-sm transition hover:shadow-xl">
                        @php $catPhoto = 'images/gallery/'.$gallery->category.'.jpg'; @endphp
                        <div class="aspect-[4/3] overflow-hidden">
                            @if ($gallery->cover_image)
                                <img src="{{ asset('storage/'.$gallery->cover_image) }}"
                                     alt="{{ $gallery->title }}" loading="lazy"
                                     class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @elseif (file_exists(public_path($catPhoto)))
                                <img src="{{ asset($catPhoto) }}"
                                     alt="{{ $gallery->title }}" loading="lazy"
                                     class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-azur-200 to-azur-500">
                                    <x-brand.mark tone="light" class="h-16 w-auto opacity-80"/>
                                </div>
                            @endif
                        </div>

                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-azur-950/85 via-azur-950/40 to-transparent p-5 pt-14">
                            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-azur-200">
                                {{ $label($gallery->category) }}
                            </p>
                            <h2 class="mt-1 font-display text-xl font-semibold text-white">{{ $gallery->title }}</h2>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</x-site-layout>
