<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limpio Zambo · Smart Waste Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Base Reset & Typography */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #ffffff; /* White */
            color: #334155;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Layout */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        section { padding: 5rem 0; }

        .section-header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .section-title {
            font-size: 2.25rem;
            color: #1e3a8a; /* Blue */
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .btn-primary {
            background-color: #16a34a; /* Green */
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #15803d;
            transform: translateY(-1px);
        }

        .btn-outline {
            border-color: #1e3a8a; /* Blue */
            color: #1e3a8a;
            background-color: transparent;
        }

        .btn-outline:hover {
            background-color: #f0f4f8;
        }

        /* Header Navigation */
        header {
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e3a8a;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo span { color: #16a34a; }

        .nav-links a {
            color: #475569;
            text-decoration: none;
            margin-left: 2rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: #16a34a; }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 6rem 0 7rem;
            background: linear-gradient(to bottom, #f8fafc, #ffffff);
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            color: #1e3a8a;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -0.02em;
        }

        .hero h1 span { color: #16a34a; }

        .hero p {
            font-size: 1.25rem;
            color: #475569;
            max-width: 650px;
            margin: 0 auto 2.5rem;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        /* Features Grid */
        .features-bg { background-color: #f8fafc; /* Very light blue/gray */ }

        .grid-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: box-shadow 0.2s;
        }

        .feature-card:hover {
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.05);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background-color: #f0fdf4; /* Light green */
            color: #16a34a;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .feature-icon svg {
            width: 28px;
            height: 28px;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .feature-card p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Roles/Audiences Section */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .role-card {
            padding: 2.5rem;
            border-radius: 12px;
            border-top: 4px solid #1e3a8a;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .role-card.green-top { border-top-color: #16a34a; }

        .role-card h3 {
            font-size: 1.5rem;
            color: #1e3a8a;
            margin-bottom: 1rem;
        }

        .role-list {
            list-style: none;
            margin-top: 1.5rem;
        }

        .role-list li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: #475569;
        }

        .role-list li::before {
            content: "✓";
            color: #16a34a;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background-color: #1e3a8a; /* Deep Blue */
            color: #f8fafc;
            padding: 4rem 0 2rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .footer-col h4 {
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
        }

        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 0.5rem; }
        .footer-col ul li a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-col ul li a:hover { color: #16a34a; }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero h1 { font-size: 2.5rem; }
            .hero-actions { flex-direction: column; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <a href="/" class="logo">
                    Limpio<span>Zambo</span>
                </a>
                <div class="nav-links">
                    <a href="#features">Platform Features</a>
                    <a href="#ecosystem">The Ecosystem</a>
                    <a href="/login" class="btn btn-outline" style="padding: 0.5rem 1.25rem; margin-left: 2rem;">Sign In</a>
                    <a href="/register" class="btn btn-primary" style="padding: 0.5rem 1.25rem; margin-left: 0.5rem;">Register</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <main>
        <section class="hero">
            <div class="container">
                <h1>Smarter Waste Collection for a <br><span>Cleaner Zamboanga.</span></h1>
                <p>An integrated platform connecting residents, local barangays, and garbage collectors. Track schedules, report issues, scan waste with AI, and earn rewards.</p>
                <div class="hero-actions">
                    <a href="/register" class="btn btn-primary">Join Your Barangay</a>
                    <a href="#features" class="btn btn-outline">Explore Features</a>
                </div>
            </div>
        </section>

        <!-- Features (Based on ERD) -->
        <section id="features" class="features-bg">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Powered by Data & Community</h2>
                    <p class="section-subtitle">A fully digital ecosystem designed to ensure accountability and efficiency in municipal solid waste management.</p>
                </div>

                <div class="grid-features">
                    <!-- AI Scan -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h3>AI Waste Classification</h3>
                        <p>Not sure where it goes? Upload an image of your trash. Our AI model provides instant classification and disposal advice.</p>
                    </div>

                    <!-- Alerts -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </div>
                        <h3>Live Notifications</h3>
                        <p>Receive immediate SMS and Email alerts when a collection truck logs an arrival at your assigned neighborhood collection point.</p>
                    </div>

                    <!-- Community Reporting -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <h3>Violation & Missed Reports</h3>
                        <p>Submit GPS-tagged photos to report missed pickups or illegal dumping. Track the resolution status directly with your barangay.</p>
                    </div>

                    <!-- Eco Points -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        </div>
                        <h3>Eco Rewards System</h3>
                        <p>Earn Eco-Points for performing waste scans, reporting violations, and maintaining a high compliance rate. Redeem points locally.</p>
                    </div>

                    <!-- Routing & Logistics -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                        </div>
                        <h3>Fleet Logistics & Delays</h3>
                        <p>Real-time transparency. If a truck reaches capacity, collectors log a "Truck Full" event, updating ETAs for remaining route points automatically.</p>
                    </div>

                    <!-- Barangay Management -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <h3>Barangay Oversight</h3>
                        <p>Local government panels to manage truck assignments, active collection schedules, and review community-submitted violation reports.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ecosystem Breakdown -->
        <section id="ecosystem">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">One Platform, Three Roles</h2>
                    <p class="section-subtitle">Limpio Zambo serves the entire community ecosystem.</p>
                </div>

                <div class="roles-grid">
                    <div class="role-card green-top">
                        <h3>For Residents</h3>
                        <p style="color: #64748b;">Participate easily and stay informed.</p>
                        <ul class="role-list">
                            <li>Track assigned collection points</li>
                            <li>Receive automated schedule alerts</li>
                            <li>Scan waste using AI for proper sorting</li>
                            <li>Earn Eco-Points for active participation</li>
                        </ul>
                    </div>

                    <div class="role-card">
                        <h3>For Dispatch/Collectors</h3>
                        <p style="color: #64748b;">Streamline daily collection routes.</p>
                        <ul class="role-list">
                            <li>Log route starts and ends (Session tracking)</li>
                            <li>Upload GPS/Photo proof of collection</li>
                            <li>Log "Truck Full" to trigger delay notices</li>
                        </ul>
                    </div>

                    <div class="role-card">
                        <h3>For Barangays</h3>
                        <p style="color: #64748b;">Manage logistics and accountability.</p>
                        <ul class="role-list">
                            <li>Define collection schedules and zones</li>
                            <li>Manage registered trucks and capacities</li>
                            <li>Review and act on submitted violation reports</li>
                            <li>Respond to missed collection tickets</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Limpio Zambo</h4>
                    <p style="color: #94a3b8; font-size: 0.95rem;">Empowering Zamboanga City with smart, data-driven waste management systems.</p>
                </div>
                <div class="footer-col">
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="#">AI Scanner</a></li>
                        <li><a href="#">Report an Issue</a></li>
                        <li><a href="#">Eco-Points Directory</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Barangay Portal</h4>
                    <ul>
                        <li><a href="#">Schedule Management</a></li>
                        <li><a href="#">Fleet Tracking</a></li>
                        <li><a href="#">Violation Desk</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Limpio Zambo · Designed for Zamboanga City
            </div>
        </div>
    </footer>

</body>
</html>