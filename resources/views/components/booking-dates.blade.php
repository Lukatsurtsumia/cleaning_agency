@props(['booking', 'withCount' => true])

{{-- One place that decides how a booking's dates read, so the admin list, the
     detail page, the emails and the quote PDF can never drift apart. --}}
@if (! $booking->preferred_date)
    <span class="text-gray-400">&mdash;</span>
@elseif ($booking->spansMultipleDays())
    <span {{ $attributes }}>
        {{ $booking->preferred_date->translatedFormat('j M') }}
        &rarr;
        {{ $booking->endsOn()->translatedFormat('j M Y') }}
        @if ($withCount)
            <span class="whitespace-nowrap text-xs text-gray-500">
                ({{ trans_choice('site.booking.day_count', $booking->dayCount(), ['count' => $booking->dayCount()]) }})
            </span>
        @endif
    </span>
@else
    <span {{ $attributes }}>{{ $booking->preferred_date->translatedFormat('j M Y') }}</span>
@endif
