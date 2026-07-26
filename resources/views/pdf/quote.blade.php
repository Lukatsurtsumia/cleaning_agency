<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #1f2937; }
        h1 { font-size: 22px; margin-bottom: 0; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; }
        .total { font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>
    <p class="muted">Cleaning Quote #{{ $booking->id }} &mdash; {{ $booking->created_at->format('M j, Y') }}</p>

    <table>
        <tr><th>Customer</th><td>{{ $booking->name }}</td></tr>
        <tr><th>Email</th><td>{{ $booking->email }}</td></tr>
        <tr><th>Phone</th><td>{{ $booking->phone }}</td></tr>
        <tr><th>Address</th><td>{{ $booking->address ?? 'Not provided' }}</td></tr>
        <tr><th>Property type</th><td>{{ ucfirst($booking->property_type) }}</td></tr>
        <tr><th>Service</th><td>{{ ucfirst($booking->service_type) }}</td></tr>
        <tr><th>Bedrooms</th><td>{{ $booking->bedrooms }}</td></tr>
        <tr><th>Bathrooms</th><td>{{ $booking->bathrooms }}</td></tr>
        <tr><th>Extras</th><td>{{ $booking->extras ? collect($booking->extras)->map(fn ($e) => ucfirst($e))->join(', ') : 'None' }}</td></tr>
        <tr>
            <th>Dates</th>
            <td>
                @if (! $booking->preferred_date)
                    Not specified
                @elseif ($booking->spansMultipleDays())
                    {{ $booking->preferred_date->translatedFormat('j F Y') }}
                    &ndash; {{ $booking->endsOn()->translatedFormat('j F Y') }}
                    ({{ $booking->dayCount() }})
                @else
                    {{ $booking->preferred_date->translatedFormat('j F Y') }}
                @endif
            </td>
        </tr>
        <tr><th class="total">Estimated Total</th><td class="total">{{ number_format($booking->estimated_price, 2) }} &euro;</td></tr>
    </table>

    <p class="muted" style="margin-top: 30px;">This is an estimate based on the details provided. Final pricing is confirmed after we review your request.</p>
</body>
</html>
