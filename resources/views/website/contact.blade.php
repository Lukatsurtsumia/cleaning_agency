@php
    $company = config('azurclean');
    $people = [
        ['role' => __('site.contact.manager'), 'person' => $company['manager'], 'show_phone' => false],
        ['role' => __('site.contact.assistant'), 'person' => $company['assistant'], 'show_phone' => true],
    ];
@endphp

<section id="contact" class="mx-auto max-w-7xl scroll-mt-24 px-4 py-24 sm:px-6 lg:px-8 lg:py-28">
    <div class="reveal max-w-2xl">
        <p class="section-eyebrow">{{ __('site.contact.eyebrow') }}</p>
        <h2 class="section-title">{{ __('site.contact.title') }}</h2>
        <p class="mt-4 text-base leading-relaxed text-azur-800/70">{{ __('site.contact.lead') }}</p>
    </div>

    <div class="mt-12 grid gap-8 lg:grid-cols-5 lg:gap-10">
        {{-- Reach us — call & book presented on their own, away from the form. --}}
        <aside class="reveal space-y-6 lg:col-span-2">
            <div class="rounded-3xl bg-azur-900 p-6 text-white shadow-xl sm:p-8">
                <h3 class="font-display text-2xl font-semibold">{{ __('site.contact.reach_title') }}</h3>

                <a href="tel:{{ $company['manager']['phone_href'] }}"
                   class="mt-5 flex items-center gap-4 rounded-2xl bg-white p-4 text-azur-900 shadow-lg transition hover:bg-azur-50">
                    <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-azur-700 text-white">
                        <x-icon name="phone" class="h-5 w-5"/>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-[0.7rem] font-semibold uppercase tracking-[0.14em] text-azur-800/50">
                            {{ __('site.contact.call_cta') }}
                        </span>
                        <span class="mt-0.5 block text-lg font-semibold tracking-tight">{{ $company['manager']['phone'] }}</span>
                    </span>
                </a>

                <div class="mt-6 space-y-3 border-t border-white/10 pt-6 text-sm">
                    @foreach ($people as $entry)
                        <div class="flex items-baseline justify-between gap-3">
                            <span class="text-azur-100/50">{{ $entry['role'] }}</span>
                            <a href="mailto:{{ $entry['person']['email'] }}"
                               class="truncate font-medium text-azur-100/90 transition hover:text-white">
                                {{ $entry['person']['name'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- The contact form. --}}
        <div class="reveal lg:col-span-3" style="--reveal-delay: 120ms">
            <div class="rounded-3xl border border-azur-900/10 bg-white p-6 shadow-sm sm:p-8">
                <h3 class="font-display text-2xl font-semibold text-azur-900">{{ __('site.contact.form_title') }}</h3>
                <p class="mt-1.5 text-sm text-azur-800/60">{{ __('site.contact.form_lead') }}</p>

                @if (session('contact_status'))
                    <div class="mt-5 flex items-start gap-3 rounded-2xl border border-azur-300 bg-azur-50 px-4 py-3.5 text-sm text-azur-900">
                        <x-icon name="check" class="mt-0.5 h-5 w-5 shrink-0 text-azur-600"/>
                        <span>{{ session('contact_status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}#contact" class="mt-6">
                    @csrf

                    {{-- Honeypot: hidden from people, tempting to bots. --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="contact-website">Website</label>
                        <input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form.field name="name" :label="__('site.contact.fields.name')" required autocomplete="name"/>
                        <x-form.field name="email" type="email" :label="__('site.contact.fields.email')" required autocomplete="email"/>
                        <x-form.field name="phone" type="tel" :label="__('site.contact.fields.phone')" autocomplete="tel"/>
                        <x-form.field name="subject" :label="__('site.contact.fields.subject')"/>
                        <x-form.field name="message" type="textarea" :rows="5" class="sm:col-span-2"
                                      :label="__('site.contact.fields.message')"
                                      :placeholder="__('site.contact.fields.message_placeholder')"/>
                    </div>

                    <button type="submit" class="btn-primary mt-6 w-full sm:w-auto">
                        {{ __('site.contact.submit') }}
                        <x-icon name="arrow-right" class="h-4 w-4"/>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
