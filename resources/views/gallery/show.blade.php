@php
    $label = collect(__('site.services.items'))->firstWhere('key', $gallery->category)['name']
        ?? Str::headline($gallery->category);

    // Build the photo set: real uploads if any, otherwise the category's own
    // set of stock photos (public/images/gallery/<category>[-2|-3].jpg).
    $photos = [];
    if ($gallery->cover_image) {
        $photos[] = asset('storage/'.$gallery->cover_image);
    }
    foreach ($gallery->images as $image) {
        $photos[] = asset('storage/'.$image->path);
    }
    if (empty($photos)) {
        foreach (['', '-2', '-3'] as $suffix) {
            $p = 'images/gallery/'.$gallery->category.$suffix.'.jpg';
            if (file_exists(public_path($p))) {
                $photos[] = asset($p);
            }
        }
    }
@endphp

<x-site-layout :title="$gallery->title" :description="$gallery->description">
    <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">

        {{-- Clean back-to-home control (replaces the old bottom text link). --}}
        <a href="{{ route('welcome') }}#realisations"
           class="inline-flex items-center gap-2 rounded-full border border-azur-900/10 bg-white px-4 py-2.5 text-sm font-semibold text-azur-700 shadow-sm transition hover:-translate-x-0.5 hover:border-azur-700/30 hover:text-azur-900">
            <x-icon name="chevron-left" class="h-4 w-4"/>
            {{ __('site.gallery.back') }}
        </a>

        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-azur-300 bg-azur-50 px-5 py-3.5 text-sm text-azur-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 max-w-3xl">
            <p class="section-eyebrow">{{ $label }}</p>
            <h1 class="mt-3 font-display text-4xl font-semibold leading-tight text-azur-900 sm:text-5xl">
                {{ $gallery->title }}
            </h1>
            @if ($gallery->description)
                <p class="mt-4 text-base leading-relaxed text-azur-800/75">{{ $gallery->description }}</p>
            @endif
        </div>

        {{-- The section's gallery: a large lead photo, then the rest in a grid. --}}
        @if (count($photos))
            <div class="mt-10 space-y-6">
                <img src="{{ $photos[0] }}" alt="{{ $gallery->title }}"
                     class="aspect-[16/9] w-full rounded-3xl object-cover shadow-lg ring-1 ring-azur-900/5">

                @if (count($photos) > 1)
                    <div class="grid gap-6 sm:grid-cols-2">
                        @foreach (array_slice($photos, 1) as $photo)
                            <img src="{{ $photo }}" alt="{{ $gallery->title }}" loading="lazy"
                                 class="aspect-[4/3] w-full rounded-3xl object-cover shadow-md ring-1 ring-azur-900/5">
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @auth
            <div class="mt-10 flex items-center gap-4 border-t border-azur-900/10 pt-6">
                <a href="{{ route('gallery.edit', $gallery) }}"
                   class="text-sm font-semibold text-azur-700 hover:text-azur-900">Modifier</a>
                <form method="POST" action="{{ route('gallery.destroy', $gallery) }}"
                      onsubmit="return confirm('Supprimer ce chantier ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">
                        Supprimer
                    </button>
                </form>
            </div>
        @endauth
    </div>
</x-site-layout>
