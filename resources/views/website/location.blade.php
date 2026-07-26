{{-- Location is its own section, separate from the contact form. --}}
<section id="localisation" class="bg-sand-100/60 py-24 lg:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="section-eyebrow">{{ __('site.location.eyebrow') }}</p>
            <h2 class="section-title">{{ __('site.location.title') }}</h2>
            <p class="mt-4 text-base leading-relaxed text-azur-800/70">{{ __('site.location.lead') }}</p>
        </div>

        <div class="mt-12">
            @include('website.map')
        </div>
    </div>
</section>
