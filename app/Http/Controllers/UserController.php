<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // Validate input fields
        $incomingFields = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required'
        ]);

        // Try to log in using the provided credentials
        if (Auth::attempt(['name' => $incomingFields['loginname'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate(); // Prevent session fixation attacks
            return redirect('/')->with('success', 'Login successful!');
        }

        // If login fails, redirect back with an error
        return back()->withErrors(['loginname' => 'Invalid username or password.'])->onlyInput('loginname');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:4', 'max:12', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'max:12']
        ]);

        // Hash password
        $incomingFields['password'] = bcrypt($incomingFields['password']);

        // Create user
        $user = User::create($incomingFields);

        // Auto-login after registration
        Auth::login($user);

        return redirect('/')->with('success', 'Account created and logged in!');
    }
}
