<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Stand - Table {{ $table->table_number }} | {{ setting('site_name', 'Restaurant') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: {{ setting('theme_primary_color', '#23422A') }};
            --secondary-color: {{ setting('theme_secondary_color', '#C69A39') }};
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f0f2f5;
            color: #1f2937;
            padding: 30px 15px;
        }

        .no-print-toolbar {
            max-width: 500px;
            margin: 0 auto 20px auto;
        }

        .table-tent-card {
            max-width: 440px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 2px solid rgba(198, 154, 57, 0.25);
            overflow: hidden;
            text-align: center;
            position: relative;
        }

        .card-header-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, #152518 100%);
            color: #ffffff;
            padding: 32px 24px 24px 24px;
            position: relative;
        }

        .card-header-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary-color);
            border-radius: 2px;
        }

        .restaurant-brand {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .table-pill-badge {
            display: inline-block;
            background: var(--secondary-color);
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            padding: 5px 18px;
            border-radius: 50px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .card-body-content {
            padding: 32px 28px;
        }

        .qr-frame {
            display: inline-block;
            background: #ffffff;
            padding: 16px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            border: 2px dashed rgba(35, 66, 42, 0.2);
            margin-bottom: 20px;
        }

        .qr-frame svg {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .table-title {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .table-meta {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .instruction-steps {
            background: #fdfbf7;
            border: 1px solid #fae8c8;
            border-radius: 16px;
            padding: 16px 20px;
            text-align: left;
            margin-bottom: 20px;
        }

        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
        }

        .step-item:last-child {
            margin-bottom: 0;
        }

        .step-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--primary-color);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .card-footer-note {
            border-top: 1px solid #f3f4f6;
            padding: 16px 20px;
            background: #fafafa;
            font-size: 12px;
            color: #9ca3af;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
                margin: 0;
            }

            .no-print-toolbar {
                display: none !important;
            }

            .table-tent-card {
                box-shadow: none;
                border: 2px solid #23422A;
                page-break-inside: avoid;
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-toolbar d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3" style="background: var(--primary-color); border-color: var(--primary-color);">
                <i class="bi bi-printer me-1"></i> Print Stand Card
            </button>
            <a href="{{ $url }}" target="_blank" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-box-arrow-up-right me-1"></i> Open Menu
            </a>
        </div>
    </div>

    <div class="table-tent-card">
        <div class="card-header-banner">
            @if(setting('brand_logo'))
                <img src="{{ asset(setting('brand_logo')) }}" alt="Logo" style="width: 58px; height: 58px; border-radius: 50%; object-fit: cover; border: 2.5px solid var(--secondary-color); background: #ffffff; margin-bottom: 10px; box-shadow: 0 4px 14px rgba(0,0,0,0.35);">
            @endif
            <div class="restaurant-brand">
                {{ setting('site_name', 'Brahmani Lili Haldar') }}
            </div>
            <div class="table-pill-badge">
                <i class="bi bi-qr-code me-1"></i> Contactless Dining
            </div>
        </div>

        <div class="card-body-content">
            <div class="table-title">
                TABLE {{ $table->table_number }}
            </div>
            <div class="table-meta">
                @if($table->area)
                    <span class="me-2"><i class="bi bi-geo-alt me-1"></i>{{ $table->area }}</span>
                @endif
                <span><i class="bi bi-people me-1"></i>Capacity: {{ $table->capacity }}</span>
            </div>

            <div class="qr-frame">
                {!! $svg !!}
            </div>

            <h5 class="fw-bold mb-2 text-dark">Scan to View Menu & Order</h5>
            <p class="text-muted small mb-3">No app required. Use your smartphone camera to scan and place orders directly to this table.</p>

            <div class="instruction-steps">
                <div class="step-item">
                    <span class="step-badge">1</span>
                    <span>Point your phone camera at this QR code</span>
                </div>
                <div class="step-item">
                    <span class="step-badge">2</span>
                    <span>Browse our dishes, chef specials & combos</span>
                </div>
                <div class="step-item">
                    <span class="step-badge">3</span>
                    <span>Add to cart & place order directly to Table {{ $table->table_number }}</span>
                </div>
            </div>
        </div>

        <div class="card-footer-note">
            <i class="bi bi-shield-check me-1 text-success"></i> Table ID: #{{ $table->id }} &bull; Powered by Restaurant POS
        </div>
    </div>

</body>
</html>
