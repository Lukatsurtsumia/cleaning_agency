@props(['name'])

<svg {{ $attributes->merge(['class' => 'h-5 w-5']) }}
     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('clock')
            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
            @break

        @case('shield')
            <path d="M12 3l7 3v5c0 4.5-3 8.2-7 10-4-1.8-7-5.5-7-10V6l7-3z"/><path d="M9.5 12l1.8 1.8 3.4-3.6"/>
            @break

        @case('leaf')
            <path d="M4 20c0-8 6-13 16-13 0 9-5 14-12 14-2.2 0-4-1.3-4-1z"/><path d="M4 20c3-5 7-8 12-9.5"/>
            @break

        @case('sparkle')
            <path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9L12 3z"/><path d="M18.5 16.5l.7 1.8 1.8.7-1.8.7-.7 1.8-.7-1.8-1.8-.7 1.8-.7.7-1.8z"/>
            @break

        @case('bed')
            <path d="M3 18V8"/><path d="M3 12h18v6"/><path d="M21 18v-6a3 3 0 0 0-3-3h-6v3"/><circle cx="7.5" cy="10.5" r="1.8"/>
            @break

        @case('building')
            <path d="M4 21V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v16"/><path d="M14 10h4a2 2 0 0 1 2 2v9"/><path d="M2 21h20"/><path d="M7.5 7h3M7.5 11h3M7.5 15h3M17 14h1M17 17.5h1"/>
            @break

        @case('briefcase')
            <rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7"/><path d="M3 12h18"/>
            @break

        @case('spray')
            <path d="M9 8h5a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2V8z"/><path d="M10 8V5h3"/><path d="M18 5h.01M20.5 7.5h.01M18 10h.01M20.5 12h.01"/>
            @break

        @case('phone')
            <path d="M5 4h3l1.6 4-2 1.4a12 12 0 0 0 5.5 5.5l1.4-2 4 1.6v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 3 6.2 2 2 0 0 1 5 4z"/>
            @break

        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 7l8.5 6 8.5-6"/>
            @break

        @case('pin')
            <path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/>
            @break

        @case('calendar')
            <rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>
            @break

        @case('arrow-right')
            <path d="M5 12h13"/><path d="M13 6l6 6-6 6"/>
            @break

        @case('check')
            <path d="M5 12.5l4.5 4.5L19 7.5"/>
            @break

        @case('chevron-left')
            <path d="M14.5 5.5L8 12l6.5 6.5"/>
            @break

        @case('chevron-right')
            <path d="M9.5 5.5L16 12l-6.5 6.5"/>
            @break

        @case('chevron-down')
            <path d="M6 9.5l6 6 6-6"/>
            @break

        @case('menu')
            <path d="M4 7h16M4 12h16M4 17h16"/>
            @break

        @case('close')
            <path d="M6 6l12 12M18 6L6 18"/>
            @break

        @case('globe')
            <circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.5 2.6 3.8 5.7 3.8 9S14.5 18.4 12 21c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3z"/>
            @break

        @case('quote')
            <path d="M9 6.5C6.5 8 5 10.4 5 13.2V17h5v-5H7.6c.2-1.7 1-3.1 2.4-4.1L9 6.5z"/><path d="M18 6.5c-2.5 1.5-4 3.9-4 6.7V17h5v-5h-2.4c.2-1.7 1-3.1 2.4-4.1L18 6.5z"/>
            @break
    @endswitch
</svg>
