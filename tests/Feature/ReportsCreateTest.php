<?php

declare(strict_types=1);

use App\Models\User;

test('guests are redirected to the login page when visiting reports.create', function () {
    $response = $this->get(route('reports.create'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit reports.create', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('reports.create'));
    $response->assertStatus(200);
});
