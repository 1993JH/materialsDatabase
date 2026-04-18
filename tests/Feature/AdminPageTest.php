<?php

use App\Models\User;

function makeAuthenticatedUser(): User
{
    $user = new User([
        'name' => 'Admin User',
        'email' => 'admin@example.test',
        'password' => 'password',
    ]);

    $user->id = 1;

    return $user;
}

test('guests are redirected to the login page from admin page', function () {
    $response = $this->get(route('admin'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the admin page', function () {
    $user = makeAuthenticatedUser();
    $this->actingAs($user);

    $response = $this->get(route('admin'));

    $response->assertOk();
    $response->assertSee('Admin dashboard');
});

test('authenticated users see admin link in navbar', function () {
    $user = makeAuthenticatedUser();
    $this->actingAs($user);

    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee(route('admin'));
});
