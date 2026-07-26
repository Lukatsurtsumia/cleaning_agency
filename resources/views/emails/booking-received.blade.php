@php
    $serviceName = collect(__('site.services.items'))->firstWhere('key', $booking->service_type)['name'] ?? $booking->service_type;
    $propertyName = __('site.booking.property_types.'.$booking->property_type);
@endphp
@component('mail::message')
# Nouvelle demande de réservation

Une nouvelle demande vient d'arriver de la part de **{{ $booking->name }}**.

@component('mail::table')
| Détail | |
| --- | --- |
| Email | {{ $booking->email }} |
| Téléphone | {{ $booking->phone }} |
| Type de site | {{ $propertyName }} |
| Prestation | {{ $serviceName }} |
| Dates | {{ $booking->spansMultipleDays() ? $booking->preferred_date->translatedFormat('j F Y').' - '.$booking->endsOn()->translatedFormat('j F Y').' ('.$booking->dayCount().' j)' : ($booking->preferred_date?->translatedFormat('j F Y') ?? '-') }} |
| Adresse | {{ $booking->address ?: 'Non renseignée' }} |
| Estimation | {{ number_format($booking->estimated_price, 2, ',', ' ') }} € |
@endcomponent

@if ($booking->notes)
**Précisions du client :**
{{ $booking->notes }}
@endif

@component('mail::button', ['url' => route('booking.show', $booking)])
Voir la demande
@endcomponent

{{ config('azurclean.trading_name') }}
@endcomponent
