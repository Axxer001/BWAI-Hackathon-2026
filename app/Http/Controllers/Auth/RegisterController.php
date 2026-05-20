<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        // Fetches all barangays so your select element options fill dynamically
        $barangays = Barangay::all(); 
        return view('auth.register', compact('barangays'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        // 1. Validate structural incoming data parameters matching model schema constraints
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'barangay_id' => ['required', 'uuid', 'exists:barangays,id'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Instantiate and create record instance entries inside DB storage layers
        $user = User::create([
            'id' => (string) Str::uuid(), // Generates user UUID string as structured in ERD profile
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password_hash' => Hash::make($request->password), // Secure hashing step
            'role' => 'Resident', // Defaulting as assigned securely inline inside application logic
            'barangay_id' => $request->barangay_id,
        ]);

        // 3. Log the user into session state immediately upon profile execution
        Auth::login($user);

        // Redirect safely out to core landing home view context elements
        return redirect('/');
    }
}