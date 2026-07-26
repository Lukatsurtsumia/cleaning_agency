@php
    $serviceName = collect(__('site.services.items'))->firstWhere('key', $booking->service_type)['name'] ?? $booking->service_type;
    $propertyName = __('site.booking.property_types.'.$booking->property_type);
@endphp
@component('mail::message')
# Merci, {{ $booking->name }} !

Nous avons bien reçu votre demande pour une prestation **{{ $serviceName }}**. Nous vous rappelons sous 24 h pour confirmer les détails.

@component('mail::table')
| Détail | |
| --- | --- |
| Type de site | {{ $propertyName }} |
| Dates | {{ $booking->spansMultipleDays() ? $booking->preferred_date->translatedFormat('j F Y').' - '.$booking->endsOn()->translatedFormat('j F Y').' ('.$booking->dayCount().' j)' : ($booking->preferred_date?->translatedFormat('j F Y') ?? '-') }} |
| Estimation | {{ number_format($booking->estimated_price, 2, ',', ' ') }} € |
@endcomponent

Il s'agit d'une estimation indicative. Le tarif définitif est confirmé après étude de votre demande.

À très vite,<br>
{{ config('azurclean.trading_name') }}
@endcomponent
