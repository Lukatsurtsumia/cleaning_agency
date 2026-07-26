@php
    /**
     * Static decorative swell, used as a soft edge on dark panels.
     * Deliberately motionless: the hero carries the real wave photograph.
     */
    $swell = 'M0 110C120 44 240 44 360 110C480 176 600 176 720 110C840 44 960 44 1080 110C1200 176 1320 176 1440 110L1440 200L0 200Z';
    $ripple = 'M0 120C160 80 320 156 480 120C640 84 800 156 960 120C1120 84 1280 156 1440 120L1440 200L0 200Z';
    $crest = 'M0 100C90 26 300 26 480 100C660 174 800 174 960 100C1120 26 1300 26 1440 100L1440 200L0 200Z';

    $bands = [
        ['path' => $crest,  'fill' => '#b1d9d5', 'opacity' => '0.35', 'bottom' => 'bottom-10 sm:bottom-14', 'height' => 'h-20 sm:h-28'],
        ['path' => $ripple, 'fill' => '#7dbfb9', 'opacity' => '0.45', 'bottom' => 'bottom-5 sm:bottom-8',   'height' => 'h-16 sm:h-24'],
        ['path' => $swell,  'fill' => '#2d827b', 'opacity' => '0.55', 'bottom' => 'bottom-1',               'height' => 'h-16 sm:h-20'],
        ['path' => $ripple, 'fill' => '#164442', 'opacity' => '1',    'bottom' => '-bottom-1',              'height' => 'h-12 sm:h-16'],
    ];
@endphp

<div {{ $attributes->merge(['class' => 'pointer-events-none absolute inset-x-0 bottom-0 overflow-hidden']) }} aria-hidden="true">
    @foreach ($bands as $band)
        <div class="absolute inset-x-0 {{ $band['bottom'] }} {{ $band['height'] }}">
            <svg class="h-full w-full" viewBox="0 0 1440 200" preserveAspectRatio="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path d="{{ $band['path'] }}" fill="{{ $band['fill'] }}" fill-opacity="{{ $band['opacity'] }}"/>
            </svg>
        </div>
    @endforeach
</div>
