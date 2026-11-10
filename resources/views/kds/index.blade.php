<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biryani Kitchen – Kitchen Display Screen | Nagarjuna</title>

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-canvas: #fbfbee;
            --brand-green: #156935;
            --brand-green-dark: #0f4f26;
            --brand-green-light: #22c55e;
            --header-new-green: #15803d;
            --border-new-green: #16a34a;
            --header-prep-amber: #eab308;
            --border-prep-amber: #f59e0b;
            --card-bg: #fffde7;
            --card-border: #fef08a;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --timer-bg: #fde047;
            --timer-text: #713f12;
            --ready-bg: #16a34a;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-canvas);
            background-image: 
                radial-gradient(circle at 50% 10%, rgba(254, 252, 232, 0.8) 0%, transparent 60%),
                radial-gradient(circle at 50% 90%, rgba(240, 253, 244, 0.6) 0%, transparent 60%);
            color: var(--text-dark);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            min-height: 100vh;
            padding: 1.25rem 2rem;
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Botanical Leaf Clusters in 4 corners matching image */
        .corner-leaf {
            position: fixed;
            pointer-events: none;
            z-index: 1;
            opacity: 0.95;
            filter: drop-shadow(0 4px 10px rgba(22, 101, 52, 0.12));
        }
        .leaf-top-left {
            top: -15px;
            left: -20px;
            width: 170px;
        }
        .leaf-top-right {
            top: -15px;
            right: -20px;
            width: 170px;
            transform: scaleX(-1);
        }
        .leaf-bottom-left {
            bottom: -15px;
            left: -20px;
            width: 170px;
            transform: scaleY(-1);
        }
        .leaf-bottom-right {
            bottom: -15px;
            right: -20px;
            width: 170px;
            transform: scale(-1);
        }

        /* Top Header Grid Container */
        .kds-header-grid {
            display: grid;
            grid-template-columns: 240px 1.4fr 1.3fr 1.1fr;
            gap: 1.25rem;
            align-items: stretch;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 1200px) {
            .kds-header-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .kds-header-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Header Cards Common */
        .header-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1.5px solid #edf2e8;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
        }

        /* Card 1: Nagarjuna Logo */
        .card-brand {
            flex-direction: column;
            justify-content: center;
            text-align: center;
            padding: 0.75rem 1rem;
        }
        .brand-logo-svg {
            width: 52px;
            height: 32px;
            margin-bottom: 2px;
        }
        .brand-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            color: var(--brand-green);
            letter-spacing: 1.5px;
            line-height: 1.1;
        }
        .brand-subtitle {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--brand-green-dark);
            letter-spacing: 2px;
            margin-top: 2px;
        }

        /* Card 2: Order Summary */
        .card-summary {
            background: #fffef2;
            border: 1.5px solid #fef08a;
            flex-direction: column;
            justify-content: center;
            padding: 0.65rem 1.25rem;
        }
        .summary-title {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
            margin-bottom: 0.4rem;
        }
        .summary-stats-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
        }
        .summary-stat-col {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .stat-label-group {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 2px;
        }
        .stat-big-number {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            line-height: 1;
            color: #0f172a;
        }

        /* Card 3: Kitchen Display Screen Identity */
        .card-kitchen {
            gap: 1rem;
            position: relative;
        }
        .kitchen-hat-icon {
            width: 54px;
            height: 54px;
            flex-shrink: 0;
        }
        .kitchen-title-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--brand-green);
            line-height: 1.15;
        }
        .kitchen-sub-text {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        /* Card 4: Date & Live Time */
        .card-time {
            gap: 1rem;
        }
        .time-clock-icon {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
        }
        .live-time-val {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        .live-date-val {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-top: 3px;
        }

        /* Main 3-Column KDS Board */
        .kds-board {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 1024px) {
            .kds-board {
                grid-template-columns: 1fr;
            }
        }

        /* Column Wrapper */
        .kds-column-box {
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: #fbfdf9;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }

        /* Column 1: New Orders (Green Theme) */
        .col-box-new {
            border: 3px solid var(--border-new-green);
            background-color: #f3f9f3;
        }
        .col-header-new {
            background-color: var(--header-new-green);
            color: #ffffff;
        }

        /* Column 2: Preparing Orders (Amber/Orange Theme) */
        .col-box-preparing {
            border: 3px solid var(--border-prep-amber);
            background-color: #fffef6;
        }
        .col-header-preparing {
            background: linear-gradient(135deg, #eab308 0%, #f59e0b 100%);
            color: #ffffff;
        }

        /* Column 3: Ready Orders (Deep Emerald Theme) */
        .col-box-ready {
            border: 3px solid var(--border-new-green);
            background-color: #f3f9f3;
        }
        .col-header-ready {
            background-color: var(--header-new-green);
            color: #ffffff;
        }

        /* Column Header Pill Banner */
        .column-banner {
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            font-family: 'Outfit', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .column-banner svg {
            flex-shrink: 0;
        }

        /* Column Body (Cards Container) */
        .column-cards-container {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 1.15rem;
            overflow-y: auto;
            max-height: calc(100vh - 230px);
        }

        /* Order Card Component */
        .kds-order-card {
            background: var(--card-bg);
            border: 1.5px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(180, 83, 9, 0.05);
            padding: 1.1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .kds-order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        /* Card Left: KOT number & Table */
        .kds-card-left {
            text-align: center;
            min-width: 78px;
            border-right: 1.5px solid #e2e8f0;
            padding-right: 0.9rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .kot-label {
            font-weight: 800;
            font-size: 0.95rem;
            color: #1e293b;
            letter-spacing: 0.5px;
            line-height: 1;
        }
        .kot-number {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.1rem;
            color: var(--brand-green-dark);
            line-height: 1.15;
            margin: 2px 0;
        }
        .kot-table {
            font-weight: 700;
            font-size: 0.9rem;
            color: #334155;
            line-height: 1;
        }

        /* Card Middle: Dishes List */
        .kds-card-middle {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .dish-item-row {
            display: flex;
            align-items: center;
            font-size: 0.98rem;
            color: #0f172a;
            font-weight: 600;
            line-height: 1.25;
        }
        .dish-title {
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 0.5rem;
        }
        .dish-multiplier {
            color: #64748b;
            font-weight: 500;
            margin-right: 0.4rem;
        }
        .dish-quantity {
            font-weight: 800;
            color: #0f172a;
            min-width: 14px;
            text-align: right;
        }

        /* Card Right: Timer or Ready Badge */
        .kds-card-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-width: 110px;
        }

        .status-badge-btn {
            border: none;
            cursor: pointer;
            border-radius: 999px;
            padding: 0.55rem 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            transition: all 0.15s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }
        .status-badge-btn:hover {
            transform: scale(1.05);
            filter: brightness(0.96);
        }

        /* Yellow Timer Badge */
        .badge-timer {
            background-color: var(--timer-bg);
            color: var(--timer-text);
            border: 1px solid #facc15;
        }
        .badge-timer svg {
            fill: var(--timer-text);
        }

        /* Green Ready Badge */
        .badge-ready {
            background-color: var(--ready-bg);
            color: #ffffff;
            border: 1px solid #15803d;
        }
        .badge-ready svg {
            fill: #ffffff;
        }

        /* Floating Kitchen Display Controls Panel */
        .kds-controls-floating {
            position: fixed;
            bottom: 1.2rem;
            right: 1.5rem;
            z-index: 100;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            border: 1.5px solid #cbd5e1;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .kds-ctrl-btn {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #334155;
            border-radius: 999px;
            padding: 0.4rem 0.75rem;
            font-size: 0.82rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .kds-ctrl-btn:hover {
            background: #f1f5f9;
            color: var(--brand-green);
            border-color: #cbd5e1;
        }

        /* Kitchen Dropdown in Card 3 */
        .kitchen-picker-btn {
            background: transparent;
            border: none;
            padding: 0;
            cursor: pointer;
            text-align: left;
            display: block;
        }
        .kitchen-picker-btn:hover .kitchen-title-text {
            color: #16a34a;
        }

        /* Modal styling for API payload viewer */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-box {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 650px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }
        .modal-header-bar {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-body-content {
            padding: 1.25rem;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.85rem;
            background: #0f172a;
            color: #38bdf8;
        }
    </style>
</head>
<body>

    <!-- Botanical Leaf Clusters (Corner Decorations) -->
    <!-- Top-Left Leaves -->
    <svg class="corner-leaf leaf-top-left" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M-10 -10 C30 20, 80 40, 120 20 C100 60, 60 90, 10 95 C-15 95, -20 40, -10 -10 Z" fill="#84cc16" opacity="0.8"/>
        <path d="M-5 -15 C45 10, 110 25, 145 75 C105 85, 55 125, -5 110 C-15 70, -25 20, -5 -15 Z" fill="#4ade80" opacity="0.9"/>
        <path d="M-15 5 C25 25, 75 75, 85 130 C45 125, 5 110, -20 60 Z" fill="#22c55e"/>
        <path d="M-20 -20 C10 10, 40 40, 60 70 C40 60, 20 40, -20 -20 Z" stroke="#15803d" stroke-width="2" opacity="0.4"/>
    </svg>

    <!-- Top-Right Leaves -->
    <svg class="corner-leaf leaf-top-right" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M-10 -10 C30 20, 80 40, 120 20 C100 60, 60 90, 10 95 C-15 95, -20 40, -10 -10 Z" fill="#84cc16" opacity="0.8"/>
        <path d="M-5 -15 C45 10, 110 25, 145 75 C105 85, 55 125, -5 110 C-15 70, -25 20, -5 -15 Z" fill="#4ade80" opacity="0.9"/>
        <path d="M-15 5 C25 25, 75 75, 85 130 C45 125, 5 110, -20 60 Z" fill="#22c55e"/>
    </svg>

    <!-- Bottom-Left Leaves -->
    <svg class="corner-leaf leaf-bottom-left" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M-10 -10 C30 20, 80 40, 120 20 C100 60, 60 90, 10 95 C-15 95, -20 40, -10 -10 Z" fill="#84cc16" opacity="0.8"/>
        <path d="M-5 -15 C45 10, 110 25, 145 75 C105 85, 55 125, -5 110 C-15 70, -25 20, -5 -15 Z" fill="#4ade80" opacity="0.9"/>
        <path d="M-15 5 C25 25, 75 75, 85 130 C45 125, 5 110, -20 60 Z" fill="#22c55e"/>
    </svg>

    <!-- Bottom-Right Leaves -->
    <svg class="corner-leaf leaf-bottom-right" viewBox="0 0 160 140" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M-10 -10 C30 20, 80 40, 120 20 C100 60, 60 90, 10 95 C-15 95, -20 40, -10 -10 Z" fill="#84cc16" opacity="0.8"/>
        <path d="M-5 -15 C45 10, 110 25, 145 75 C105 85, 55 125, -5 110 C-15 70, -25 20, -5 -15 Z" fill="#4ade80" opacity="0.9"/>
        <path d="M-15 5 C25 25, 75 75, 85 130 C45 125, 5 110, -20 60 Z" fill="#22c55e"/>
    </svg>


    <!-- TOP HEADER BAR: 4 CARDS -->
    <header class="kds-header-grid">
        
        <!-- CARD 1: NAGARJUNA BRAND LOGO -->
        <div class="header-card card-brand">
            <!-- Green Double Leaf Emblem -->
            <svg class="brand-logo-svg" viewBox="0 0 54 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 28C8 18 12 6 27 4C28 16 22 28 12 28Z" fill="#15803d"/>
                <path d="M25 28C22 17 26 3 42 2C43 14 36 28 25 28Z" fill="#22c55e"/>
                <path d="M7 29C15 28 25 27 34 29" stroke="#15803d" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
            <div class="brand-name">NAGARJUNA</div>
            <div class="brand-subtitle">ANDHRA STYLE</div>
        </div>

        <!-- CARD 2: ORDER SUMMARY -->
        <div class="header-card card-summary">
            <div class="summary-title">
                <!-- Bar Chart Icon -->
                <svg viewBox="0 0 24 24" width="18" height="18" fill="#15803d">
                    <rect x="3" y="12" width="4" height="8" rx="1"/>
                    <rect x="10" y="7" width="4" height="13" rx="1"/>
                    <rect x="17" y="3" width="4" height="17" rx="1"/>
                </svg>
                <span>Order Summary</span>
            </div>
            
            <div class="summary-stats-row">
                <!-- New Stat -->
                <div class="summary-stat-col">
                    <div class="stat-label-group">
                        <span style="display:inline-block; width:9px; height:9px; border-radius:50%; background:#dc2626;"></span>
                        <span>New</span>
                    </div>
                    <div class="stat-big-number" id="header-stat-new">{{ $stats['new'] }}</div>
                </div>

                <!-- Preparing Stat -->
                <div class="summary-stat-col">
                    <div class="stat-label-group">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="#f59e0b">
                            <path d="M18 10a4 4 0 0 0-4-4 4 4 0 0 0-4 4H7a3 3 0 0 0-3 3c0 1.3.8 2.4 2 2.8V19a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1v-3.2c1.2-.4 2-1.5 2-2.8a3 3 0 0 0-3-3h-3zM7 18v-2h10v2H7z"/>
                        </svg>
                        <span>Preparing</span>
                    </div>
                    <div class="stat-big-number" id="header-stat-preparing">{{ $stats['preparing'] }}</div>
                </div>

                <!-- Ready Stat -->
                <div class="summary-stat-col">
                    <div class="stat-label-group">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="#16a34a">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 12l2.5 2.5L16 9" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        </svg>
                        <span>Ready</span>
                    </div>
                    <div class="stat-big-number" id="header-stat-ready">{{ $stats['ready'] }}</div>
                </div>
            </div>
        </div>

        <!-- CARD 3: BIRYANI KITCHEN IDENTITY -->
        <div class="header-card card-kitchen">
            <!-- Orange/Yellow Chef Hat Icon -->
            <svg class="kitchen-hat-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="30" fill="#fffbeb" stroke="#fef08a" stroke-width="1.5"/>
                <path d="M22 25C19 25 17 28 17 31C17 34.5 19.5 37 23 37.5V45H41V37.5C44.5 37 47 34.5 47 31C47 28 45 25 42 25C42 20 37 16 32 16C27 16 22 20 22 25Z" stroke="#f59e0b" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="23" y1="41" x2="41" y2="41" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="28" y1="28" x2="28" y2="35" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/>
                <line x1="32" y1="26" x2="32" y2="35" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/>
                <line x1="36" y1="28" x2="36" y2="35" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"/>
            </svg>

            <div>
                <button class="kitchen-picker-btn" onclick="toggleKitchenMenu()" title="Click to Switch Kitchen">
                    <div class="kitchen-title-text d-flex align-items-center gap-1">
                        <span id="currentKitchenName">{{ collect($availableKitchens)->firstWhere('code', $currentKitchen)['name'] ?? 'Biryani Kitchen' }}</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.85rem; color: #94a3b8;"></i>
                    </div>
                </button>
                <div class="kitchen-sub-text">Kitchen Display Screen</div>
            </div>
        </div>

        <!-- CARD 4: DATE & LIVE TIME -->
        <div class="header-card card-time">
            <!-- Orange Analog Clock Icon -->
            <svg class="time-clock-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="32" cy="32" r="28" stroke="#f59e0b" stroke-width="3.5" fill="#ffffff"/>
                <circle cx="32" cy="32" r="3" fill="#f59e0b"/>
                <line x1="32" y1="32" x2="32" y2="18" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                <line x1="32" y1="32" x2="43" y2="38" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                <line x1="32" y1="7" x2="32" y2="10" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="57" y1="32" x2="54" y2="32" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="32" y1="57" x2="32" y2="54" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
                <line x1="7" y1="32" x2="10" y2="32" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
            </svg>

            <div>
                <div class="live-time-val" id="kdsClock">12:45 PM</div>
                <div class="live-date-val" id="kdsDate">Mon, 03 Nov 2026</div>
            </div>
        </div>

    </header>


    <!-- MAIN 3-SECTION KDS BOARD (New, Preparing, Ready) -->
    <main class="kds-board">
        
        <!-- SECTION 1: NEW ORDERS (12) -->
        <section class="kds-column-box col-box-new" id="col-new">
            <div class="column-banner col-header-new">
                <!-- Bell Icon with Sparkles -->
                <svg viewBox="0 0 24 24" width="24" height="24" fill="#ffffff">
                    <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                </svg>
                <span>New Orders (<span id="count-new">{{ $stats['new'] }}</span>)</span>
                <!-- Sparkle rays -->
                <svg viewBox="0 0 24 24" width="18" height="18" fill="#fde047">
                    <path d="M12 2l1.8 4.6L18 8.4l-3.6 3.1 1.1 4.9-3.5-2.6L8.5 16.4l1.1-4.9L6 8.4l4.2-1.8L12 2z"/>
                </svg>
            </div>

            <div class="column-cards-container" id="container-new">
                @foreach($sections['new'] as $kot)
                    @include('kds.partials.card', ['kot' => $kot])
                @endforeach
            </div>
        </section>

        <!-- SECTION 2: PREPARING ORDERS (18) -->
        <section class="kds-column-box col-box-preparing" id="col-preparing">
            <div class="column-banner col-header-preparing">
                <!-- Chef Hat Icon -->
                <svg viewBox="0 0 24 24" width="26" height="26" fill="#ffffff">
                    <path d="M18.06 6.07A6 6 0 0 0 6.13 7.82 4 4 0 0 0 4 11.5a4 4 0 0 0 3 3.87V19a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-3.63A4 4 0 0 0 20 11.5a4 4 0 0 0-1.94-3.43zM9 18v-2h6v2H9z"/>
                </svg>
                <span>Preparing Orders (<span id="count-preparing">{{ $stats['preparing'] }}</span>)</span>
            </div>

            <div class="column-cards-container" id="container-preparing">
                @foreach($sections['preparing'] as $kot)
                    @include('kds.partials.card', ['kot' => $kot])
                @endforeach
            </div>
        </section>

        <!-- SECTION 3: READY ORDERS (10) -->
        <section class="kds-column-box col-box-ready" id="col-ready">
            <div class="column-banner col-header-ready">
                <!-- Circular Checkmark Icon -->
                <svg viewBox="0 0 24 24" width="26" height="26" fill="#ffffff">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span>Ready Orders (<span id="count-ready">{{ $stats['ready'] }}</span>)</span>
            </div>

            <div class="column-cards-container" id="container-ready">
                @foreach($sections['ready'] as $kot)
                    @include('kds.partials.card', ['kot' => $kot])
                @endforeach
            </div>
        </section>

    </main>


    <!-- FLOATING KITCHEN DISPLAY CONTROLS (Discreet, Functional) -->
    <div class="kds-controls-floating">
        <button class="kds-ctrl-btn" id="btnSoundToggle" title="Toggle Sound Alert">
            <i class="bi bi-volume-up-fill text-warning"></i> Sound On
        </button>

        <button class="kds-ctrl-btn" id="btnSimulate" title="Simulate incoming order">
            <i class="bi bi-plus-circle-fill text-success"></i> + Sim KOT
        </button>

        <button class="kds-ctrl-btn" onclick="openJsonModal()" title="Inspect API Data Structure">
            <i class="bi bi-code-slash text-primary"></i> API JSON
        </button>

        <button class="kds-ctrl-btn" id="btnFullscreen" title="Toggle Fullscreen for TV">
            <i class="bi bi-arrows-fullscreen"></i> TV Fullscreen
        </button>

        <a href="{{ route('dashboard') }}" class="kds-ctrl-btn" title="Exit to Admin Dashboard">
            <i class="bi bi-arrow-left"></i> Exit
        </a>
    </div>


    <!-- MODAL: API JSON DATA INSPECTOR (Phase 2 Requirement) -->
    <div class="modal-overlay" id="jsonModal">
        <div class="modal-box">
            <div class="modal-header-bar">
                <strong style="color: #0f172a; font-size: 1rem;">
                    <i class="bi bi-diagram-3 text-success me-1"></i> Kitchen KOT API Payload (Phase 2)
                </strong>
                <button type="button" class="btn-close" onclick="closeJsonModal()" style="border:none; background:none; font-size:1.4rem; cursor:pointer;">×</button>
            </div>
            <div style="padding: 0.75rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 0.82rem; color: #475569;">
                Endpoint: <code>GET /kds/api/tickets</code> — Standardized data schema for WebSockets & API consumers.
            </div>
            <pre class="modal-body-content">{{ json_encode($tickets, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            <div style="padding: 0.75rem 1.25rem; background: #f8fafc; text-align: right;">
                <button class="kds-ctrl-btn" onclick="closeJsonModal()">Close</button>
            </div>
        </div>
    </div>


    <!-- Audio chime on new incoming order -->
    <audio id="orderChime" preload="auto">
        <source src="https://cdn.freesound.org/previews/536/536108_11861866-lq.mp3" type="audio/mp3">
    </audio>

    <!-- KDS CLIENT JAVASCRIPT: LIVE TIMERS, STATUS ACTIONS, API SYNC -->
    <script>
        window.KDS_DATA = {
            apiUrl: "{{ route('kds.api.tickets') }}",
            tickets: @json($tickets),
            stats: @json($stats)
        };

        class KdsController {
            constructor() {
                this.soundEnabled = true;
                this.initClock();
                this.initTimers();
                this.bindControls();
            }

            // Real-time Clock (12:45 PM & Mon, 03 Nov 2026 format)
            initClock() {
                const timeEl = document.getElementById('kdsClock');
                const dateEl = document.getElementById('kdsDate');

                const update = () => {
                    const now = new Date();
                    
                    // Time format: 12:45 PM
                    let hours = now.getHours();
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    timeEl.textContent = `${hours}:${minutes} ${ampm}`;

                    // Date format: Mon, 03 Nov 2026
                    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const dayName = days[now.getDay()];
                    const dateNum = String(now.getDate()).padStart(2, '0');
                    const monthName = months[now.getMonth()];
                    const yearNum = now.getFullYear();
                    dateEl.textContent = `${dayName}, ${dateNum} ${monthName} ${yearNum}`;
                };

                update();
                setInterval(update, 1000);
            }

            // Real-time Live Stopwatch Incrementer for each Card
            initTimers() {
                setInterval(() => {
                    document.querySelectorAll('.kds-order-card').forEach(card => {
                        const status = card.dataset.status;
                        if (status === 'ready') return; // Ready orders display "Ready" badge

                        let elapsed = parseInt(card.dataset.elapsed || '0', 10);
                        elapsed += 1;
                        card.dataset.elapsed = elapsed;

                        const mins = Math.floor(elapsed / 60);
                        const secs = elapsed % 60;
                        const formatted = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

                        const displayEl = card.querySelector('.timer-display');
                        if (displayEl) {
                            displayEl.textContent = formatted;
                        }
                    });
                }, 1000);
            }

            // Interactive Order Status Progression
            progressOrder(kotId, targetStatus) {
                const card = document.getElementById(`card-${kotId}`);
                if (!card) return;

                card.dataset.status = targetStatus;

                if (targetStatus === 'preparing') {
                    // Move from New -> Preparing
                    const rightCol = card.querySelector('.kds-card-right');
                    rightCol.innerHTML = `
                        <button class="status-badge-btn badge-timer" onclick="kds.progressOrder(${kotId}, 'ready')" title="Click to Mark Ready">
                            <svg class="badge-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm1-11V7h-2v6h5v-2h-3z"/>
                            </svg>
                            <span class="timer-display">00:01</span>
                        </button>
                    `;
                    card.dataset.elapsed = '1';
                    document.getElementById('container-preparing').prepend(card);
                } else if (targetStatus === 'ready') {
                    // Move from Preparing -> Ready
                    const rightCol = card.querySelector('.kds-card-right');
                    rightCol.innerHTML = `
                        <button class="status-badge-btn badge-ready" onclick="kds.progressOrder(${kotId}, 'served')" title="Click to Dispatch / Clear">
                            <svg class="badge-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <span>Ready</span>
                        </button>
                    `;
                    document.getElementById('container-ready').prepend(card);
                } else if (targetStatus === 'served') {
                    // Animate out & remove from screen
                    card.style.transition = 'all 0.3s ease';
                    card.style.transform = 'scale(0.85)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        this.recalculateCounts();
                    }, 300);
                    return;
                }

                this.recalculateCounts();
            }

            recalculateCounts() {
                const newCards = document.querySelectorAll('#container-new .kds-order-card').length;
                const prepCards = document.querySelectorAll('#container-preparing .kds-order-card').length;
                const readyCards = document.querySelectorAll('#container-ready .kds-order-card').length;

                // Update column headers
                document.getElementById('count-new').textContent = newCards;
                document.getElementById('count-preparing').textContent = prepCards;
                document.getElementById('count-ready').textContent = readyCards;

                // Update summary banner
                document.getElementById('header-stat-new').textContent = newCards;
                document.getElementById('header-stat-preparing').textContent = prepCards;
                document.getElementById('header-stat-ready').textContent = readyCards;
            }

            // Simulate incoming order
            simulateIncomingOrder() {
                const randKOT = Math.floor(Math.random() * 80) + 221;
                const randTable = Math.floor(Math.random() * 15) + 1;

                const cardHtml = `
                    <div class="kds-order-card" id="card-${randKOT}" data-id="${randKOT}" data-status="new" data-elapsed="0">
                        <div class="kds-card-left">
                            <div class="kot-label">KOT</div>
                            <div class="kot-number">${randKOT}</div>
                            <div class="kot-table">Table ${randTable}</div>
                        </div>
                        <div class="kds-card-middle">
                            <div class="dish-item-row">
                                <span class="dish-title">Chicken Biryani</span>
                                <span class="dish-multiplier">×</span>
                                <span class="dish-quantity">2</span>
                            </div>
                            <div class="dish-item-row">
                                <span class="dish-title">Mutton Sukka</span>
                                <span class="dish-multiplier">×</span>
                                <span class="dish-quantity">1</span>
                            </div>
                        </div>
                        <div class="kds-card-right">
                            <button class="status-badge-btn badge-timer" onclick="kds.progressOrder(${randKOT}, 'preparing')" title="Click to Start Preparing">
                                <svg class="badge-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                    <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm0 2C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm1-11V7h-2v6h5v-2h-3z"/>
                                </svg>
                                <span class="timer-display">00:00</span>
                            </button>
                        </div>
                    </div>
                `;

                const container = document.getElementById('container-new');
                container.insertAdjacentHTML('afterbegin', cardHtml);
                this.recalculateCounts();

                if (this.soundEnabled) {
                    const audio = document.getElementById('orderChime');
                    if (audio) {
                        audio.play().catch(() => {});
                    }
                }
            }

            bindControls() {
                // Fullscreen
                const btnFull = document.getElementById('btnFullscreen');
                if (btnFull) {
                    btnFull.addEventListener('click', () => {
                        if (!document.fullscreenElement) {
                            document.documentElement.requestFullscreen().catch(() => {});
                        } else {
                            document.exitFullscreen().catch(() => {});
                        }
                    });
                }

                // Sound toggle
                const btnSound = document.getElementById('btnSoundToggle');
                if (btnSound) {
                    btnSound.addEventListener('click', () => {
                        this.soundEnabled = !this.soundEnabled;
                        btnSound.innerHTML = this.soundEnabled 
                            ? '<i class="bi bi-volume-up-fill text-warning"></i> Sound On' 
                            : '<i class="bi bi-volume-mute-fill text-secondary"></i> Sound Off';
                    });
                }

                // Simulate order
                const btnSim = document.getElementById('btnSimulate');
                if (btnSim) {
                    btnSim.addEventListener('click', () => this.simulateIncomingOrder());
                }
            }
        }

        // Modal Helpers
        function openJsonModal() {
            document.getElementById('jsonModal').classList.add('active');
        }
        function closeJsonModal() {
            document.getElementById('jsonModal').classList.remove('active');
        }

        function toggleKitchenMenu() {
            const current = prompt("Switch Kitchen View:\n1. biryani-kitchen (Biryani Kitchen)\n2. chinese-kitchen (Chinese Kitchen)\n3. starters-kitchen (Starters Kitchen)", "biryani-kitchen");
            if (current) {
                window.location.href = `?kitchen=${current}`;
            }
        }

        // Initialize KDS
        const kds = new KdsController();
    </script>
</body>
</html>
