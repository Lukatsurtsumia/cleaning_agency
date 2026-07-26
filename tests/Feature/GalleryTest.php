<?php

use App\Models\Gallery;
use App\Models\User;

it('lists galleries publicly', function () {
    Gallery::factory()->count(3)->create();

    $this->get(route('gallery.index'))->assertOk();
});

it('shows a single gallery', function () {
    $gallery = Gallery::factory()->create();

    $this->get(route('gallery.show', $gallery))->assertOk();
});

it('requires auth to create a gallery project', function () {
    $this->get(route('gallery.create'))->assertRedirect(route('login'));
});

it('lets an authenticated user create a gallery project', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('gallery.store'), [
        'title' => 'Test Project',
        'category' => 'immeubles',
        'description' => 'A test project.',
    ]);

    $response->assertRedirect();
    expect(Gallery::where('title', 'Test Project')->exists())->toBeTrue();
});
