@component('mail::message')
# Merci, {{ $contactMessage->name }} !

Nous avons bien reçu votre message et vous répondons dans les meilleurs délais, généralement sous 24 h.

Pour une demande urgente, vous pouvez nous joindre directement au **{{ config('azurclean.manager.phone') }}**.

À très vite,<br>
{{ config('azurclean.trading_name') }}
@endcomponent
