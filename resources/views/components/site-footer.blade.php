@php
    $company = config('azurclean');
    $address = $company['address'];
    $home = route('welcome');
@endphp

<footer class="relative mt-20 overflow-hidden bg-azur-900 text-azur-100">
    {{-- A last, quiet swell along the top edge. --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 h-10 rotate-180 overflow-hidden" aria-hidden="true">
        <div class="absolute inset-x-0 top-0 h-10">
            <svg class="h-full w-full" viewBox="0 0 1440 200" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120C160 80 320 156 480 120C640 84 800 156 960 120C1120 84 1280 156 1440 120L1440 200L0 200Z"
                      fill="#fdfbf6"/>
            </svg>
        </div>
    </div>

    {{-- Kept deliberately short on mobile: no tagline paragraph (redundant
         with the hero/services copy above) and no locale switcher (already
         reachable from the header nav on every breakpoint). --}}
    <div class="mx-auto grid max-w-7xl gap-6 px-4 pb-5 pt-10 sm:grid-cols-3 sm:px-6 lg:px-8">
        <div>
            <x-brand.logo full compact/>
        </div>

        <div>
            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-azur-100/45">
                {{ __('site.footer.nav_title') }}
            </h2>
            <nav class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5 text-xs">
                @foreach ([
                    $home.'#services' => __('site.nav.services'),
                    $home.'#tarifs' => __('site.nav.pricing'),
                    $home.'#realisations' => __('site.nav.gallery'),
                    $home.'#a-propos' => __('site.nav.about'),
                    $home.'#contact' => __('site.nav.book'),
                ] as $href => $label)
                    <a href="{{ $href }}" class="text-azur-100/75 transition hover:text-white">{{ $label }}</a>
                @endforeach
            </nav>
        </div>

        <div>
            <h2 class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-azur-100/45">
                {{ __('site.footer.contact_title') }}
            </h2>
            <ul class="mt-3 space-y-1.5 text-xs">
                <li>
                    <a href="tel:{{ $company['manager']['phone_href'] }}"
                       class="inline-flex items-center gap-2 text-azur-100/75 transition hover:text-white">
                        <x-icon name="phone" class="h-3.5 w-3.5 shrink-0"/>
                        {{ $company['manager']['phone'] }}
                    </a>
                </li>
                <li>
                    <a href="mailto:{{ $company['manager']['email'] }}"
                       class="inline-flex items-center gap-2 break-all text-azur-100/75 transition hover:text-white">
                        <x-icon name="mail" class="h-3.5 w-3.5 shrink-0"/>
                        {{ $company['manager']['email'] }}
                    </a>
                </li>
                <li class="flex items-start gap-2 text-azur-100/65">
                    <x-icon name="pin" class="mt-0.5 h-3.5 w-3.5 shrink-0"/>
                    <span>{{ $address['street'] }}, {{ $address['postcode'] }} {{ $address['city'] }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Legal identity (name + RCS) folded into the fine print rather than
         its own heading/column — it only needs to exist, not to be prominent. --}}
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-3 text-[0.7rem] tracking-wide text-azur-100/45 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>
                {{ $company['legal_name'] }} &middot; {{ $company['rcs'] }} &middot;
                &copy; {{ now()->year }} {{ __('site.footer.rights') }}
            </p>

            <div class="flex items-center gap-3">
                <span>{{ __('site.footer.credit') }}</span>
                <span class="text-azur-100/20" aria-hidden="true">&middot;</span>
                @auth
                    <a href="{{ route('dashboard') }}" class="transition hover:text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="transition hover:text-white">Administration</a>
                @endauth
            </div>
        </div>
    </div>
</footer>
