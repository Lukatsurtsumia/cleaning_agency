<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\BookingReceived;
use App\Models\Booking;
use App\Services\CleaningQuoteCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function store(Request $request, CleaningQuoteCalculator $calculator)
    {
        // A plain reservation request. The agency confirms the date by phone,
        // so there is no availability gating here — any future date is accepted.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'property_type' => ['required', Rule::in(array_keys(CleaningQuoteCalculator::PROPERTY_MULTIPLIERS))],
            'service_type' => ['required', Rule::in(array_keys(CleaningQuoteCalculator::SERVICE_BASE_PRICES))],
            // Room counts are no longer asked for on the public form; they stay
            // accepted so the admin and the quote PDF can still use them.
            'bedrooms' => ['nullable', 'integer', 'min:0', 'max:20'],
            'bathrooms' => ['nullable', 'integer', 'min:0', 'max:20'],
            'extras' => ['nullable', 'array'],
            'extras.*' => [Rule::in(array_keys(CleaningQuoteCalculator::EXTRA_PRICES))],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:preferred_date'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['bedrooms'] ??= 0;
        $data['bathrooms'] ??= 0;
        // A single-day job stores null rather than repeating the start date.
        if (($data['end_date'] ?? null) === $data['preferred_date']) {
            $data['end_date'] = null;
        }
        $data['estimated_price'] = $calculator->estimate($data);
        $data['status'] = 'new';

        $booking = Booking::create($data);

        Mail::to(config('mail.agency_address'))->send(new BookingReceived($booking));
        Mail::to($booking->email)->send(new BookingConfirmation($booking));

        $pdfUrl = URL::temporarySignedRoute('booking.pdf', now()->addDays(7), ['booking' => $booking->id]);

        return redirect()->route('welcome')
            ->with('status', __('site.booking.success'))
            ->with('booking_pdf_url', $pdfUrl);
    }

    public function index()
    {
        $bookings = Booking::latest()->paginate(20);

        // The .ics feed lets the agency subscribe to confirmed jobs from a phone
        // calendar. Only shown once AGENDA_FEED_TOKEN is set.
        $feedUrl = filled(config('azurclean.booking.agenda_token'))
            ? route('agenda.feed', ['token' => config('azurclean.booking.agenda_token')])
            : null;

        return view('admin.bookings.index', compact('bookings', 'feedUrl'));
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'contacted', 'scheduled', 'completed', 'cancelled'])],
        ]);

        $booking->update($data);

        return back()->with('status', 'Booking status updated.');
    }

    public function pdf(Request $request, Booking $booking)
    {
        abort_unless(auth()->check() || $request->hasValidSignature(), 403);

        $pdf = Pdf::loadView('pdf.quote', compact('booking'));

        return $pdf->download("devis-azur-clean-{$booking->id}.pdf");
    }
}
