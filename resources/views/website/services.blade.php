<section id="services" class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8 lg:py-28">
    <div class="reveal max-w-2xl">
        <p class="section-eyebrow">{{ __('site.services.eyebrow') }}</p>
        <h2 class="section-title">{{ __('site.services.title') }}</h2>
        <p class="mt-4 text-base leading-relaxed text-azur-800/70">{{ __('site.services.lead') }}</p>
    </div>

    <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($services as $service)
            <div class="reveal" style="--reveal-delay: {{ $loop->index * 90 }}ms">
            <article class="card flex h-full flex-col border-t-4 {{ ['border-t-azur-500', 'border-t-azur-600', 'border-t-azur-700', 'border-t-azur-400'][$loop->index % 4] }}">
                <div class="flex items-start justify-between">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-azur-700 text-white shadow-sm">
                        <x-icon :name="$service['icon']" class="h-6 w-6"/>
                    </span>
                    <span class="font-display text-xl font-semibold text-azur-900/15" aria-hidden="true">
                        {{ sprintf('%02d', $loop->iteration) }}
                    </span>
                </div>

                <h3 class="mt-5 font-display text-2xl font-semibold leading-snug text-azur-900">
                    {{ $service['name'] }}
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-azur-800/70">{{ $service['summary'] }}</p>

                <ul class="mt-5 space-y-2 border-t border-azur-900/10 pt-5">
                    @foreach ($service['points'] as $point)
                        <li class="flex items-start gap-2.5 text-sm text-azur-800/80">
                            <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-azur-500"/>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </article>
            </div>
        @endforeach
    </div>
</section>
