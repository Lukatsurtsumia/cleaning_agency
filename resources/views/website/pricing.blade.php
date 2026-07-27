<section id="tarifs" class="relative overflow-hidden bg-azur-900 py-24 lg:py-28">
    {{-- Subtle dot-grid texture instead of a stock photo: on-brand and never
         fights the card content for attention. --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(255,255,255,0.08)_1px,transparent_0)] [background-size:28px_28px]" aria-hidden="true"></div>

    <div class="pointer-events-none absolute -left-24 top-1/3 h-96 w-96 rounded-full bg-azur-600/25 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-azur-400/15 blur-3xl" aria-hidden="true"></div>
    <x-waves class="opacity-20"/>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-azur-300">
                {{ __('site.pricing.eyebrow') }}
            </p>
            <h2 class="mt-3 font-display text-4xl font-semibold leading-tight text-white sm:text-5xl">
                {{ __('site.pricing.title') }}
            </h2>
            <p class="mt-4 text-base leading-relaxed text-azur-100/70">{{ __('site.pricing.lead') }}</p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach ($pricing as $tier)
                <div class="reveal" style="--reveal-delay: {{ $loop->index * 110 }}ms">
                <article class="relative flex h-full flex-col rounded-2xl p-7 transition
                                {{ $tier['featured']
                                     ? 'bg-white shadow-2xl shadow-black/20 ring-2 ring-azur-300 lg:-translate-y-3'
                                     : 'bg-white/5 ring-1 ring-white/15 backdrop-blur hover:bg-white/10' }}">

                    @if ($tier['featured'])
                        <span class="absolute -top-3 left-7 rounded-full bg-azur-600 px-3 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.14em] text-white">
                            {{ __('site.pricing.popular') }}
                        </span>
                    @endif

                    <h3 class="font-display text-2xl font-semibold {{ $tier['featured'] ? 'text-azur-900' : 'text-white' }}">
                        {{ $tier['name'] }}
                    </h3>
                    <p class="mt-1 text-sm {{ $tier['featured'] ? 'text-azur-800/60' : 'text-azur-100/60' }}">
                        {{ $tier['for'] }}
                    </p>

                    <div class="mt-6 flex items-baseline gap-1.5">
                        @if ($tier['from'] !== null)
                            <span class="text-xs font-medium uppercase tracking-wide {{ $tier['featured'] ? 'text-azur-800/60' : 'text-azur-100/60' }}">
                                {{ __('site.pricing.from') }}
                            </span>
                            <span class="font-display text-5xl font-semibold {{ $tier['featured'] ? 'text-azur-900' : 'text-white' }}">
                                {{ $tier['from'] }}&nbsp;&euro;
                            </span>
                            <span class="text-sm {{ $tier['featured'] ? 'text-azur-800/60' : 'text-azur-100/60' }}">
                                {{ __('site.pricing.per_hour') }}
                            </span>
                        @else
                            <span class="font-display text-4xl font-semibold {{ $tier['featured'] ? 'text-azur-900' : 'text-white' }}">
                                {{ __('site.pricing.on_quote') }}
                            </span>
                        @endif
                    </div>

                    <ul class="mt-6 flex-1 space-y-2.5 border-t pt-6 {{ $tier['featured'] ? 'border-azur-900/10' : 'border-white/15' }}">
                        @foreach ($tier['includes'] as $line)
                            <li class="flex items-start gap-2.5 text-sm {{ $tier['featured'] ? 'text-azur-800/80' : 'text-azur-100/80' }}">
                                <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 {{ $tier['featured'] ? 'text-azur-500' : 'text-azur-300' }}"/>
                                {{ $line }}
                            </li>
                        @endforeach
                    </ul>

                    <a href="#contact"
                       class="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-full px-6 py-3.5 text-sm font-semibold transition
                              {{ $tier['featured']
                                   ? 'bg-azur-700 text-white hover:bg-azur-800'
                                   : 'bg-white/10 text-white ring-1 ring-white/25 hover:bg-white/20' }}">
                        {{ __('site.pricing.cta') }}
                        <x-icon name="arrow-right" class="h-4 w-4"/>
                    </a>
                </article>
                </div>
            @endforeach
        </div>

        <p class="mt-8 max-w-2xl text-xs leading-relaxed text-azur-100/50">
            {{ __('site.pricing.note') }}
        </p>
    </div>
</section>
