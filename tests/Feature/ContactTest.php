<?php

use App\Mail\ContactAutoReply;
use App\Mail\ContactReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

it('stores a contact message and sends both emails', function () {
    Mail::fake();

    $response = $this->post(route('contact.send'), [
        'name' => 'Jean Dupont',
        'email' => 'jean@example.com',
        'phone' => '0612345678',
        'subject' => 'Devis bureaux',
        'message' => 'Bonjour, je souhaite un devis pour un bureau de 200m2.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('contact_status');

    expect(ContactMessage::count())->toBe(1);
    expect(ContactMessage::first()->name)->toBe('Jean Dupont');

    Mail::assertQueued(ContactReceived::class);
    Mail::assertQueued(ContactAutoReply::class);
});

it('requires name, email and message', function () {
    $this->post(route('contact.send'), [])
        ->assertSessionHasErrors(['name', 'email', 'message']);
});

it('rejects a message that is too short', function () {
    $this->post(route('contact.send'), [
        'name' => 'Jean',
        'email' => 'jean@example.com',
        'message' => 'Court',
    ])->assertSessionHasErrors('message');
});

it('silently drops spam that trips the honeypot', function () {
    Mail::fake();

    $response = $this->post(route('contact.send'), [
        'name' => 'Spam Bot',
        'email' => 'bot@example.com',
        'message' => 'This is a spammy message with links.',
        'website' => 'http://spam.example',
    ]);

    // Looks successful to the bot, but nothing is stored or emailed.
    $response->assertSessionHas('contact_status');
    expect(ContactMessage::count())->toBe(0);
    Mail::assertNothingQueued();
});
