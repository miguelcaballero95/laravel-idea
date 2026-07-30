<?php

use App\Models\User;

it('creates a new idea', function () {

    $user = User::factory()->create();
    $this->actingAs($user);
    $this->assertAuthenticated();

    visit('/ideas')
        ->click('@create-idea-button')
        ->fill('title', 'Some Idea Title')
        ->click('@button-status-completed')
        ->fill('description', 'Some Idea Description')
        ->fill('@new-link', 'https://example.com')
        ->click('@submit-new-link-button')
        ->fill('@new-link', 'https://laravel.com')
        ->click('@submit-new-link-button')
        ->fill('@new-step', 'Step 1')
        ->click('@submit-new-step-button')
        ->fill('@new-step', 'Step 2')
        ->click('@submit-new-step-button')
        ->click('Create')
        ->assertPathIs('/ideas');

    //    expect(Idea::count())->toBe(1);
    expect($idea = $user->ideas()->first())->toMatchArray([
        'title' => 'Some Idea Title',
        'status' => 'completed',
        'description' => 'Some Idea Description',
        'links' => ['https://example.com', 'https://laravel.com'],
    ])->and($idea->steps)->toHaveCount(2);

});
