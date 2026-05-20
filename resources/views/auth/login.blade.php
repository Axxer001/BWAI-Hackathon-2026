<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · Limpio Zambo</title>
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
            overflow: hidden;
            position: relative;
        }

        /* Background Blobs */
        .blobs { position: absolute; inset: 0; z-index: 0; pointer-events: none; }
        .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; animation: float 10s infinite alternate ease-in-out; }
        .blob-1 { top: -10%; left: -10%; width: 400px; height: 400px; background: #bfdbfe; }
        .blob-2 { bottom: -10%; right: -10%; width: 500px; height: 500px; background: #bbf7d0; animation-delay: -5s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(30px, 30px) scale(1.1); } }

        /* Auth Container */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 40px -15px rgba(30, 58, 138, 0.1);
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Header */
        .auth-header { text-align: center; margin-bottom: 2.5rem; }
        .logo { font-size: 1.75rem; font-weight: 800; color: #1e3a8a; text-decoration: none; display: inline-flex; margin-bottom: 1rem; }
        .logo span { color: #16a34a; }
        .auth-title { font-size: 1.5rem; color: #1e3a8a; font-weight: 700; margin-bottom: 0.5rem; }
        .auth-subtitle { color: #64748b; font-size: 0.95rem; }

        /* Forms */
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; font-size: 0.9rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem; }
        .form-control {
            width: 100%; padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid #cbd5e1;
            background: #ffffff; color: #334155; font-size: 1rem; transition: all 0.2s; outline: none;
        }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
        .form-control.is-invalid { border-color: #ef4444; }
        .error-msg { color: #ef4444; font-size: 0.85rem; margin-top: 0.4rem; display: block; }

        /* Utils */
        .flex-between { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .checkbox-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #475569; cursor: pointer; }
        .text-link { color: #1e3a8a; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: color 0.2s; }
        .text-link:hover { color: #16a34a; }

        /* Button */
        .btn-submit {
            width: 100%; padding: 0.9rem; border-radius: 8px; background-color: #16a34a; color: white;
            font-size: 1rem; font-weight: 600; border: none; cursor: pointer;
            transition: all 0.3s; box-shadow: 0 4px 14px 0 rgba(22, 163, 74, 0.3);
        }
        .btn-submit:hover { background-color: #15803d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4); }
        
        .auth-footer { text-align: center; margin-top: 2rem; font-size: 0.95rem; color: #64748b; }
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
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to your account to continue.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="juan@example.com">
                @error('email')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex-between">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-link">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}" class="text-link">Register here</a>
        </div>
    </div>

</body>
</html>