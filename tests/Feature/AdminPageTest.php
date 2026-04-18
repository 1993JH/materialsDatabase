<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeAdminUser(): User
{
    return User::factory()->admin()->create();
}

function makeRegularUser(): User
{
    return User::factory()->create();
}

test('guests are redirected to the login page from admin page', function () {
    $response = $this->get(route('admin'));

    $response->assertRedirect(route('login'));
});

test('admin users can visit the admin page', function () {
    $user = makeAdminUser();
    $this->actingAs($user);

    $response = $this->get(route('admin'));

    $response->assertOk();
    $response->assertSee('Admin dashboard');
});

test('non-admin users cannot visit the admin page', function () {
    $user = makeRegularUser();
    $this->actingAs($user);

    $response = $this->get(route('admin'));

    $response->assertForbidden();
});

test('admin users see admin link in navbar', function () {
    $user = makeAdminUser();
    $this->actingAs($user);

    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertSee(route('admin'));
});

test('non-admin users do not see admin link in navbar', function () {
    $user = makeRegularUser();
    $this->actingAs($user);

    $response = $this->get(route('about'));

    $response->assertOk();
    $response->assertDontSee(route('admin'));
});
