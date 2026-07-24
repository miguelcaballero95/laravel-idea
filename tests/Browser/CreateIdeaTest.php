<?php

use App\Models\Idea;
use App\Models\User;

it('creates a new idea', function () {

    $this->actingAs(User::factory()->create());
    $this->assertAuthenticated();

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Some Idea Title')
        ->click('@button-status-completed')
        ->fill('description', 'Some Idea Description')
        ->click('Create')
        ->assertPathIs('/ideas');

    expect(Idea::count())->toBe(1);
});
