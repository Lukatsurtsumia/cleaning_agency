<section id="a-propos" class="bg-sand-100 py-24 lg:py-28">
    <div class="reveal mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <p class="section-eyebrow inline-flex">{{ __('site.about.eyebrow') }}</p>
        <h2 class="section-title">{{ __('site.about.title') }}</h2>

        <figure class="mx-auto mt-8 max-w-xl border-l-2 border-azur-500 pl-5 text-left">
            <blockquote class="font-display text-2xl italic leading-snug text-azur-800">
                {{ __('site.about.quote') }}
            </blockquote>
            <figcaption class="mt-3 text-sm font-medium text-azur-600">
                {{ config('azurclean.manager.name') }} &middot; {{ __('site.brand.manager_role') }}
            </figcaption>
        </figure>

        <p class="mx-auto mt-8 max-w-xl text-left text-base leading-relaxed text-azur-800/80">
            {{ __('site.about.body') }}
        </p>

        <dl class="mt-10 flex items-center justify-center divide-x divide-azur-900/10">
            @foreach (__('site.about.stats') as $stat)
                <div class="px-6 first:pl-0 last:pr-0">
                    <dd class="font-display text-2xl font-semibold text-azur-800">{{ $stat['value'] }}</dd>
                    <dt class="mt-1 text-[0.7rem] leading-tight text-azur-800/60">{{ $stat['label'] }}</dt>
                </div>
            @endforeach
        </dl>

        <div class="mt-10 inline-flex items-center gap-2 rounded-full bg-azur-800 px-5 py-2.5 text-xs font-semibold uppercase tracking-wide text-white">
            <x-icon name="leaf" class="h-4 w-4"/>
            {{ __('site.about.eco_title') }}
        </div>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-azur-800/60">
            {{ __('site.about.eco_body') }}
        </p>
    </div>
</section>
