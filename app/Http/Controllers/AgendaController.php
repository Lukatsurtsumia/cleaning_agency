<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class AgendaController extends Controller
{
    /**
     * A read-only .ics feed Tina can subscribe to from her phone calendar, so
     * confirmed jobs show up next to everything else in her week.
     *
     * Guarded by a secret token because calendar clients cannot log in.
     */
    public function feed(string $token): Response
    {
        $expected = config('azurclean.booking.agenda_token');

        abort_if(blank($expected), 404);
        abort_unless(hash_equals($expected, $token), 404);

        $bookings = Booking::whereNotNull('preferred_date')
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('preferred_date')
            ->get();

        return response($this->buildCalendar($bookings), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="azur-clean.ics"',
        ]);
    }

    /**
     * @param  Collection<int, Booking>  $bookings
     */
    private function buildCalendar($bookings): string
    {
        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Azur Clean Tinati//Booking feed//FR',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:Azur Clean Tinati',
            'X-WR-TIMEZONE:Europe/Paris',
        ];

        foreach ($bookings as $booking) {
            $start = $booking->preferred_date;

            $lines = array_merge($lines, [
                'BEGIN:VEVENT',
                'UID:booking-'.$booking->id.'@azurclean',
                'DTSTAMP:'.$booking->updated_at->utc()->format('Ymd\THis\Z'),
                // All-day event: DTEND is exclusive, hence the +1 day.
                'DTSTART;VALUE=DATE:'.$start->format('Ymd'),
                // DTEND is exclusive, so a multi-day job ends the day after its last.
                'DTEND;VALUE=DATE:'.$booking->endsOn()->addDay()->format('Ymd'),
                'SUMMARY:'.$this->escape($booking->name.' - '.$booking->service_type),
                'DESCRIPTION:'.$this->escape(implode("\n", array_filter([
                    $booking->phone,
                    $booking->email,
                    $booking->notes,
                ]))),
                'LOCATION:'.$this->escape((string) $booking->address),
                'STATUS:'.($booking->status === 'new' ? 'TENTATIVE' : 'CONFIRMED'),
                'END:VEVENT',
            ]);
        }

        $lines[] = 'END:VCALENDAR';

        // RFC 5545 wants CRLF line endings.
        return implode("\r\n", $lines)."\r\n";
    }

    private function escape(string $value): string
    {
        return str_replace(
            ['\\', ';', ',', "\r\n", "\n"],
            ['\\\\', '\;', '\,', '\n', '\n'],
            $value
        );
    }
}
