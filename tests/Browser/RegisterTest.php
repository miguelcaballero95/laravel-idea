<?php

it('registers a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password')
        ->press('Create Account')
        ->assertPathIs('/');

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['name' => 'John Doe', 'email' => 'john@example.com']);
});

it('requires a valid email', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john123')
        ->fill('password', 'password')
        ->press('Create Account')
        ->assertPathIs('/register');
});
