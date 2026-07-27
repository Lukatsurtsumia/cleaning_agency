<?php

return [

    'brand' => [
        'tagline' => 'Professional cleaning',
        'tagline_full' => 'Professional, flexible cleaning services',
        'manager_role' => 'Founder',
    ],

    'nav' => [
        'home' => 'Home',
        'services' => 'Services',
        'pricing' => 'Pricing',
        'gallery' => 'Our work',
        'about' => 'About',
        'contact' => 'Contact',
        'book' => 'Book',
        'menu' => 'Menu',
        'close' => 'Close',
        'lang_label' => 'Language',
    ],

    'hero' => [
        'eyebrow' => 'Nice and the French Riviera',
        'title_line1' => 'Spotless spaces,',
        'title_line2' => 'a flawless impression.',
        'lead' => 'Azur Clean Tinati handles professional cleaning for hotels, homes and private villas across the French Riviera. Meticulous, flexible, eco-conscious.',
        'cta_book' => 'Book a cleaning',
        'cta_quote' => 'See pricing',
        'scroll' => 'Explore',
        'prev' => 'Previous slide',
        'next' => 'Next slide',
        'goto' => 'Go to slide :n',
    ],

    'services' => [
        'eyebrow' => 'What we do',
        'title' => 'Spaces we look after',
        'lead' => 'From hotels to private villas and one-off events - a regular contract or a single visit, your choice.',
        'cta' => 'Request a quote',
        'items' => [
            [
                'key' => 'hotelier',
                'name' => 'Hotels',
                'summary' => 'Guest rooms, corridors, lobbies and shared areas.',
                'points' => ['Guest rooms and bathrooms', 'Corridors and lobbies', 'Fresh linen and towels', 'Common areas'],
                'icon' => 'bed',
            ],
            [
                'key' => 'immeubles',
                'name' => 'Apartments',
                'summary' => 'Apartments and the shared areas of residential buildings.',
                'points' => ['Full apartment cleaning', 'Rooms and bathrooms', 'Entrance halls and stairs', 'Move-in and move-out'],
                'icon' => 'building',
            ],
            [
                'key' => 'bureaux',
                'name' => 'Houses & villas',
                'summary' => 'Private houses and villas, regular upkeep or a deep clean.',
                'points' => ['Whole-home cleaning', 'Kitchens and bathrooms', 'Floors, windows and surfaces', 'Terraces and outdoor areas'],
                'icon' => 'home',
            ],
            [
                'key' => 'specifiques',
                'name' => 'Special events',
                'summary' => 'Cleaning after parties and private events.',
                'points' => ['Cleaning after the event', 'Halls and event spaces', 'Floors and surfaces', 'Same-day service'],
                'icon' => 'sparkle',
            ],
        ],
    ],

    'pricing' => [
        'eyebrow' => 'Pricing',
        'title' => 'Simple and transparent',
        'lead' => 'A clear starting rate, then a free quote matched to your site within 24 hours.',
        'from' => 'From',
        'per_hour' => '/ hour',
        'on_quote' => 'On quote',
        'popular' => 'Most requested',
        'cta' => 'Request a quote',
        'note' => 'Indicative rates, excluding tax. The final quote depends on floor area, frequency and site constraints.',
        'tiers' => [
            'residences' => [
                'name' => 'Apartments & buildings',
                'for' => 'Landlords, tenants, property managers',
                'includes' => ['Apartments and common areas', 'Regular or one-off visits', 'Move-in / move-out cleans', 'Products supplied'],
            ],
            'bureaux' => [
                'name' => 'Houses & villas',
                'for' => 'Homeowners and villa rentals',
                'includes' => ['Whole-home cleaning', 'Deep cleans and upkeep', 'Windows and terraces', 'Products supplied'],
            ],
            'hotellerie' => [
                'name' => 'Hotels & events',
                'for' => 'Hotels, guesthouses and private events',
                'includes' => ['Guest-room cleaning', 'Cleaning after events', 'Dedicated team', 'Contract or one-off'],
            ],
        ],
    ],

    'gallery' => [
        'eyebrow' => 'Our work',
        'title' => 'Recent projects',
        'lead' => 'A few recent jobs, before and after.',
        'all' => 'View all',
        'view' => 'View this job',
        'empty' => 'The first projects are coming soon.',
        'back' => 'Back to home',
    ],

    'about' => [
        'eyebrow' => 'About',
        'title' => 'Tina Babayan, founder',
        'body' => 'Eight years as head housekeeper in hospitality, including Mama Shelter Nice. I founded Azur Clean Tinati to bring that same standard to the homes, villas and hotels of the French Riviera.',
        'quote' => 'Cleanliness never comes without safety, or without trust.',
        'stats' => [
            ['value' => '8 years', 'label' => 'in hotel housekeeping'],
            ['value' => '06', 'label' => 'Nice and the Riviera'],
            ['value' => '24 h', 'label' => 'response time'],
        ],
        'eco_title' => 'Environmental commitment',
        'eco_body' => 'Natural or ready-to-use products to avoid dosing errors, reusable packaging, waste sorting, and careful use of water and electricity.',
    ],

    // The public booking section (form, then a mailto: CTA) has since been
    // removed entirely — "Réserver"/"Book" now just scrolls to #contact.
    // This key survives only because App\Mail\BookingReceived/BookingConfirmation
    // still render existing Booking records (admin dashboard, quote PDF, .ics
    // feed) and read property_types.
    'booking' => [
        'property_types' => [
            'apartment' => 'Apartment / residence',
            'house' => 'Villa / house',
            'office' => 'Hotel / event venue',
        ],
    ],

    'contact' => [
        'eyebrow' => 'Contact',
        'title' => 'Tell us about your site',
        'lead' => 'A question, a quote, an urgent call-out? We reply within 24 hours.',
        'manager' => 'Management',
        'assistant' => 'Executive assistant',
        'address_label' => 'Address',
        'directions' => 'Directions',
        'area_body' => 'Nice and the whole French Riviera.',
        'hours_label' => 'Hours',
        'hours_value' => 'Mon - Fri, 8am - 6pm · Sat, 8am - 1pm',
        'direct_title' => 'Easiest of all: give us a call',
        'direct_body' => 'One call is enough to arrange a visit or get a quote. Prefer to pick your own date? Book online in under a minute.',
        'or' => 'or',
        'form_title' => 'Write to us',
        'form_lead' => 'Tell us what you need and we get back to you within 24 hours.',
        'reach_title' => 'Reach us',
        'call_cta' => 'Call now',
        'success' => 'Thank you, your message has been sent. We reply within 24 hours.',
        'fields' => [
            'name' => 'Full name',
            'email' => 'Email',
            'phone' => 'Phone',
            'subject' => 'Subject',
            'message' => 'Your message',
            'message_placeholder' => 'Site type, floor area, preferred frequency, your questions...',
        ],
        'submit' => 'Send message',
    ],

    'location' => [
        'eyebrow' => 'Location',
        'title' => 'Where to find us',
        'lead' => 'Based in Nice, we work across the whole French Riviera.',
    ],

    'footer' => [
        'rights' => 'All rights reserved.',
        'legal' => 'Legal notice',
        'nav_title' => 'Navigation',
        'contact_title' => 'Contact',
        'credit' => 'Designed by Luka Tsurtsumia',
    ],

    'common' => [
        'call' => 'Call',
        'email' => 'Send an email',
        'required' => 'required',
        'optional' => 'optional',
    ],

];
