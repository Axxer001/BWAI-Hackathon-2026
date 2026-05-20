<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') · Limpio Zambo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Container */
        .sidebar {
            width: 260px;
            background-color: #1e3a8a; /* Blue Brand Accent */
            color: #ffffff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 800;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-brand span { color: #16a34a; } /* Green Brand Accent */

        .sidebar-user {
            padding: 1rem 1.5rem;
            background: rgba(0, 0, 0, 0.15);
            font-size: 0.9rem;
        }
        .user-role-badge {
            display: inline-block;
            background: #16a34a;
            font-size: 0.75rem;
            padding: 0.1rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 0.25rem;
        }

        /* Navigation Links Styling */
        .sidebar-menu {
            list-style: none;
            padding: 1.5rem 0;
            flex: 1;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-menu li a:hover, .sidebar-menu li.active a {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
            border-left: 4px solid #16a34a;
        }

        /* Main Content Panel */
        .main-wrapper {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .top-nav {
            height: 70px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
        }

        .logout-btn {
            background: transparent;
            border: none;
            color: #64748b;
            font-weight: 600;
            cursor: pointer;
        }
        .logout-btn:hover { color: #ef4444; }

        .content-body { padding: 2.5rem; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-brand">
            Limpio<span>Zambo</span>
        </div>
        
        <div class="sidebar-user">
            <div><strong>{{ Auth::user()->full_name ?? 'Guest User' }}</strong></div>
            <span class="user-role-badge">{{ Auth::user()->role ?? 'Resident' }}</span>
        </div>

        <ul class="sidebar-menu">
            <li><a href="/dashboard">Dashboard Home</a></li>

            @if(Auth::check())
                @if(Auth::user()->role === 'Resident')
                    @include('dashboard.partials.nav-resident')
                @elseif(Auth::user()->role === 'Collector')
                    @include('dashboard.partials.nav-collector')
                @elseif(Auth::user()->role === 'Barangay')
                    @include('dashboard.partials.nav-barangay')
                @elseif(Auth::user()->role === 'Admin')
                    @include('dashboard.partials.nav-admin')
                @endif
            @else
                @include('dashboard.partials.nav-resident')
            @endif
        </ul>
    </aside>

    <div class="main-wrapper">
        <header class="top-nav">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Sign Out</button>
            </form>
        </header>

        <main class="content-body">
            @yield('content')
        </main>
    </div>

</body>
</html>