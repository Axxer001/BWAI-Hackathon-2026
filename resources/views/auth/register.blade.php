<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register · Limpio Zambo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 2rem 1rem; /* Padding for mobile scrolling */
        }

        /* Background Blobs */
        .blobs { position: absolute; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .blob { position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.4; animation: float 12s infinite alternate ease-in-out; }
        .blob-1 { top: 0; right: -10%; width: 500px; height: 500px; background: #bbf7d0; }
        .blob-2 { bottom: -10%; left: -10%; width: 600px; height: 600px; background: #bfdbfe; animation-delay: -3s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-40px, -40px) scale(1.05); } }

        /* Auth Container */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 40px -15px rgba(30, 58, 138, 0.1);
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Header */
        .auth-header { text-align: center; margin-bottom: 2.5rem; }
        .logo { font-size: 1.75rem; font-weight: 800; color: #1e3a8a; text-decoration: none; display: inline-flex; margin-bottom: 0.5rem; }
        .logo span { color: #16a34a; }
        .auth-title { font-size: 1.5rem; color: #1e3a8a; font-weight: 700; margin-bottom: 0.5rem; }
        .auth-subtitle { color: #64748b; font-size: 0.95rem; }

        /* Grid for Form Layout */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .full-width { grid-column: 1 / -1; }

        /* Forms */
        .form-group { margin-bottom: 0.5rem; }
        .form-label { display: block; font-size: 0.9rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem; }
        .form-control, .form-select {
            width: 100%; padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid #cbd5e1;
            background: #ffffff; color: #334155; font-size: 1rem; transition: all 0.2s; outline: none;
        }
        .form-control:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.85rem; margin-top: 0.4rem; display: block; }
        
        .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; }

        /* Button & Links */
        .btn-submit {
            width: 100%; padding: 0.9rem; border-radius: 8px; background-color: #16a34a; color: white; margin-top: 1.5rem;
            font-size: 1rem; font-weight: 600; border: none; cursor: pointer;
            transition: all 0.3s; box-shadow: 0 4px 14px 0 rgba(22, 163, 74, 0.3);
        }
        .btn-submit:hover { background-color: #15803d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4); }
        
        .auth-footer { text-align: center; margin-top: 2rem; font-size: 0.95rem; color: #64748b; }
        .text-link { color: #1e3a8a; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: color 0.2s; }
        .text-link:hover { color: #16a34a; }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .auth-card { padding: 2rem; }
        }
    </style>
</head>
<body>

    <div class="blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="auth-card">
        <div class="auth-header">
            <a href="/" class="logo">Limpio<span>Zambo</span></a>
            <h1 class="auth-title">Create an Account</h1>
            <p class="auth-subtitle">Join the community to track and report waste management.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-grid">
                <!-- Full Name -->
                <div class="form-group full-width">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required autofocus placeholder="Juan Dela Cruz">
                    @error('full_name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="juan@example.com">
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="0917 123 4567">
                    @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <!-- Barangay Assignment -->
                <div class="form-group full-width">
                    <label for="barangay_id" class="form-label">Select Your Barangay</label>
                    <select id="barangay_id" name="barangay_id" class="form-select @error('barangay_id') is-invalid @enderror" required>
                        <option value="" disabled {{ old('barangay_id') ? '' : 'selected' }}>Choose a barangay...</option>
                        <!-- Assuming a variable $barangays is passed from the controller -->
                        @foreach(App\Models\Barangay::all() ?? [] as $barangay)
                            <option value="{{ $barangay->id }}" {{ old('barangay_id') == $barangay->id ? 'selected' : '' }}>
                                {{ $barangay->name }} (District {{ $barangay->district }})
                            </option>
                        @endforeach
                    </select>
                    @error('barangay_id') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Create a password">
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Confirm password">
                </div>
            </div>

            <!-- Defaulting role registration to 'Resident'. Admins/Collectors should ideally be created in the backend. -->
            <input type="hidden" name="role" value="Resident">

            <button type="submit" class="btn-submit">Register Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}" class="text-link">Sign in here</a>
        </div>
    </div>

</body>
</html>