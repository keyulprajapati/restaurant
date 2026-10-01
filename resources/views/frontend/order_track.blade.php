<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Track Order #{{ $order->order_number }} | {{ setting('site_name', 'Brahmani Lili Haldar') }}</title>

    @if(setting('favicon_icon'))
        <link rel="icon" href="{{ asset(setting('favicon_icon')) }}">
    @endif

    <style>
        :root {
            --primary: {{ setting('theme_primary_color', '#23422A') }};
            --primary-dark: #152518;
            --secondary: {{ setting('theme_secondary_color', '#C69A39') }};
            --bg-color: #f7f9f7;
            --card-bg: #ffffff;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --radius-lg: 22px;
            --radius-md: 14px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            line-height: 1.5;
            padding-bottom: 60px;
        }

        .page-container {
            max-width: 580px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Hero Header */
        .hero-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            padding: 26px 20px 32px 20px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            box-shadow: 0 8px 24px rgba(20, 36, 23, 0.15);
            text-align: center;
        }

        .brand-logo-wrapper {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid var(--secondary);
            box-shadow: 0 4px 12px rgba(198, 154, 57, 0.4);
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .brand-logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-title {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .order-title {
            font-size: 15px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 4px;
        }

        .order-meta-info {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.75);
            margin-top: 4px;
        }

        /* Status Badge */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin-top: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.35);
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-dot {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        /* Timeline Box */
        .timeline-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 22px 20px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border-color);
            margin-top: -16px;
            position: relative;
        }

        .timeline-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .timeline-header h2 {
            font-size: 15px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dark);
        }

        .auto-refresh-tag {
            font-size: 11px;
            font-weight: 600;
            color: #16a34a;
            background: #dcfce7;
            padding: 2px 8px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .timeline-steps {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 17px;
            top: 36px;
            bottom: -18px;
            width: 2px;
            background: #e5e7eb;
            z-index: 1;
        }

        .step-item.is-completed:not(:last-child)::after {
            background: #16a34a;
        }

        .step-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f3f4f6;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .step-icon svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }

        .step-item.is-active .step-icon {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(35, 66, 42, 0.2);
        }

        .step-item.is-completed .step-icon {
            background: #16a34a;
            color: #ffffff;
        }

        .step-content h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .step-content p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0;
        }

        /* Order Details Card */
        .details-card {
            background: #ffffff;
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border-color);
            margin-top: 16px;
        }

        .details-card h2 {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-line-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed var(--border-color);
            font-size: 14px;
        }

        .order-line-item:last-child {
            border-bottom: none;
        }

        .item-qty-badge {
            display: inline-block;
            background: #f3f4f6;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
            margin-right: 6px;
        }

        .bill-summary {
            background: #f9fafb;
            border-radius: var(--radius-md);
            padding: 14px;
            margin-top: 14px;
            border: 1px solid var(--border-color);
        }

        .bill-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .bill-row.total {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-dark);
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid var(--border-color);
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-action.primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(35, 66, 42, 0.25);
        }

        .btn-action.outline {
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            color: var(--text-dark);
        }

        .btn-action svg {
            width: 18px;
            height: 18px;
            fill: currentColor;
        }
    </style>
</head>
<body>

    <!-- Hero Header -->
    <header class="hero-header">
        <div class="page-container">
            <div class="brand-logo-wrapper">
                @if(setting('brand_logo'))
                    <img src="{{ asset(setting('brand_logo')) }}" alt="Logo">
                @elseif(setting('site_icon'))
                    <img src="{{ asset(setting('site_icon')) }}" alt="Logo">
                @else
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="#C69A39"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                @endif
            </div>
            <h1 class="brand-title">{{ setting('site_name', 'Brahmani Lili Haldar') }}</h1>
            <div class="order-title">Order #{{ $order->order_number }}</div>
            <div class="order-meta-info">
                Table #{{ $order->table?->table_number ?? 'Dine-In' }}
                @if($order->table?->area)
                    &bull; {{ $order->table->area }}
                @endif
                &bull; {{ $order->created_at->format('h:i A') }}
            </div>

            <div class="status-pill" id="liveStatusBadge">
                <span class="pulse-dot" id="pulseDot"></span>
                <span id="liveStatusText">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
    </header>

    <main class="page-container">
        <!-- Live Status Tracker Card -->
        <section class="timeline-card">
            <div class="timeline-header">
                <h2>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="var(--primary)"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg>
                    Live Kitchen Timeline
                </h2>
                <div class="auto-refresh-tag" id="refreshIndicator">
                    <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: #16a34a;"></span>
                    Live Updating
                </div>
            </div>

            <div class="timeline-steps">
                <!-- Step 1: Placed -->
                <div class="step-item {{ in_array($order->status, ['placed', 'preparing', 'ready', 'served', 'completed']) ? ($order->status === 'placed' ? 'is-active' : 'is-completed') : '' }}" id="step-placed">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
                    </div>
                    <div class="step-content">
                        <h3>Order Received</h3>
                        <p>Sent to kitchen. The chef will accept it shortly.</p>
                    </div>
                </div>

                <!-- Step 2: Preparing -->
                <div class="step-item {{ in_array($order->status, ['preparing', 'ready', 'served', 'completed']) ? ($order->status === 'preparing' ? 'is-active' : 'is-completed') : '' }}" id="step-preparing">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67zM11.71 19c-1.78 0-3.22-1.4-3.22-3.14 0-1.62 1.05-2.76 2.81-3.12 1.77-.36 3.6-1.21 4.62-2.58.39 1.29.59 2.65.59 4.04 0 2.65-2.15 4.8-4.8 4.8z"/></svg>
                    </div>
                    <div class="step-content">
                        <h3>Preparing in Kitchen</h3>
                        <p>Chef is actively cooking your fresh meal.</p>
                    </div>
                </div>

                <!-- Step 3: Ready -->
                <div class="step-item {{ in_array($order->status, ['ready', 'served', 'completed']) ? ($order->status === 'ready' ? 'is-active' : 'is-completed') : '' }}" id="step-ready">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/></svg>
                    </div>
                    <div class="step-content">
                        <h3>Ready to Serve</h3>
                        <p>Plated and heading to Table #{{ $order->table?->table_number }}.</p>
                    </div>
                </div>

                <!-- Step 4: Served / Completed -->
                <div class="step-item {{ in_array($order->status, ['served', 'completed']) ? ($order->status === 'served' ? 'is-active' : 'is-completed') : '' }}" id="step-served">
                    <div class="step-icon">
                        <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                    </div>
                    <div class="step-content">
                        <h3>Served on Table</h3>
                        <p>Delivered to your table. Enjoy your meal!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Order Items Summary Card -->
        <section class="details-card">
            <h2>
                <span>Dishes Ordered</span>
                <span style="font-size: 13px; font-weight: 600; color: var(--text-muted);">
                    {{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}
                </span>
            </h2>

            <div class="order-items-list">
                @foreach($order->items as $item)
                    <div class="order-line-item">
                        <div>
                            <span class="item-qty-badge">&times;{{ (int) $item->quantity }}</span>
                            <strong>{{ $item->item_name }}</strong>
                            @if($item->size)
                                <span style="font-size: 12px; color: var(--text-muted);">({{ $item->size }})</span>
                            @endif
                        </div>
                        <div style="font-weight: 700;">
                            &#8377;{{ number_format($item->total, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            @if($order->notes)
                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 10px 14px; margin-top: 14px; font-size: 13px; color: #92400e;">
                    <strong>Special Instructions:</strong> {{ $order->notes }}
                </div>
            @endif

            <!-- Bill Breakdown -->
            <div class="bill-summary">
                <div class="bill-row">
                    <span>Subtotal</span>
                    <span>&#8377;{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->tax > 0)
                    <div class="bill-row">
                        <span>Taxes &amp; Charges</span>
                        <span>&#8377;{{ number_format($order->tax, 2) }}</span>
                    </div>
                @endif
                <div class="bill-row total">
                    <span>Total Amount</span>
                    <span style="color: var(--primary);">&#8377;{{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </section>

        <!-- Action Navigation -->
        <div class="actions-group">
            <a href="{{ route('table.menu', $order->table?->table_number ?? 'T01') }}" class="btn-action primary">
                <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                Order More Items for Table #{{ $order->table?->table_number }}
            </a>
            <button type="button" class="btn-action outline" onclick="checkStatusNow()">
                <svg viewBox="0 0 24 24"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                Refresh Status
            </button>
        </div>
    </main>

    <!-- Pure Offline JavaScript Polling -->
    <script>
        // Use relative URL so it works seamlessly on any IP or domain
        const STATUS_URL = window.location.pathname.replace(/\/$/, '') + '/status';
        let pollTimer = null;

        function updateTimelineUI(status) {
            const badge = document.getElementById('liveStatusText');
            if (badge) badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            const steps = ['placed', 'preparing', 'ready', 'served', 'completed'];
            const currentIndex = steps.indexOf(status);

            const stepElements = {
                placed: document.getElementById('step-placed'),
                preparing: document.getElementById('step-preparing'),
                ready: document.getElementById('step-ready'),
                served: document.getElementById('step-served')
            };

            const orderSteps = ['placed', 'preparing', 'ready', 'served'];

            orderSteps.forEach((s, idx) => {
                const el = stepElements[s];
                if (!el) return;

                el.classList.remove('is-active', 'is-completed');

                if (idx < currentIndex) {
                    el.classList.add('is-completed');
                } else if (idx === currentIndex || (status === 'completed' && s === 'served')) {
                    el.classList.add('is-active');
                }
            });
        }

        function checkStatusNow() {
            fetch(STATUS_URL, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data && data.status) {
                    updateTimelineUI(data.status);
                    if (data.is_finished && pollTimer) {
                        clearInterval(pollTimer);
                    }
                }
            })
            .catch(err => {
                console.log('Status polling temporarily unavailable');
            });
        }

        // Auto-poll status every 5 seconds
        pollTimer = setInterval(checkStatusNow, 5000);
    </script>
</body>
</html>
