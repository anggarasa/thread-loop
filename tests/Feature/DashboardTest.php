<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/home')->assertRedirect('/login');
});

test('authenticated users can visit the home page', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/home')->assertStatus(200);
});
