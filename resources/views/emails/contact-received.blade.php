@component('mail::message')
# Nouveau message

**{{ $contactMessage->name }}** vous a écrit via le site.

@component('mail::table')
| Détail | |
| --- | --- |
| Email | {{ $contactMessage->email }} |
| Téléphone | {{ $contactMessage->phone ?: 'Non renseigné' }} |
| Sujet | {{ $contactMessage->subject ?: '-' }} |
@endcomponent

**Message :**

{{ $contactMessage->message }}

Répondez directement à cet email pour recontacter {{ $contactMessage->name }}.

{{ config('azurclean.trading_name') }}
@endcomponent
