<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class SessionsController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $validated = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::defaults()],
        ]);

        if (!Auth::attempt($validated)) {
            return back()
                ->withErrors(['password' => 'We were unable to sign you in.'])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'You are now logged in!');

    }

    public function destroy()
    {
        session()->regenerateToken();
        Auth::logout();
        return redirect('/');
    }
}
