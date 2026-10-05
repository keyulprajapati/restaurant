<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu - Table {{ $table->table_number }} | {{ setting('site_name', 'Brahmani Lili Haldar') }}</title>

    @if(setting('favicon_icon'))
        <link rel="icon" href="{{ asset(setting('favicon_icon')) }}">
    @endif

    <style>
        :root {
            --primary: {{ setting('theme_primary_color', '#23422A') }};
            --primary-dark: #142417;
            --secondary: {{ setting('theme_secondary_color', '#C69A39') }};
            --bg-color: #f7f9f7;
            --card-bg: #ffffff;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --radius-lg: 20px;
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
            padding-bottom: 110px;
        }

        /* Container */
        .page-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Header */
        .hero-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            padding: 24px 20px 28px 20px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
            box-shadow: 0 8px 24px rgba(20, 36, 23, 0.15);
            position: relative;
        }

        .brand-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .brand-identity {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid var(--secondary);
            box-shadow: 0 4px 12px rgba(198, 154, 57, 0.4);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-text h1 {
            font-size: 19px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.75);
            margin: 0;
        }

        /* Table Indicator */
        .table-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            margin-top: 14px;
        }

        .pulse-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35);
        }

        /* Sticky Search & Chips */
        .sticky-toolbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(247, 249, 247, 0.96);
            backdrop-filter: blur(12px);
            padding: 12px 0 8px 0;
            margin-top: -12px;
        }

        .search-bar {
            background: #ffffff;
            border-radius: 50px;
            padding: 8px 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .search-bar input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            width: 100%;
            color: var(--text-dark);
        }

        .search-bar svg {
            fill: var(--text-muted);
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 4px;
        }

        .filter-tabs::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            background: #ffffff;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            box-shadow: 0 4px 10px rgba(35, 66, 42, 0.25);
        }

        /* Food Type Badges */
        .type-dot {
            width: 14px;
            height: 14px;
            border-radius: 3px;
            border: 2px solid;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .type-dot.general {
            border-color: #16a34a;
        }
        .type-dot.general::after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #16a34a;
        }

        .type-dot.jain {
            border-color: #d97706;
        }
        .type-dot.jain::after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #d97706;
        }

        /* Dish Cards */
        .dish-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px solid #edf0ed;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .dish-info {
            flex-grow: 1;
        }

        .dish-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .dish-meta {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .dish-meta span {
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-right: 6px;
        }

        .dish-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
        }

        /* Add & Quantity Controls */
        .btn-add {
            background: #ffffff;
            color: var(--primary);
            border: 1.5px solid var(--primary);
            border-radius: 50px;
            padding: 6px 18px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(35, 66, 42, 0.1);
            transition: all 0.2s ease;
        }

        .btn-add:active {
            background: var(--primary);
            color: #ffffff;
        }

        .qty-box {
            display: inline-flex;
            align-items: center;
            background: var(--primary);
            color: #ffffff;
            border-radius: 50px;
            padding: 3px 6px;
            box-shadow: 0 4px 10px rgba(35, 66, 42, 0.25);
        }

        .qty-action {
            background: transparent;
            border: none;
            color: #ffffff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .qty-action:active {
            background: rgba(255, 255, 255, 0.25);
        }

        .qty-val {
            min-width: 24px;
            text-align: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* Bottom Floating Cart Bar */
        .cart-bar-fixed {
            position: fixed;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 32px);
            max-width: 540px;
            background: linear-gradient(135deg, var(--primary) 0%, #152719 100%);
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 20px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.28);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .cart-bar-fixed:active {
            transform: translateX(-50%) scale(0.98);
        }

        .cart-pill {
            background: #ffffff;
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 50px;
            margin-right: 8px;
        }

        /* Custom Slide-Up Drawer (Pure CSS/JS) */
        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .drawer-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .cart-drawer {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) translateY(100%);
            width: 100%;
            max-width: 600px;
            background: #ffffff;
            border-top-left-radius: 28px;
            border-top-right-radius: 28px;
            z-index: 1200;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.2);
            transition: transform 0.35s cubic-bezier(0.32, 0.72, 0, 1);
        }

        .cart-drawer.open {
            transform: translateX(-50%) translateY(0);
        }

        .drawer-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .drawer-body {
            padding: 20px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .cart-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border-color);
        }

        .cart-row:last-child {
            border-bottom: none;
        }

        .bill-summary {
            background: #f9fafb;
            border-radius: var(--radius-md);
            padding: 16px;
            margin-top: 16px;
            border: 1px solid var(--border-color);
        }

        .bill-line {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 6px;
            color: var(--text-muted);
        }

        .bill-line.total {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-dark);
            border-top: 1.5px solid var(--border-color);
            padding-top: 10px;
            margin-top: 10px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            background: #f9fafb;
            font-size: 14px;
            outline: none;
            margin-bottom: 10px;
        }

        .form-input:focus {
            background: #ffffff;
            border-color: var(--primary);
        }

        .btn-order {
            background: var(--primary);
            color: #ffffff;
            font-weight: 800;
            font-size: 16px;
            padding: 15px;
            border-radius: 16px;
            border: none;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(35, 66, 42, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-order:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Hero Header -->
    <header class="hero-header">
        <div class="page-container">
            <div class="brand-header-flex">
                <div class="brand-identity">
                    <div class="brand-logo-wrapper">
                        @if(setting('brand_logo'))
                            <img src="{{ asset(setting('brand_logo')) }}" alt="Logo">
                        @elseif(setting('site_icon'))
                            <img src="{{ asset(setting('site_icon')) }}" alt="Logo">
                        @else
                            <svg viewBox="0 0 24 24" width="28" height="28" fill="#C69A39"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/></svg>
                        @endif
                    </div>
                    <div class="brand-text">
                        <h1>{{ setting('site_name', 'Brahmani Lili Haldar') }}</h1>
                        <p>Authentic Dining &bull; Contactless Table Ordering</p>
                    </div>
                </div>

                @if($activeOrder)
                    <a href="{{ route('table.order.track', $activeOrder->order_number) }}" style="background: #ffffff; color: var(--primary); text-decoration: none; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 700;">
                        Track Order
                    </a>
                @endif
            </div>

            <!-- Table Pill -->
            <div class="table-pill">
                <span class="pulse-indicator"></span>
                <span>TABLE #{{ $table->table_number }}</span>
                @if($table->area)
                    <span style="opacity: 0.7;">&bull; {{ $table->area }}</span>
                @endif
                <span style="opacity: 0.7;">&bull; {{ $table->capacity }} Seats</span>
            </div>

            @if($activeOrder)
                <div style="background: #ffffff; color: #111827; border-radius: 14px; padding: 12px 16px; margin-top: 14px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-weight: 700; font-size: 13px; color: #16a34a;">
                            &#10004; Running Order: #{{ $activeOrder->order_number }}
                        </div>
                        <div style="font-size: 12px; color: #6b7280;">Status: {{ ucfirst($activeOrder->status) }} &bull; &#8377;{{ number_format((float) $activeOrder->grand_total, 0) }}</div>
                    </div>
                    <a href="{{ route('table.order.track', $activeOrder->order_number) }}" style="background: var(--primary); color: #fff; text-decoration: none; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600;">
                        View
                    </a>
                </div>
            @endif
        </div>
    </header>

    <!-- Sticky Filter & Search Toolbar -->
    <div class="sticky-toolbar">
        <div class="page-container">
            <div class="search-bar">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" id="searchInput" placeholder="Search dishes, drinks, specials..." autocomplete="off">
            </div>

            <div class="filter-tabs">
                <button type="button" class="tab-btn active" data-filter="all">All Dishes</button>
                <button type="button" class="tab-btn" data-filter="general">
                    <span class="type-dot general"></span> Regular / Veg
                </button>
                <button type="button" class="tab-btn" data-filter="jain">
                    <span class="type-dot jain"></span> Jain Specials
                </button>
                @if($combos->count() > 0)
                    <button type="button" class="tab-btn" data-filter="combos">
                        Value Combos ({{ $combos->count() }})
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Dishes Menu List -->
    <main class="page-container" style="margin-top: 12px;">

        <!-- Combos (if any) -->
        @if($combos->count() > 0)
            <div id="combosSection" style="margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 10px; color: var(--text-dark);">
                    &#10024; Value Combos &amp; Meals
                </h3>
                @foreach($combos as $combo)
                    <div class="dish-card combo-item"
                         data-id="{{ $combo->id }}"
                         data-type="combo"
                         data-name="{{ strtolower($combo->name) }}"
                         data-display-name="{{ $combo->name }}"
                         data-price="{{ $combo->price }}">
                        <div class="dish-info">
                            <div class="dish-title">
                                <span class="type-dot general"></span>
                                {{ $combo->name }}
                            </div>
                            <div class="dish-meta">
                                @foreach($combo->items as $ci)
                                    <span>{{ $ci->product?->name }} (x{{ (int) $ci->quantity }})</span>
                                @endforeach
                            </div>
                            <div class="dish-price">&#8377;{{ number_format((float) $combo->price, 0) }}</div>
                        </div>

                        <div id="action-wrapper-combo-{{ $combo->id }}">
                            <button type="button" class="btn-add" onclick="addItem({{ $combo->id }}, '{{ addslashes($combo->name) }}', {{ $combo->price }}, 'combo')">
                                + Add
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- A La Carte Dishes -->
        <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 10px; color: var(--text-dark);">
            Our Signature Dishes
        </h3>

        <div id="dishesList">
            @forelse($products as $product)
                <div class="dish-card dish-item"
                     data-id="{{ $product->id }}"
                     data-type="product"
                     data-name="{{ strtolower($product->name) }}"
                     data-display-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-foodtype="{{ $product->food_type }}">
                    <div class="dish-info">
                        <div class="dish-title">
                            <span class="type-dot {{ $product->food_type }}"></span>
                            {{ $product->name }}
                        </div>
                        <div class="dish-meta">
                            @if($product->size)
                                <span>{{ $product->formatted_size }}</span>
                            @endif
                            <span style="text-transform: capitalize;">{{ $product->food_type }}</span>
                        </div>
                        <div class="dish-price">&#8377;{{ number_format((float) $product->price, 0) }}</div>
                    </div>

                    <div id="action-wrapper-product-{{ $product->id }}">
                        <button type="button" class="btn-add" onclick="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, 'product')">
                            + Add
                        </button>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 40px 20px; color: #6b7280;">
                    <p>No dishes found on menu.</p>
                </div>
            @endforelse
        </div>

        <div id="noResults" style="display: none; text-align: center; padding: 40px 20px; color: #6b7280;">
            <p>No dishes matching your search.</p>
            <button type="button" class="btn-add" onclick="resetSearch()" style="margin-top: 8px;">View All Items</button>
        </div>
    </main>

    <!-- Bottom Floating Cart Bar -->
    <div class="cart-bar-fixed" id="cartBar" style="display: none;" onclick="openDrawer()">
        <div style="display: flex; align-items: center;">
            <span class="cart-pill" id="cartItemCount">0 items</span>
            <span style="font-weight: 800; font-size: 16px;">&#8377;<span id="cartBarAmount">0.00</span></span>
        </div>
        <div style="display: flex; align-items: center; gap: 6px; font-weight: 700;">
            <span>View Order &amp; Cart</span>
            <svg viewBox="0 0 24 24" width="20" height="20" fill="#ffffff"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
        </div>
    </div>

    <!-- Drawer Overlay -->
    <div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>

    <!-- Custom Slide-Up Checkout Drawer -->
    <div class="cart-drawer" id="cartDrawer">
        <div class="drawer-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                @if(setting('brand_logo'))
                    <img src="{{ asset(setting('brand_logo')) }}" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--secondary);">
                @endif
                <div>
                    <div style="font-weight: 800; font-size: 16px;">Table {{ $table->table_number }} Order</div>
                    <div style="font-size: 12px; color: #6b7280;">{{ setting('site_name', 'Brahmani Lili Haldar') }} &bull; Table ID: #{{ $table->id }}</div>
                </div>
            </div>
            <button type="button" onclick="closeDrawer()" style="background: none; border: none; font-size: 24px; color: #6b7280; cursor: pointer;">&times;</button>
        </div>

        <div class="drawer-body">
            <!-- Selected Items -->
            <div style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #6b7280; margin-bottom: 8px;">
                Selected Items
            </div>
            <div id="drawerItemsList"></div>

            <!-- Customer Details & Notes -->
            <div style="background: #f9fafb; border-radius: var(--radius-md); padding: 14px; margin-top: 16px; border: 1px solid var(--border-color);">
                <div style="font-weight: 700; font-size: 12px; text-transform: uppercase; color: #4b5563; margin-bottom: 8px;">
                    Guest Details (Optional)
                </div>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="custName" class="form-input" placeholder="Your Name">
                    <input type="tel" id="custPhone" class="form-input" placeholder="Mobile Number">
                </div>
                <textarea id="orderNotes" class="form-input" rows="2" placeholder="Special cooking instructions (e.g. less spicy, extra napkins)"></textarea>
            </div>

            <!-- Bill Breakdown -->
            <div class="bill-summary">
                <div class="bill-line">
                    <span>Items Subtotal</span>
                    <span>&#8377;<span id="subtotalVal">0.00</span></span>
                </div>
                @foreach($taxes as $tax)
                    <div class="bill-line">
                        <span>{{ $tax->name }} ({{ $tax->type === 'percentage' ? $tax->rate.'%' : '&#8377;'.$tax->rate }})</span>
                        <span class="tax-row" data-rate="{{ $tax->rate }}" data-type="{{ $tax->type }}">&#8377;0.00</span>
                    </div>
                @endforeach
                <div class="bill-line total">
                    <span>Grand Total</span>
                    <span style="color: var(--primary);">&#8377;<span id="grandTotalVal">0.00</span></span>
                </div>
            </div>

            <div style="margin-top: 18px;">
                <button type="button" class="btn-order" id="submitOrderBtn" onclick="submitTableOrder()">
                    <span id="btnText">Confirm &amp; Place Table Order</span>
                    <span class="spinner" id="btnSpinner"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Offline-First Pure Vanilla JavaScript -->
    <script>
        const TABLE_ID = {{ $table->id }};
        const TABLE_NUM = "{{ $table->table_number }}";
        // Always post to current table URL + /order to guarantee same-origin and correct IP
        const ORDER_URL = window.location.pathname.replace(/\/$/, '') + '/order';
        const CSRF_TOKEN = "{{ csrf_token() }}";

        const taxes = [
            @foreach($taxes as $tax)
            {
                name: "{{ $tax->name }}",
                rate: {{ (float) $tax->rate }},
                type: "{{ $tax->type }}"
            },
            @endforeach
        ];

        let cart = {};
        const storageKey = `table_cart_${TABLE_ID}`;

        // Initialize from local storage if available
        try {
            const saved = localStorage.getItem(storageKey);
            if (saved) cart = JSON.parse(saved);
        } catch (e) {
            cart = {};
        }

        document.addEventListener('DOMContentLoaded', function () {
            renderCart();
            updateAllButtons();
            setupSearch();
        });

        function saveCart() {
            try {
                localStorage.setItem(storageKey, JSON.stringify(cart));
            } catch (e) {}
            renderCart();
        }

        function addItem(id, name, price, type = 'product') {
            const key = `${type}_${id}`;
            if (cart[key]) {
                cart[key].qty += 1;
            } else {
                cart[key] = { key: key, id: parseInt(id, 10), type: type, name: name, price: parseFloat(price), qty: 1 };
            }
            saveCart();
            updateButton(key);
        }

        function changeQty(key, delta) {
            if (!cart[key]) return;
            cart[key].qty += delta;
            if (cart[key].qty <= 0) {
                delete cart[key];
            }
            saveCart();
            updateButton(key);
        }

        function updateButton(key) {
            const item = cart[key];
            let type = 'product';
            let id = key;
            if (typeof key === 'string' && key.includes('_')) {
                const parts = key.split('_');
                type = parts[0];
                id = parts[1];
            }

            const wrapper = document.getElementById(`action-wrapper-${type}-${id}`);
            if (!wrapper) return;

            if (item && item.qty > 0) {
                wrapper.innerHTML = `
                    <div class="qty-box">
                        <button class="qty-action" type="button" onclick="changeQty('${key}', -1)">&minus;</button>
                        <span class="qty-val">${item.qty}</span>
                        <button class="qty-action" type="button" onclick="changeQty('${key}', 1)">&plus;</button>
                    </div>
                `;
            } else {
                const card = document.querySelector(`.${type}-item[data-id="${id}"]`);
                const name = card ? card.getAttribute('data-display-name') : '';
                const price = card ? card.getAttribute('data-price') : 0;
                const safeName = name ? name.replace(/'/g, "\\'") : '';
                wrapper.innerHTML = `
                    <button type="button" class="btn-add" onclick="addItem(${id}, '${safeName}', ${price}, '${type}')">
                        + Add
                    </button>
                `;
            }
        }

        function updateAllButtons() {
            document.querySelectorAll('.dish-item').forEach(card => {
                const id = card.getAttribute('data-id');
                updateButton(`product_${id}`);
            });
            document.querySelectorAll('.combo-item').forEach(card => {
                const id = card.getAttribute('data-id');
                updateButton(`combo_${id}`);
            });
        }

        function renderCart() {
            const items = Object.values(cart);
            let count = 0;
            let subtotal = 0;

            items.forEach(i => {
                count += i.qty;
                subtotal += i.price * i.qty;
            });

            let taxAmount = 0;
            taxes.forEach(t => {
                if (t.type === 'percentage') {
                    taxAmount += subtotal * (t.rate / 100);
                } else {
                    taxAmount += t.rate;
                }
            });
            taxAmount = Math.round(taxAmount * 100) / 100;
            const grandTotal = Math.round((subtotal + taxAmount) * 100) / 100;

            // Update floating cart bar
            const bar = document.getElementById('cartBar');
            if (count > 0) {
                bar.style.display = 'flex';
                document.getElementById('cartItemCount').textContent = `${count} item${count > 1 ? 's' : ''}`;
                document.getElementById('cartBarAmount').textContent = grandTotal.toFixed(2);
            } else {
                bar.style.display = 'none';
                closeDrawer();
            }

            // Update Drawer Totals
            document.getElementById('subtotalVal').textContent = subtotal.toFixed(2);
            document.getElementById('grandTotalVal').textContent = grandTotal.toFixed(2);

            document.querySelectorAll('.tax-row').forEach(el => {
                const rate = parseFloat(el.getAttribute('data-rate'));
                const type = el.getAttribute('data-type');
                const tVal = (type === 'percentage') ? (subtotal * (rate / 100)) : rate;
                el.innerHTML = `&#8377;${tVal.toFixed(2)}`;
            });

            const list = document.getElementById('drawerItemsList');
            const submitBtn = document.getElementById('submitOrderBtn');

            if (items.length === 0) {
                list.innerHTML = '<p style="color: #9ca3af; text-align: center; padding: 20px 0;">Cart is empty. Add dishes from the menu.</p>';
                submitBtn.disabled = true;
            } else {
                submitBtn.disabled = false;
                list.innerHTML = items.map(item => `
                    <div class="cart-row">
                        <div>
                            <div style="font-weight: 700; font-size: 14px;">${item.name}</div>
                            <div style="font-size: 12px; color: #6b7280;">
                                &#8377;${item.price.toFixed(2)} &times; ${item.qty}
                                ${item.type === 'combo' ? '<span style="color:#d97706;font-weight:700;">(Combo)</span>' : ''}
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div class="qty-box" style="padding: 2px 4px;">
                                <button class="qty-action" style="width: 24px; height: 24px; font-size: 16px;" onclick="changeQty('${item.key}', -1)">&minus;</button>
                                <span class="qty-val" style="font-size: 13px;">${item.qty}</span>
                                <button class="qty-action" style="width: 24px; height: 24px; font-size: 16px;" onclick="changeQty('${item.key}', 1)">&plus;</button>
                            </div>
                            <div style="font-weight: 800; min-width: 60px; text-align: right;">
                                &#8377;${(item.price * item.qty).toFixed(2)}
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        function openDrawer() {
            document.getElementById('drawerOverlay').classList.add('open');
            document.getElementById('cartDrawer').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('drawerOverlay').classList.remove('open');
            document.getElementById('cartDrawer').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Direct Order Submission
        function submitTableOrder() {
            const items = Object.values(cart);
            if (items.length === 0) return;

            const btn = document.getElementById('submitOrderBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btnText.textContent = 'Sending to Kitchen...';
            spinner.style.display = 'inline-block';

            const payload = {
                items: items.map(i => ({ id: i.id, type: i.type, quantity: i.qty })),
                customer_name: document.getElementById('custName').value.trim() || null,
                customer_phone: document.getElementById('custPhone').value.trim() || null,
                notes: document.getElementById('orderNotes').value.trim() || null,
            };

            fetch(ORDER_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(data => { throw new Error(data.message || 'Order could not be placed.'); });
                }
                return res.json();
            })
            .then(data => {
                localStorage.removeItem(storageKey);
                cart = {};
                window.location.href = data.redirect;
            })
            .catch(err => {
                alert('Could not place order: ' + err.message);
                btn.disabled = false;
                btnText.textContent = 'Confirm & Place Table Order';
                spinner.style.display = 'none';
            });
        }

        // Search & Filter
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            const tabs = document.querySelectorAll('.tab-btn');
            const dishCards = document.querySelectorAll('.dish-item');
            const combosSection = document.getElementById('combosSection');
            const noResults = document.getElementById('noResults');

            let currentFilter = 'all';
            let query = '';

            function filterDishes() {
                let matches = 0;
                dishCards.forEach(card => {
                    const name = card.getAttribute('data-name') || '';
                    const ftype = card.getAttribute('data-foodtype') || '';

                    const matchesSearch = !query || name.includes(query);
                    let matchesTab = true;

                    if (currentFilter === 'general') matchesTab = (ftype === 'general');
                    else if (currentFilter === 'jain') matchesTab = (ftype === 'jain');
                    else if (currentFilter === 'combos') matchesTab = false;

                    if (matchesSearch && matchesTab) {
                        card.style.display = 'flex';
                        matches++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (combosSection) {
                    combosSection.style.display = (currentFilter === 'combos' || currentFilter === 'all') ? 'block' : 'none';
                }

                noResults.style.display = (matches === 0 && currentFilter !== 'combos') ? 'block' : 'none';
            }

            searchInput.addEventListener('input', function () {
                query = this.value.toLowerCase().trim();
                filterDishes();
            });

            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    filterDishes();
                });
            });

            window.resetSearch = function () {
                searchInput.value = '';
                query = '';
                tabs.forEach(t => t.classList.remove('active'));
                tabs[0].classList.add('active');
                currentFilter = 'all';
                filterDishes();
            };
        }
    </script>
</body>
</html>
