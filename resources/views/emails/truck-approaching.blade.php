<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garbage Truck Approaching</title>
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
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
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
        /* ETA Banner */
        .eta-banner {
            background: #f0fdf4;
            border-top: 3px solid #16a34a;
            border-bottom: 3px solid #16a34a;
            padding: 28px 40px;
            text-align: center;
        }
        .eta-label {
            font-size: 11px;
            font-weight: 800;
            color: #16a34a;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .eta-value {
            font-size: 52px;
            font-weight: 900;
            color: #15803d;
            line-height: 1;
        }
        .eta-unit {
            font-size: 18px;
            font-weight: 700;
            color: #16a34a;
            margin-left: 4px;
        }
        .eta-sub {
            font-size: 13px;
            color: #4ade80;
            font-weight: 600;
            margin-top: 6px;
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
        /* CTA */
        .cta-box {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-top: 24px;
        }
        .cta-box p {
            font-size: 13px;
            color: #15803d;
            font-weight: 600;
            line-height: 1.6;
        }
        .cta-box strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            color: #14532d;
            margin-bottom: 6px;
        }
        /* Steps */
        .steps {
            margin: 24px 0;
        }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 14px;
        }
        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #16a34a;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .step-text {
            font-size: 13px;
            color: #475569;
            font-weight: 500;
            line-height: 1.5;
            margin-top: 4px;
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
            color: #16a34a;
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
            <span class="header-icon">🚛</span>
            <h1>Garbage Truck Alert</h1>
            <p>LimpioZambo · Waste Management System</p>
        </div>

        <!-- ETA Banner -->
        <div class="eta-banner" style="{{ $etaMinutes === 0 ? 'background: #f0fdf4; border-top: 3px solid #10b981; border-bottom: 3px solid #10b981;' : '' }}">
            @if($etaMinutes === 0)
                <div class="eta-label" style="color: #10b981;">Status</div>
                <div class="eta-value" style="color: #15803d; font-size: 40px; letter-spacing: -0.5px;">ARRIVED</div>
                <div class="eta-sub" style="color: #16a34a;">The truck is now at your collection point!</div>
            @else
                <div class="eta-label">Estimated Time of Arrival</div>
                <div>
                    <span class="eta-value">{{ $etaMinutes }}</span>
                    <span class="eta-unit">min{{ $etaMinutes !== 1 ? 's' : '' }}</span>
                </div>
                <div class="eta-sub">The truck is on its way — prepare now!</div>
            @endif
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Hi {{ $residentName }},</p>

            <p class="message">
                @if($etaMinutes === 0)
                    Great news! The garbage collection truck has arrived at your collection point (<strong>{{ $pointName }}</strong>). Please bring your segregated waste out immediately for collection.
                @else
                    Great news! A garbage collection truck is heading to your collection point
                    and will arrive in approximately <strong>{{ $etaMinutes }} minute{{ $etaMinutes !== 1 ? 's' : '' }}</strong>.
                    Please make sure your waste is ready for pickup.
                @endif
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
                    <span class="info-icon">⏱️</span>
                    <div>
                        <div class="info-label">ETA</div>
                        <div class="info-value">{{ $etaMinutes }} minute{{ $etaMinutes !== 1 ? 's' : '' }} from now</div>
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-icon">🕐</span>
                    <div>
                        <div class="info-label">Notified At</div>
                        <div class="info-value">{{ now()->setTimezone('Asia/Manila')->format('M d, Y — h:i A') }} (PHT)</div>
                    </div>
                </div>
            </div>

            <!-- Steps -->
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px;">Here's what to do:</p>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-text">Bring your segregated waste bags to <strong>{{ $pointName }}</strong>.</div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-text">Ensure waste is properly sorted — <strong>Biodegradable</strong> (green bag), <strong>Non-Biodegradable</strong> (blue bag), and <strong>Residual</strong> (black bag).</div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-text">Wait near the point for the truck to arrive within the estimated time.</div>
                </div>
            </div>

            <!-- CTA -->
            <div class="cta-box">
                <strong>♻️ Eco-Points Reminder</strong>
                <p>Proper waste segregation earns you Eco-Points in the LimpioZambo app. Keep it up and help keep Zamboanga City clean!</p>
            </div>

            <div class="divider"></div>

            <p class="message" style="font-size:13px;color:#94a3b8;">
                This is an automated notification from the LimpioZambo Waste Management System. If you believe you received this in error, please disregard.
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
