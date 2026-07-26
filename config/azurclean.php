<?php

/*
|--------------------------------------------------------------------------
| Azur Clean Tinati — company details
|--------------------------------------------------------------------------
|
| Everything here comes from the visit card and the company presentation PDF.
| Views read these instead of hardcoding, so Tina only edits one file.
|
*/

return [
    'legal_name' => 'Société Azur Clean Tinati',
    'trading_name' => 'Azur Clean Tinati',
    'rcs' => '999 548 753 R.C.S. Nice',
    'legal_form' => 'SARL à associé unique',

    'manager' => [
        'name' => 'Tina Babayan',
        'email' => 'tinababayan99@gmail.com',
        'phone' => '+33 6 61 92 33 70',
        'phone_href' => '+33661923370',
    ],

    'assistant' => [
        'name' => 'Tereza Mouradyan',
        'email' => 'tereza.mouradyan@gmail.com',
        'phone' => '+33 6 62 46 05 71',
        'phone_href' => '+33662460571',
    ],

    'address' => [
        'street' => '12 Boulevard Comte de Falicon',
        'postcode' => '06100',
        'city' => 'Nice',
        'country' => 'France',
        /*
        | Geocoded from the street address (OpenStreetMap/Nominatim), quartier
        | Henri Sappia. The map pins these coordinates directly rather than
        | searching for the business name — searching returned a map full of
        | rival cleaning firms, because the company is not listed on Google yet.
        |
        | OSM has two nodes tagged "12" about 100 m apart on this boulevard, so
        | verify the pin sits on the right door and nudge if needed.
        */
        'lat' => 43.72847,
        'lng' => 7.25561,
    ],

    /*
    | Pricing shown on the site. These are PLACEHOLDERS — replace `from` with
    | Tina's real rates before launch. `null` renders as "sur devis".
    */
    'pricing' => [
        ['key' => 'residences', 'from' => 25, 'featured' => false],
        ['key' => 'bureaux', 'from' => 28, 'featured' => true],
        ['key' => 'hotellerie', 'from' => null, 'featured' => false],
    ],

    /*
    | Reservation requests are confirmed by phone, so there is no availability
    | gating. The only setting left is the secret segment for the .ics feed that
    | lets the agency subscribe to confirmed jobs. Set AGENDA_FEED_TOKEN in .env.
    */
    'booking' => [
        'agenda_token' => env('AGENDA_FEED_TOKEN'),
    ],

    'social' => [
        'instagram' => null,
        'facebook' => null,
        'linkedin' => null,
    ],
];
