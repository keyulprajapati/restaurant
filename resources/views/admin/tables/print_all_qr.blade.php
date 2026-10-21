<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Table QR Stands - {{ setting('site_name', 'Brahmani Lili Haldar') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: {{ setting('theme_primary_color', '#23422A') }};
            --card-bg: #F6F0E7;
            --stroke-green: #364728;
            --badge-num-green: #1E2D16;
            --heading-charcoal: #48473F;
            --frame-border: #565750;
            --corner-bracket: #565750;
            --accent-gold: #E5C564;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
            background: #eef1f5;
            color: #1f2937;
            padding: 24px 15px 40px 15px;
            min-height: 100vh;
        }

        .no-print-toolbar {
            max-width: 1060px;
            margin: 0 auto 20px auto;
        }

        .print-tip-alert {
            max-width: 1060px;
            margin: 0 auto 24px auto;
            background: #ffffff;
            border-left: 4px solid var(--primary-color);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #4b5563;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        /* Sheet Grid */
        .qr-sheet-grid {
            max-width: 1060px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 32px;
            justify-content: center;
        }

        /* Printable Stand Card */
        .stand-card {
            width: 390px;
            background-color: var(--card-bg);
            border-radius: 28px;
            padding: 36px 26px 32px 26px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0,0,0,0.05);
            margin: 0 auto;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Watermark in background behind lower-middle content */
        .brand-watermark {
            position: absolute;
            top: 235px;
            left: 50%;
            transform: translateX(-50%);
            width: 350px;
            opacity: 0.18;
            pointer-events: none;
            z-index: 1;
            filter: contrast(1.1);
        }

        .card-inner {
            position: relative;
            z-index: 2;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* 1. Scalloped Rosette Table Badge (16 outward smooth sinusoidal lobes) */
        .badge-rosette {
            width: 172px;
            height: 172px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .rosette-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .badge-content {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: -2px;
            text-align: center;
            padding: 0 8px;
        }

        .badge-label {
            font-size: 20px;
            font-weight: 600;
            color: var(--stroke-green);
            letter-spacing: -0.2px;
            line-height: 1.15;
            margin-bottom: 1px;
        }

        .badge-table-number {
            font-size: 70px;
            font-weight: 900;
            color: var(--badge-num-green);
            line-height: 0.95;
            letter-spacing: -2px;
        }

        .badge-table-number.text-scale-md {
            font-size: 50px;
            letter-spacing: -1px;
        }

        .badge-table-number.text-scale-sm {
            font-size: 36px;
            letter-spacing: normal;
        }

        /* 2. "Our Menu" title */
        .menu-heading {
            font-size: 29px;
            font-weight: 800;
            color: var(--heading-charcoal);
            margin-bottom: 16px;
            letter-spacing: -0.4px;
            text-align: center;
            line-height: 1.1;
        }

        /* 3. Outer Scanner Frame */
        .scanner-frame-wrap {
            width: 280px;
            height: 280px;
            border: 2px solid var(--frame-border);
            border-radius: 34px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            margin-bottom: 20px;
        }

        /* 4 Scanner Corner Viewfinder Brackets */
        .corner-bracket {
            position: absolute;
            width: 26px;
            height: 26px;
            border-color: var(--corner-bracket);
            border-style: solid;
        }

        .bracket-tl {
            top: 13px;
            left: 13px;
            border-width: 2.4px 0 0 2.4px;
        }

        .bracket-tr {
            top: 13px;
            right: 13px;
            border-width: 2.4px 2.4px 0 0;
        }

        .bracket-bl {
            bottom: 13px;
            left: 13px;
            border-width: 0 0 2.4px 2.4px;
        }

        .bracket-br {
            bottom: 13px;
            right: 13px;
            border-width: 0 2.4px 2.4px 0;
        }

        /* White QR Container inside brackets */
        .qr-card-white {
            background-color: #ffffff;
            width: 226px;
            height: 226px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            padding: 8px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .qr-card-white svg {
            width: 100% !important;
            height: 100% !important;
            max-width: 100%;
            display: block;
        }

        /* 4. Crossed Fork and Spoon with Golden Sprout Accent */
        .utensils-icon-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 6px;
        }

        /* 5. "Order here" title */
        .order-here-heading {
            font-size: 26px;
            font-weight: 800;
            color: var(--heading-charcoal);
            letter-spacing: -0.3px;
            text-align: center;
            line-height: 1.1;
        }

        /* Print Media Styles */
        @media print {
            @page {
                size: auto;
                margin: 8mm;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print-toolbar,
            .print-tip-alert {
                display: none !important;
            }

            .qr-sheet-grid {
                display: block !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            .stand-card {
                box-shadow: none !important;
                border: 1px solid rgba(0,0,0,0.08) !important;
                margin: 0 auto 30px auto !important;
                page-break-after: always;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .stand-card:last-child {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Top Toolbar (Hidden on Print) -->
    <div class="no-print-toolbar d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Tables
            </a>
            <span class="badge bg-light text-dark border px-3 py-2">
                <i class="bi bi-table me-1"></i> {{ $tables->count() }} Tables
            </span>
        </div>

        <div class="d-flex align-items-center gap-2">
            @if(isset($areas) && $areas->count() > 0)
                <form method="GET" action="{{ route('admin.tables.print-all-qr') }}" class="d-inline-flex align-items-center">
                    <select name="area" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Dining Areas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area }}" {{ request('area') == $area ? 'selected' : '' }}>{{ $area }}</option>
                        @endforeach
                    </select>
                </form>
            @endif

            <button onclick="window.print()" class="btn btn-primary btn-sm px-4 shadow-sm" style="background: var(--primary-color); border-color: var(--primary-color);">
                <i class="bi bi-printer me-2"></i> Print All QR Cards
            </button>
        </div>
    </div>

    <!-- Print Tip for User -->
    <div class="print-tip-alert d-flex align-items-center">
        <i class="bi bi-info-circle-fill me-2 text-primary"></i>
        <span><strong>Print Tip:</strong> In your browser print dialog, enable <strong>"Background graphics"</strong> to ensure the linen background and watermark print cleanly on all cards.</span>
    </div>

    <!-- Grid of Stand Cards -->
    <div class="qr-sheet-grid">
        @forelse($tables as $table)
            @php
                $rawNumber = $table->table_number;
                $cleanNumber = preg_replace('/^Table\s*(No\.?)?\s*/i', '', $rawNumber);
                $cleanNumber = preg_replace('/^T-?\s*/i', '', $cleanNumber);
                $numLength = mb_strlen($cleanNumber);
                $scaleClass = '';
                if ($numLength >= 5) {
                    $scaleClass = 'text-scale-sm';
                } elseif ($numLength >= 3) {
                    $scaleClass = 'text-scale-md';
                }
            @endphp

            <div class="stand-card">
                <!-- Background Watermark Logo -->
                @if(file_exists(public_path('watermark_logo.png')))
                    <img class="brand-watermark" src="{{ asset('watermark_logo.png') }}" alt="Watermark">
                @elseif(setting('brand_logo') && file_exists(public_path(setting('brand_logo'))))
                    <img class="brand-watermark" src="{{ asset(setting('brand_logo')) }}" alt="Watermark">
                @endif
                <div class="restaurant-brand">
                    {{ setting('site_name', 'Brahmani Lili Haldar') }}
                </div>
                @if($table->branch)
                    <div class="small fw-semibold">{{ $table->branch->name }}</div>
                @endif
                <div class="table-badge mt-2">
                    TABLE {{ $table->table_number }}
                </div>
                <div class="table-area">
                    {{ $table->area ?: 'Dine-In Dining' }} &bull; Seats {{ $table->capacity }}
                </div>

                <div class="card-inner">
                    <!-- 1. Scalloped Rosette Table Badge (16 outward smooth sinusoidal lobes) -->
                    <div class="badge-rosette">
                        <svg class="rosette-svg" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M 100 14.5 L 102.09 15.02 L 104.1 16.5 L 105.99 18.73 L 107.74 21.38 L 109.37 24.06 L 110.92 26.4 L 112.48 28.08 L 114.14 28.89 L 115.99 28.78 L 118.08 27.83 L 120.41 26.26 L 122.93 24.4 L 125.56 22.63 L 128.16 21.29 L 130.59 20.69 L 132.72 21.01 L 134.45 22.29 L 135.74 24.43 L 136.64 27.21 L 137.24 30.33 L 137.71 33.43 L 138.25 36.18 L 139.05 38.33 L 140.28 39.72 L 142.03 40.32 L 144.32 40.24 L 147.07 39.68 L 150.12 38.93 L 153.23 38.3 L 156.14 38.06 L 158.61 38.44 L 160.46 39.54 L 161.56 41.39 L 161.94 43.86 L 161.7 46.77 L 161.07 49.88 L 160.32 52.93 L 159.76 55.68 L 159.68 57.97 L 160.28 59.72 L 161.67 60.95 L 163.82 61.75 L 166.57 62.29 L 169.67 62.76 L 172.79 63.36 L 175.57 64.26 L 177.71 65.55 L 178.99 67.28 L 179.31 69.41 L 178.71 71.84 L 177.37 74.44 L 175.6 77.07 L 173.74 79.59 L 172.17 81.92 L 171.22 84.01 L 171.11 85.86 L 171.92 87.52 L 173.6 89.08 L 175.94 90.63 L 178.62 92.26 L 181.27 94.01 L 183.5 95.9 L 184.98 97.91 L 185.5 100 L 184.98 102.09 L 183.5 104.1 L 181.27 105.99 L 178.62 107.74 L 175.94 109.37 L 173.6 110.92 L 171.92 112.48 L 171.11 114.14 L 171.22 115.99 L 172.17 118.08 L 173.74 120.41 L 175.6 122.93 L 177.37 125.56 L 178.71 128.16 L 179.31 130.59 L 178.99 132.72 L 177.71 134.45 L 175.57 135.74 L 172.79 136.64 L 169.67 137.24 L 166.57 137.71 L 163.82 138.25 L 161.67 139.05 L 160.28 140.28 L 159.68 142.03 L 159.76 144.32 L 160.32 147.07 L 161.07 150.12 L 161.7 153.23 L 161.94 156.14 L 161.56 158.61 L 160.46 160.46 L 158.61 161.56 L 156.14 161.94 L 153.23 161.7 L 150.12 161.07 L 147.07 160.32 L 144.32 159.76 L 142.03 159.68 L 140.28 160.28 L 139.05 161.67 L 138.25 163.82 L 137.71 166.57 L 137.24 169.67 L 136.64 172.79 L 135.74 175.57 L 134.45 177.71 L 132.72 178.99 L 130.59 179.31 L 128.16 178.71 L 125.56 177.37 L 122.93 175.6 L 120.41 173.74 L 118.08 172.17 L 115.99 171.22 L 114.14 171.11 L 112.48 171.92 L 110.92 173.6 L 109.37 175.94 L 107.74 178.62 L 105.99 181.27 L 104.1 183.5 L 102.09 184.98 L 100 185.5 L 97.91 184.98 L 95.9 183.5 L 94.01 181.27 L 92.26 178.62 L 90.63 175.94 L 89.08 173.6 L 87.52 171.92 L 85.86 171.11 L 84.01 171.22 L 81.92 172.17 L 79.59 173.74 L 77.07 175.6 L 74.44 177.37 L 71.84 178.71 L 69.41 179.31 L 67.28 178.99 L 65.55 177.71 L 64.26 175.57 L 63.36 172.79 L 62.76 169.67 L 62.29 166.57 L 61.75 163.82 L 60.95 161.67 L 59.72 160.28 L 57.97 159.68 L 55.68 159.76 L 52.93 160.32 L 49.88 161.07 L 46.77 161.7 L 43.86 161.94 L 41.39 161.56 L 39.54 160.46 L 38.44 158.61 L 38.06 156.14 L 38.3 153.23 L 38.93 150.12 L 39.68 147.07 L 40.24 144.32 L 40.32 142.03 L 39.72 140.28 L 38.33 139.05 L 36.18 138.25 L 33.43 137.71 L 30.33 137.24 L 27.21 136.64 L 24.43 135.74 L 22.29 134.45 L 21.01 132.72 L 20.69 130.59 L 21.29 128.16 L 22.63 125.56 L 24.4 122.93 L 26.26 120.41 L 27.83 118.08 L 28.78 115.99 L 28.89 114.14 L 28.08 112.48 L 26.4 110.92 L 24.06 109.37 L 21.38 107.74 L 18.73 105.99 L 16.5 104.1 L 15.02 102.09 L 14.5 100 L 15.02 97.91 L 16.5 95.9 L 18.73 94.01 L 21.38 92.26 L 24.06 90.63 L 26.4 89.08 L 28.08 87.52 L 28.89 85.86 L 28.78 84.01 L 27.83 81.92 L 26.26 79.59 L 24.4 77.07 L 22.63 74.44 L 21.29 71.84 L 20.69 69.41 L 21.01 67.28 L 22.29 65.55 L 24.43 64.26 L 27.21 63.36 L 30.33 62.76 L 33.43 62.29 L 36.18 61.75 L 38.33 60.95 L 39.72 59.72 L 40.32 57.97 L 40.24 55.68 L 39.68 52.93 L 38.93 49.88 L 38.3 46.77 L 38.06 43.86 L 38.44 41.39 L 39.54 39.54 L 41.39 38.44 L 43.86 38.06 L 46.77 38.3 L 49.88 38.93 L 52.93 39.68 L 55.68 40.24 L 57.97 40.32 L 59.72 39.72 L 60.95 38.33 L 61.75 36.18 L 62.29 33.43 L 62.76 30.33 L 63.36 27.21 L 64.26 24.43 L 65.55 22.29 L 67.28 21.01 L 69.41 20.69 L 71.84 21.29 L 74.44 22.63 L 77.07 24.4 L 79.59 26.26 L 81.92 27.83 L 84.01 28.78 L 85.86 28.89 L 87.52 28.08 L 89.08 26.4 L 90.63 24.06 L 92.26 21.38 L 94.01 18.73 L 95.9 16.5 L 97.91 15.02 Z"
                                  fill="#F6F0E7" stroke="#364728" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                        <div class="badge-content">
                            <span class="badge-label">Table No.</span>
                            <span class="badge-table-number {{ $scaleClass }}">{{ $cleanNumber }}</span>
                        </div>
                    </div>

                    <!-- 2. "Our Menu" -->
                    <div class="menu-heading">Our Menu</div>

                    <!-- 3. Outer Scanner Frame with Corner Scanner Brackets -->
                    <div class="scanner-frame-wrap">
                        <div class="corner-bracket bracket-tl"></div>
                        <div class="corner-bracket bracket-tr"></div>
                        <div class="corner-bracket bracket-bl"></div>
                        <div class="corner-bracket bracket-br"></div>

                        <div class="qr-card-white">
                            {!! $table->svg !!}
                        </div>
                    </div>

                    <!-- 4. Crossed Fork & Spoon with Golden Leaves -->
                    <div class="utensils-icon-container">
                        <svg width="60" height="54" viewBox="0 0 68 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Golden Leaves Accent behind handles -->
                            <g fill="#DFBA53" opacity="0.95">
                                <path d="M 34 46 C 28 43 21 35 22 26 C 27 28 32 35 34 44 Z" />
                                <path d="M 34 46 C 40 43 47 35 46 26 C 41 28 36 35 34 44 Z" />
                            </g>

                            <!-- Fork (Left, angled -23deg) - behind spoon crossing -->
                            <g transform="translate(34, 37) rotate(-23) translate(-34, -37)">
                                <path d="M 32.2 24 L 32.2 49 C 32.2 50.8 33 52 34 52 C 35 52 35.8 50.8 35.8 49 L 35.8 24" 
                                      stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                                <path d="M 27 10 L 28.5 21 C 28.8 23.5 31 25.5 34 25.5 C 37 25.5 39.2 23.5 39.5 21 L 41 10 
                                         C 40.8 9.5 39.5 9.5 39.3 10.2 L 37.8 19.5 C 37.6 20.2 36.6 20.2 36.4 19.5 L 35.2 10.2 
                                         C 35 9.5 33.8 9.5 33.6 10.2 L 32.4 19.5 C 32.2 20.2 31.2 20.2 31 19.5 L 29.5 10.2 
                                         C 29.3 9.5 28 9.5 27.8 10 Z" 
                                      stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                            </g>

                            <!-- Spoon (Right, angled +23deg) - in front of fork -->
                            <g transform="translate(34, 37) rotate(23) translate(-34, -37)">
                                <path d="M 32.2 23 L 32.2 49 C 32.2 50.8 33 52 34 52 C 35 52 35.8 50.8 35.8 49 L 35.8 23" 
                                      stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                                <path d="M 34 7.5 C 29.5 7.5 26.5 12 27 17 C 27.5 21 30.5 24 34 24 C 37.5 24 40.5 21 41 17 C 41.5 12 38.5 7.5 34 7.5 Z" 
                                      stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                            </g>
                        </svg>
                    </div>

                    <!-- 5. "Order here" -->
                    <div class="order-here-heading">Order here</div>
                </div>
            </div>
        @empty
            <div class="text-center py-5 col-12">
                <p class="text-muted">No active tables found to print.</p>
            </div>
        @endforelse
    </div>

</body>
</html>
