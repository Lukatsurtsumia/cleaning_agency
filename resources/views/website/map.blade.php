@php
    $company = config('azurclean');
    $address = $company['address'];

    $full = "{$address['street']}, {$address['postcode']} {$address['city']}";

    // Pin the exact coordinates. Querying by business name made Google run a
    // *search*, which zoomed out across the whole Riviera and dropped pins on
    // competing cleaning firms instead of showing this address.
    $point = "{$address['lat']},{$address['lng']}";

    // Google's classic embed endpoint: no API key and no billing account needed.
    $embedUrl = "https://maps.google.com/maps?q={$point}&t=&z=17&output=embed";
    $directionsUrl = 'https://www.google.com/maps/dir/?'.http_build_query([
        'api' => 1,
        'destination' => $point,
    ]);

    $rows = [
        ['icon' => 'pin', 'label' => __('site.contact.address_label'), 'value' => $full],
        ['icon' => 'clock', 'label' => __('site.contact.hours_label'), 'value' => __('site.contact.hours_value')],
    ];
@endphp

<div class="grid grid-cols-1 overflow-hidden rounded-3xl shadow-xl ring-1 ring-azur-900/10 lg:grid-cols-5">
    <div class="relative flex flex-col justify-center gap-7 overflow-hidden bg-azur-900 p-8 sm:p-10 lg:col-span-2">
        <div class="border-b border-white/10 pb-6">
            <h3 class="font-display text-2xl font-semibold tracking-wide text-white">
                {{ $company['trading_name'] }}
            </h3>
            <p class="mt-2 text-sm text-azur-100/55">
                {{ $address['city'] }} &middot; {{ __('site.contact.area_body') }}
            </p>
        </div>

        @foreach ($rows as $row)
            <div class="flex items-start gap-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-azur-200">
                    <x-icon :name="$row['icon']" class="h-5 w-5"/>
                </span>

                <div class="min-w-0">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-white/40">
                        {{ $row['label'] }}
                    </p>

                    @isset ($row['href'])
                        <a href="{{ $row['href'] }}"
                           class="mt-0.5 block text-sm font-semibold text-white transition hover:text-azur-200">
                            {{ $row['value'] }}
                        </a>
                    @else
                        <p class="mt-0.5 text-sm font-semibold text-white">{{ $row['value'] }}</p>
                    @endisset
                </div>
            </div>
        @endforeach

        <a href="{{ $directionsUrl }}" target="_blank" rel="noopener noreferrer"
           class="relative mt-1 inline-flex w-fit items-center gap-2 rounded-full bg-azur-600 px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-azur-500">
            {{ __('site.contact.directions') }}
            <x-icon name="arrow-right" class="h-4 w-4"/>
        </a>

        <x-waves class="opacity-15"/>
    </div>

    <div class="min-h-[22rem] bg-azur-800 lg:col-span-3">
        <iframe
            src="{{ $embedUrl }}"
            title="{{ $company['trading_name'] }} - {{ $full }}"
            class="h-full min-h-[22rem] w-full"
            style="border:0"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>
