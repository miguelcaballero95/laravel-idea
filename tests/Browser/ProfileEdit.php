<?php

use App\Models\User;
use App\Notifications\EmailChanged;

it('required authentication', function () {
    $this->get('/profile/edit')
        ->assertRedirect('/login');
});

it('edits profile', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/profile/edit')
        ->assertValue('name', $user->name)
        ->fill('name', 'New name')
        ->assertValue('email', $user->email)
        ->fill('email', 'new@example.com')
        ->click('Update Account')
        ->assertSee('Profile updated successfully!');

    expect($user->fresh())->toMatchArray([
        'name' => 'New name',
        'email' => 'new@example.com',
    ]);
});

it('notifies the original email if updated', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    Notification::fake();
    $originalEmail = $user->email;

    visit('/profile/edit')
        ->assertValue('name', $user->name)
        ->fill('email', 'new@example.com')
        ->click('Update Account')
        ->assertSee('Profile updated successfully!');

    Notification::assertSentOnDemand(EmailChanged::class, fn (EmailChanged $notification, $routes, $notifiable) => $notifiable->routes['mail'] === $originalEmail);

});
