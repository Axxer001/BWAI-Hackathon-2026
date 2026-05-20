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
        // 1. Validate form fields
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 2. Attempt authentication match
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect back to landing or a protected home/dashboard view
            return redirect()->intended('/');
        }

        // 3. Throw authentication error if credentials fail match
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
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