<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Collection Confirmed</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 32px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header-icon {
            font-size: 48px;
            display: block;
            margin-bottom: 16px;
            line-height: 1;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
        }
        .header p {
            font-size: 14px;
            color: rgba(255,255,255,0.8);
            margin-top: 6px;
            font-weight: 500;
        }
        /* Status Banner */
        .status-banner {
            background: #ecfdf5;
            border-top: 3px solid #10b981;
            border-bottom: 3px solid #10b981;
            padding: 24px 40px;
            text-align: center;
        }
        .status-label {
            font-size: 11px;
            font-weight: 800;
            color: #059669;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .status-value {
            font-size: 32px;
            font-weight: 900;
            color: #047857;
            line-height: 1.2;
        }
        /* Body */
        .body {
            padding: 36px 40px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            color: #475569;
            line-height: 1.75;
        }
        /* Info Card */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 24px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-row:first-child {
            padding-top: 0;
        }
        .info-icon {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }
        /* Eco Points Callout */
        .points-box {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-top: 24px;
        }
        .points-box p {
            font-size: 13px;
            color: #15803d;
            font-weight: 600;
            line-height: 1.6;
        }
        .points-box strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            color: #14532d;
            margin-bottom: 6px;
        }
        /* Footer */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }
        .footer-logo {
            font-size: 16px;
            font-weight: 900;
            color: #10b981;
            margin-bottom: 8px;
        }
        .footer p {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.6;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 24px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Header -->
        <div class="header">
            <span class="header-icon">✅</span>
            <h1>Collection Confirmed</h1>
            <p>LimpioZambo · Waste Management System</p>
        </div>

        <!-- Status Banner -->
        <div class="status-banner">
            <div class="status-label">Collection Status</div>
            <div class="status-value">SUCCESSFULLY COLLECTED</div>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Hi {{ $residentName }},</p>

            <p class="message">
                We're happy to inform you that the garbage collection truck has successfully completed its pickup at your assigned collection point (<strong>{{ $pointName }}</strong>).
            </p>

            <!-- Info Card -->
            <div class="info-card">
                <div class="info-row">
                    <span class="info-icon">📍</span>
                    <div>
                        <div class="info-label">Collection Point</div>
                        <div class="info-value">{{ $pointName }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-icon">🏘️</span>
                    <div>
                        <div class="info-label">Barangay</div>
                        <div class="info-value">{{ $barangayName }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-icon">🕐</span>
                    <div>
                        <div class="info-label">Collected At</div>
                        <div class="info-value">{{ now()->setTimezone('Asia/Manila')->format('M d, Y — h:i A') }} (PHT)</div>
                    </div>
                </div>
            </div>

            <!-- Eco Points Callout -->
            <div class="points-box">
                <strong>♻️ Eco-Points Updated</strong>
                <p>Thank you for participating in our waste management initiative and segregating your trash properly. Any Eco-Points gained from this collection session have been added to your profile.</p>
            </div>

            <div class="divider"></div>

            <p class="message" style="font-size:13px;color:#94a3b8;">
                This is an automated notification from the LimpioZambo Waste Management System.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">🌿 LimpioZambo</div>
            <p>Zamboanga City Waste Management System<br>
            Keeping our city clean, one pickup at a time.</p>
        </div>

    </div>
</body>
</html>
