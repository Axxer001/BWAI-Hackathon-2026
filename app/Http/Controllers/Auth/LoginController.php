<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the custom login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authenticating incoming request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Safely get the user's role in lowercase
            $role = strtolower(Auth::user()->role);

            // Route users to their specific first navigation item
            if ($role === 'barangay') {
                return redirect()->intended('/dashboard/schedules');
            } elseif ($role === 'collector') {
                return redirect()->intended('/dashboard/active-session');
            } elseif ($role === 'admin') {
                return redirect()->intended('/admin/users');
            }

            // Default fallback for 'user' or 'resident'
            return redirect()->intended('/dashboard/points');
        }

        // REPLACE THE THROW EXCEPTION WITH THIS:
        // This forces an HTTP redirect back to the form, carrying the errors and the old email input.
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * Handle logging out of the application session safely.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}