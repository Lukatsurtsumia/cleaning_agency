<?php

use App\Mail\BookingConfirmation;
use App\Mail\BookingReceived;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('creates a booking with a server-computed estimate and sends notifications', function () {
    Mail::fake();

    $response = $this->post(route('booking.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '0612345678',
        'property_type' => 'office',
        'service_type' => 'hotelier',
        'preferred_date' => today()->addDay()->toDateString(),
        'address' => '12 Boulevard Comte de Falicon, Nice',
    ]);

    $response->assertRedirect(route('welcome'));

    $booking = Booking::first();

    expect($booking)->not->toBeNull();
    // hotelier base 90, office multiplier 1.3, no rooms or extras.
    expect((float) $booking->estimated_price)->toBe(117.0);

    Mail::assertQueued(BookingReceived::class);
    Mail::assertQueued(BookingConfirmation::class);
});

it('rejects an invalid service type', function () {
    $response = $this->post(route('booking.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '0612345678',
        'property_type' => 'house',
        'service_type' => 'not-a-real-service',
        'preferred_date' => today()->addDay()->toDateString(),
    ]);

    $response->assertSessionHasErrors('service_type');
});

it('requires a preferred date', function () {
    $response = $this->post(route('booking.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '0612345678',
        'property_type' => 'house',
        'service_type' => 'bureaux',
    ]);

    $response->assertSessionHasErrors('preferred_date');
});

it('rejects a preferred date in the past', function () {
    $response = $this->post(route('booking.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '0612345678',
        'property_type' => 'house',
        'service_type' => 'bureaux',
        'preferred_date' => today()->subDay()->toDateString(),
    ]);

    $response->assertSessionHasErrors('preferred_date');
});

it('lets an authenticated user download a booking pdf', function () {
    $user = User::factory()->create();
    $booking = Booking::factory()->create();

    $this->actingAs($user)
        ->get(route('booking.pdf', $booking))
        ->assertOk();
});

it('blocks pdf downloads without auth or a valid signature', function () {
    $booking = Booking::factory()->create();

    $this->get(route('booking.pdf', $booking))->assertForbidden();
});
