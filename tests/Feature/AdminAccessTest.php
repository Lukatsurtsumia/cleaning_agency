<?php

use App\Models\User;

test('signed-in accounts off the admin list are refused and signed out', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertForbidden();

    $this->assertGuest();
})->with(['/admin/bookings', '/gallery-admin/create', '/profile', '/dashboard']);

test('accounts off the admin list can not log in', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('the admin account can open the admin pages', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/bookings')->assertOk();
    $this->actingAs($admin)->get('/gallery-admin/create')->assertOk();
});
