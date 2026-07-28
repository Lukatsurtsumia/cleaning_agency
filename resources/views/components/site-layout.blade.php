@props(['title' => null, 'description' => null])

@php
    $company = config('azurclean');
    $pageTitle = $title ? $title.' | '.$company['trading_name'] : $company['trading_name'].' | '.__('site.brand.tagline_full');
    $pageDescription = $description ?? __('site.hero.lead');

    // Helps Google show the business with the right address and phone.
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'CleaningService',
        'name' => $company['trading_name'],
        'description' => $pageDescription,
        'telephone' => $company['manager']['phone'],
        'email' => $company['manager']['email'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $company['address']['street'],
            'postalCode' => $company['address']['postcode'],
            'addressLocality' => $company['address']['city'],
            'addressCountry' => 'FR',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => $company['address']['lat'],
            'longitude' => $company['address']['lng'],
        ],
        'areaServed' => "Nice, Côte d'Azur",
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">

    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ app()->getLocale() === 'fr' ? 'fr_FR' : 'en_GB' }}">
    <meta property="og:image" content="{{ asset('images/logo-full.png') }}">
    <meta name="theme-color" content="#173c56">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}?v=2">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|cormorant-garamond:500,600,600i&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Reveal-on-scroll starts elements hidden and JS fades them in. With JS
         off (or before it runs), show everything so content is never stuck
         invisible for no-script visitors or crawlers. --}}
    <noscript>
        <style>.reveal { opacity: 1 !important; transform: none !important; }</style>
    </noscript>

    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
</head>
<body class="antialiased">

<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-azur-800 focus:px-5 focus:py-3 focus:text-sm focus:font-semibold focus:text-white">
    {{ __('site.hero.scroll') }}
</a>

<x-site-nav/>

<main id="main">
    {{ $slot }}
</main>

<x-site-footer/>

</body>
</html>
