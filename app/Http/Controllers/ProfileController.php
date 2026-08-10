<?php

namespace App\Http\Controllers;

use App\Notifications\EmailChanged;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore(Auth::id())],
            'password' => ['nullable', Password::default()],
        ]);

        $originalEmail = $user->email;

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ?? $user->password,
        ]);

        if ($originalEmail !== $user->email) {
            Notification::route('mail', $originalEmail)
                ->notify(new EmailChanged($user, $originalEmail));
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
}
