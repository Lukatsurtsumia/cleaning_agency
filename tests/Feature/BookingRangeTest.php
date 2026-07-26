<?php

use App\Models\Booking;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Mail;

/** Payload for a range booking, minus the dates. */
function rangePayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Hôtel Promenade',
        'email' => 'contact@example.com',
        'phone' => '0612345678',
        'property_type' => 'office',
        'service_type' => 'hotelier',
    ], $overrides);
}

it('books a run of consecutive days and stores both ends', function () {
    Mail::fake();

    $start = today()->addDay();
    $end = $start->copy()->addDays(6); // the 23rd-to-29th shape: seven days

    $response = $this->post(route('booking.store'), rangePayload([
        'preferred_date' => $start->toDateString(),
        'end_date' => $end->toDateString(),
    ]));

    $response->assertSessionHasNoErrors();

    $booking = Booking::first();

    expect($booking->preferred_date->toDateString())->toBe($start->toDateString());
    expect($booking->end_date->toDateString())->toBe($end->toDateString());
    expect($booking->spansMultipleDays())->toBeTrue();
    expect($booking->dayCount())->toBe(7);
});

it('stores a single-day booking with a null end date', function () {
    Mail::fake();

    $start = today()->addDay();

    $this->post(route('booking.store'), rangePayload([
        'preferred_date' => $start->toDateString(),
        'end_date' => $start->toDateString(),
    ]))->assertSessionHasNoErrors();

    $booking = Booking::first();

    expect($booking->end_date)->toBeNull();
    expect($booking->spansMultipleDays())->toBeFalse();
    expect($booking->dayCount())->toBe(1);
});

it('treats a booking with no end date as a single day', function () {
    Mail::fake();

    $start = today()->addDay();

    $this->post(route('booking.store'), rangePayload([
        'preferred_date' => $start->toDateString(),
    ]))->assertSessionHasNoErrors();

    expect(Booking::first()->end_date)->toBeNull();
});

it('refuses an end date that falls before the start', function () {
    Mail::fake();

    $start = today()->addDays(5);

    $response = $this->post(route('booking.store'), rangePayload([
        'preferred_date' => $start->toDateString(),
        'end_date' => $start->copy()->subDays(3)->toDateString(),
    ]));

    $response->assertSessionHasErrors('end_date');
    expect(Booking::count())->toBe(0);
});

it('spans the whole run in the ics feed', function () {
    config(['azurclean.booking.agenda_token' => 'secret-token']);

    $start = CarbonImmutable::today()->addDays(3);

    Booking::factory()->create([
        'preferred_date' => $start->toDateString(),
        'end_date' => $start->addDays(6)->toDateString(),
        'status' => 'scheduled',
    ]);

    $this->get(route('agenda.feed', ['token' => 'secret-token']))
        ->assertOk()
        // DTEND is exclusive, so it lands the day after the final working day.
        ->assertSee('DTSTART;VALUE=DATE:'.$start->format('Ymd'), false)
        ->assertSee('DTEND;VALUE=DATE:'.$start->addDays(7)->format('Ymd'), false);
});

it('still serves the ics feed only with the right token', function () {
    config(['azurclean.booking.agenda_token' => 'secret-token']);

    Booking::factory()->create([
        'preferred_date' => today()->addDays(3)->toDateString(),
        'status' => 'scheduled',
    ]);

    $this->get(route('agenda.feed', ['token' => 'wrong-token']))->assertNotFound();

    $this->get(route('agenda.feed', ['token' => 'secret-token']))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/calendar; charset=utf-8');
});
