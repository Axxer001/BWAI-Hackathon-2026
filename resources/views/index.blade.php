<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Limpio Zambo · Smart Waste Management</title>
    <style>
        /* Base Reset & Typography */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Layout & Utilities */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
        section { padding: 6rem 0; position: relative; }
        .text-center { text-align: center; }
        .text-blue { color: #1e3a8a; }
        .text-green { color: #16a34a; }
        .bg-light { background-color: #f8fafc; }

        /* Typography */
        .section-title { font-size: 2.5rem; color: #1e3a8a; font-weight: 800; margin-bottom: 1rem; letter-spacing: -0.02em; }
        .section-subtitle { color: #64748b; font-size: 1.15rem; max-width: 650px; margin: 0 auto 3.5rem; }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.85rem 1.75rem; border-radius: 8px; font-weight: 600;
            text-decoration: none; font-size: 1rem; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: 2px solid transparent; outline: none; position: relative; overflow: hidden;
        }
        .btn-primary { background-color: #16a34a; color: #ffffff; box-shadow: 0 4px 14px 0 rgba(22, 163, 74, 0.39); }
        .btn-primary:hover { background-color: #15803d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4); }
        .btn-outline { border-color: #1e3a8a; color: #1e3a8a; background-color: transparent; }
        .btn-outline:hover { background-color: #1e3a8a; color: #ffffff; transform: translateY(-2px); }

        /* Header Navigation */
        header {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            border-bottom: 1px solid transparent;
        }
        header.scrolled { border-bottom: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        nav { display: flex; align-items: center; justify-content: space-between; height: 80px; transition: height 0.3s; }
        header.scrolled nav { height: 70px; }
        .logo { font-size: 1.5rem; font-weight: 800; color: #1e3a8a; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .logo span { color: #16a34a; }
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a.nav-item { color: #475569; text-decoration: none; font-weight: 500; transition: color 0.2s; position: relative; }
        .nav-links a.nav-item::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: -4px; left: 0;
            background-color: #16a34a; transition: width 0.3s;
        }
        .nav-links a.nav-item:hover::after { width: 100%; }
        .nav-links a.nav-item:hover { color: #1e3a8a; }

        /* Animations */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.5, 0, 0, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
        
        /* Hero Section */
        .hero {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            text-align: center; padding-top: 80px;
            background: radial-gradient(circle at top center, #f0fdf4 0%, #ffffff 100%);
            overflow: hidden;
        }
        .hero h1 { font-size: clamp(3rem, 6vw, 4.5rem); color: #1e3a8a; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.03em; }
        .hero-blobs { position: absolute; inset: 0; z-index: -1; overflow: hidden; pointer-events: none; }
        .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.4; animation: float 12s infinite alternate ease-in-out; }
        .blob-1 { top: 10%; left: 10%; width: 400px; height: 400px; background: #bfdbfe; }
        .blob-2 { bottom: 10%; right: 10%; width: 500px; height: 500px; background: #bbf7d0; animation-delay: -5s; }
        @keyframes float { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(50px, 50px) scale(1.1); } }

        /* Stats Strip */
        .stats-container { display: flex; flex-wrap: wrap; justify-content: center; gap: 4rem; padding: 3rem 0; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 2.5rem; font-weight: 800; color: #16a34a; line-height: 1; margin-bottom: 0.5rem; }
        .stat-label { font-size: 0.9rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }

        /* Interactive AI Demo Section */
        .ai-demo-section { background-color: #1e3a8a; color: white; border-radius: 24px; margin: 4rem auto; padding: 4rem 2rem; position: relative; overflow: hidden; display: flex; flex-wrap: wrap; align-items: center; gap: 3rem; box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.4); }
        .ai-demo-text { flex: 1; min-width: 300px; }
        .ai-demo-text h2 { font-size: 2.2rem; margin-bottom: 1rem; color: #ffffff; }
        .ai-demo-text p { color: #bfdbfe; font-size: 1.1rem; margin-bottom: 2rem; }
        
        .ai-scanner-box { flex: 1; min-width: 300px; background: rgba(255,255,255,0.1); border: 2px dashed #3b82f6; border-radius: 16px; padding: 2rem; text-align: center; position: relative; backdrop-filter: blur(10px); }
        .scan-target { width: 150px; height: 150px; background: #ffffff; border-radius: 12px; margin: 0 auto 1.5rem; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: 4rem; box-shadow: inset 0 0 20px rgba(0,0,0,0.1); transition: all 0.3s; }
        .scan-line { position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: #22c55e; box-shadow: 0 0 15px 5px rgba(34, 197, 94, 0.5); opacity: 0; z-index: 10; }
        .scanning .scan-line { animation: scan-anim 1.5s ease-in-out infinite; opacity: 1; }
        @keyframes scan-anim { 0% { top: 0; } 50% { top: 100%; } 100% { top: 0; } }
        .scan-result { margin-top: 1rem; opacity: 0; transform: translateY(10px); transition: all 0.4s; background: #22c55e; color: white; padding: 0.75rem; border-radius: 8px; font-weight: bold; }
        .scan-result.show { opacity: 1; transform: translateY(0); }

        /* Features Grid */
        .grid-features { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; perspective: 1000px; }
        .feature-card { background: #ffffff; padding: 2.5rem; border-radius: 16px; border: 1px solid #e2e8f0; transition: all 0.4s ease; transform-style: preserve-3d; }
        .feature-card:hover { transform: translateY(-10px) rotateX(2deg); box-shadow: 0 20px 40px -5px rgba(30, 58, 138, 0.1); border-color: #bfdbfe; }
        .feature-icon-wrapper { width: 60px; height: 60px; background: #f0fdf4; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; color: #16a34a; transition: all 0.3s; }
        .feature-card:hover .feature-icon-wrapper { background: #16a34a; color: #ffffff; transform: scale(1.1) rotate(-5deg); }
        .feature-icon-wrapper svg { width: 30px; height: 30px; }
        .feature-card h3 { font-size: 1.3rem; color: #1e3a8a; margin-bottom: 1rem; font-weight: 700; }

        /* Interactive Tabs (Ecosystem) */
        .tabs-container { max-width: 900px; margin: 0 auto; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; overflow: hidden; }
        .tab-headers { display: flex; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .tab-btn { flex: 1; padding: 1.5rem; font-size: 1.1rem; font-weight: 600; color: #64748b; background: transparent; border: none; border-bottom: 3px solid transparent; cursor: pointer; transition: all 0.3s; outline: none; }
        .tab-btn:hover { color: #1e3a8a; background: #f1f5f9; }
        .tab-btn.active { color: #1e3a8a; border-bottom-color: #16a34a; background: #ffffff; }
        .tab-content { padding: 3rem; display: none; animation: fadeIn 0.5s ease; }
        .tab-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .tab-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center; }
        .tab-list { list-style: none; }
        .tab-list li { margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; font-size: 1.05rem; }
        .tab-list li svg { width: 24px; height: 24px; color: #16a34a; flex-shrink: 0; }

        /* Footer */
        footer { background-color: #0f172a; color: #f8fafc; padding: 4rem 0 2rem; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 3rem; margin-bottom: 3rem; }
        .footer-col h4 { color: #ffffff; font-size: 1.2rem; margin-bottom: 1.5rem; font-weight: 700; }
        .footer-col ul { list-style: none; }
        .footer-col ul li { margin-bottom: 0.75rem; }
        .footer-col ul li a { color: #94a3b8; text-decoration: none; transition: color 0.2s; }
        .footer-col ul li a:hover { color: #16a34a; padding-left: 5px; }
        .footer-bottom { text-align: center; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); color: #64748b; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero h1 { font-size: 2.5rem; }
            .tab-grid { grid-template-columns: 1fr; }
            .tab-headers { flex-direction: column; }
            .tab-btn { border-bottom: none; border-left: 3px solid transparent; text-align: left; }
            .tab-btn.active { border-bottom-color: transparent; border-left-color: #16a34a; }
        }
    </style>
</head>
<body>

    <!-- Dynamic Header -->
    <header id="header">
        <div class="container">
            <nav>
                <a href="#" class="logo">Limpio<span>Zambo</span></a>
                <div class="nav-links">
                    <a href="#features" class="nav-item">Features</a>
                    <a href="#ai-scanner" class="nav-item">AI Scanner</a>
                    <a href="#ecosystem" class="nav-item">Ecosystem</a>
                    <div style="width: 1px; height: 24px; background: #e2e8f0; margin: 0 1rem;"></div>
                    <a href="{{ route('login') }}" class="btn btn-outline" style="padding: 0.5rem 1.25rem;">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">Register</a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="hero-blobs">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
        </div>
        <div class="container reveal">
            <h1>Smarter Waste Collection.<br>Cleaner <span>Zamboanga.</span></h1>
            <p style="font-size: 1.25rem; color: #475569; max-width: 650px; margin: 0 auto 2.5rem;">
                A synchronized platform connecting residents, local barangays, and garbage collectors. Track schedules, report issues, and earn rewards for a greener city.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="#" class="btn btn-primary">Join Your Barangay</a>
                <a href="#features" class="btn btn-outline">Explore the Platform</a>
            </div>
        </div>
    </section>

    <!-- Animated Stats -->
    <div class="container reveal">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-num" data-target="98">0</div>
                <div class="stat-label">Collection Rate %</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" data-target="42">0</div>
                <div class="stat-label">Active Barangays</div>
            </div>
            <div class="stat-item">
                <div class="stat-num" data-target="15000">0</div>
                <div class="stat-label">Eco-Points Earned</div>
            </div>
        </div>
    </div>

    <!-- Interactive AI Scanner Demo -->
    <section id="ai-scanner" class="container reveal">
        <div class="ai-demo-section">
            <div class="ai-demo-text">
                <h2>Try the AI Waste Scanner</h2>
                <p>Not sure if it goes in the green bin or the blue bin? Our integrated AI model instantly identifies waste and tells you exactly how to dispose of it, rewarding you with Eco-Points.</p>
                <ul class="tab-list" style="color: #e2e8f0; margin-bottom: 2rem;">
                    <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Instant Classification</li>
                    <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Earn Rewards per Scan</li>
                </ul>
            </div>
            <div class="ai-scanner-box">
                <div class="scan-target" id="scanTarget">
                    🥤
                    <div class="scan-line"></div>
                </div>
                <button class="btn btn-primary" id="scanBtn" style="width: 100%;">Scan Item</button>
                <div class="scan-result" id="scanResult">
                    ✓ Plastic Cup • Recyclable • +5 Points
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="bg-light">
        <div class="container reveal">
            <div class="text-center">
                <h2 class="section-title">Powered by Data</h2>
                <p class="section-subtitle">Everything you need to ensure accountability and efficiency in municipal solid waste management.</p>
            </div>
            <div class="grid-features">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <h3>Live Notifications</h3>
                    <p>Receive SMS and email alerts when a collection truck approaches your neighborhood or logs a delay.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3>Violation Reports</h3>
                    <p>Submit GPS-tagged photos of illegal dumping or missed pickups directly to your barangay's dashboard.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                    </div>
                    <h3>Fleet Tracking</h3>
                    <p>Truck drivers log route sessions and "Truck Full" events, automatically recalibrating neighborhood ETAs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Tabs Section -->
    <section id="ecosystem">
        <div class="container reveal">
            <div class="text-center">
                <h2 class="section-title">One Platform, Three Roles</h2>
                <p class="section-subtitle">Click below to see how Limpio Zambo empowers every level of the community.</p>
            </div>
            
            <div class="tabs-container">
                <div class="tab-headers">
                    <button class="tab-btn active" onclick="openTab('resident')">For Residents</button>
                    <button class="tab-btn" onclick="openTab('collector')">For Collectors</button>
                    <button class="tab-btn" onclick="openTab('barangay')">For Barangays</button>
                </div>
                
                <!-- Resident Tab -->
                <div id="resident" class="tab-content active">
                    <div class="tab-grid">
                        <div>
                            <h3 style="font-size: 1.8rem; color: #1e3a8a; margin-bottom: 1.5rem;">Participate & Earn</h3>
                            <ul class="tab-list">
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Track assigned collection points on a live map.</li>
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Receive automated SMS schedule alerts.</li>
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Earn Eco-Points for scanning waste and reporting issues.</li>
                            </ul>
                            <a href="#" class="btn btn-outline" style="margin-top: 1.5rem;">Create Resident Account</a>
                        </div>
                        <div style="background: #f0fdf4; border-radius: 12px; height: 250px; display: flex; align-items: center; justify-content: center; font-size: 5rem;">
                            📱
                        </div>
                    </div>
                </div>

                <!-- Collector Tab -->
                <div id="collector" class="tab-content">
                    <div class="tab-grid">
                        <div>
                            <h3 style="font-size: 1.8rem; color: #1e3a8a; margin-bottom: 1.5rem;">Streamline Routes</h3>
                            <ul class="tab-list">
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> One-tap Session Start for route tracking.</li>
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Upload GPS photo proof per collection point.</li>
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Instantly log "Truck Full" to notify dispatch.</li>
                            </ul>
                        </div>
                        <div style="background: #eff6ff; border-radius: 12px; height: 250px; display: flex; align-items: center; justify-content: center; font-size: 5rem;">
                            🚛
                        </div>
                    </div>
                </div>

                <!-- Barangay Tab -->
                <div id="barangay" class="tab-content">
                    <div class="tab-grid">
                        <div>
                            <h3 style="font-size: 1.8rem; color: #1e3a8a; margin-bottom: 1.5rem;">Oversight & Control</h3>
                            <ul class="tab-list">
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Manage schedules, trucks, and driver assignments.</li>
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> View analytical dashboards of collection success.</li>
                                <li><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> Review, approve, and act on user violation reports.</li>
                            </ul>
                        </div>
                        <div style="background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; height: 250px; display: flex; align-items: center; justify-content: center; font-size: 5rem;">
                            🏢
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4 style="font-size: 1.5rem; color: #16a34a;">Limpio<span style="color:white;">Zambo</span></h4>
                    <p style="color: #94a3b8; font-size: 0.95rem; line-height: 1.5;">Empowering Zamboanga City with smart, data-driven waste management systems.</p>
                </div>
                <div class="footer-col">
                    <h4>Resident Portal</h4>
                    <ul>
                        <li><a href="#">AI Scanner</a></li>
                        <li><a href="#">Report an Issue</a></li>
                        <li><a href="#">Eco-Points Rewards</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Admin & Logistics</h4>
                    <ul>
                        <li><a href="#">Barangay Dashboard</a></li>
                        <li><a href="#">Fleet Tracking</a></li>
                        <li><a href="#">Collector App</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; <script>document.write(new Date().getFullYear())</script> Limpio Zambo · Designed for Zamboanga City
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts -->
    <script>
        // 1. Sticky Header
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 50) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });

        // 2. Scroll Reveal Animations
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });
        revealElements.forEach(el => revealObserver.observe(el));

        // 3. Animated Number Counters
        const stats = document.querySelectorAll('.stat-num');
        let animated = false;
        const statsObserver = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting && !animated) {
                animated = true;
                stats.forEach(stat => {
                    const target = +stat.getAttribute('data-target');
                    const duration = 2000; 
                    const increment = target / (duration / 16); 
                    let current = 0;
                    
                    const updateCounter = () => {
                        current += increment;
                        if (current < target) {
                            stat.innerText = Math.ceil(current).toLocaleString();
                            requestAnimationFrame(updateCounter);
                        } else {
                            stat.innerText = target.toLocaleString() + (target === 98 ? '%' : (target > 1000 ? '+' : ''));
                        }
                    };
                    updateCounter();
                });
            }
        }, { threshold: 0.5 });
        if(stats.length > 0) statsObserver.observe(document.querySelector('.stats-container'));

        // 4. Tab Navigation Logic
        function openTab(tabName) {
            const contents = document.querySelectorAll('.tab-content');
            const btns = document.querySelectorAll('.tab-btn');
            
            contents.forEach(c => c.classList.remove('active'));
            btns.forEach(b => b.classList.remove('active'));
            
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // 5. AI Scanner Interactive Demo
        const scanBtn = document.getElementById('scanBtn');
        const scanTarget = document.getElementById('scanTarget');
        const scanResult = document.getElementById('scanResult');
        
        const items = ['🥤', '📦', '🍎', '🥫'];
        const results = [
            '✓ Plastic Cup • Recyclable • +5 Points',
            '✓ Cardboard Box • Recyclable • +10 Points',
            '✓ Apple Core • Compostable • +5 Points',
            '✓ Tin Can • Recyclable • +5 Points'
        ];
        let scanCount = 0;

        scanBtn.addEventListener('click', () => {
            // Reset state
            scanResult.classList.remove('show');
            scanBtn.disabled = true;
            scanBtn.innerText = "Scanning...";
            scanTarget.classList.add('scanning');
            
            // Cycle item
            scanCount = (scanCount + 1) % items.length;
            scanTarget.firstChild.nodeValue = items[scanCount] + "\n";
            scanResult.innerText = results[scanCount];

            // Simulate AI processing time
            setTimeout(() => {
                scanTarget.classList.remove('scanning');
                scanBtn.disabled = false;
                scanBtn.innerText = "Scan Another Item";
                scanResult.classList.add('show');
            }, 1500);
        });
    </script>
</body>
</html>