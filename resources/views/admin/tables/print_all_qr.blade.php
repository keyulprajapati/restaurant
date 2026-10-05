<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Table QR Codes | {{ setting('site_name', 'Restaurant') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: {{ setting('theme_primary_color', '#23422A') }};
            --secondary-color: {{ setting('theme_secondary_color', '#C69A39') }};
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f3f4f6;
            padding: 30px 15px;
            color: #1f2937;
        }

        .no-print-toolbar {
            max-width: 1000px;
            margin: 0 auto 25px auto;
        }

        .qr-sheet-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .qr-card-item {
            background: #ffffff;
            border-radius: 20px;
            border: 2px solid rgba(35, 66, 42, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 24px 20px;
            text-align: center;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .restaurant-brand {
            font-family: 'Cinzel', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .table-badge {
            background: var(--primary-color);
            color: #ffffff;
            font-size: 20px;
            font-weight: 800;
            padding: 4px 16px;
            border-radius: 50px;
            margin-bottom: 4px;
            display: inline-block;
        }

        .table-area {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .qr-container {
            background: #ffffff;
            padding: 12px;
            border: 2px dashed rgba(198, 154, 57, 0.4);
            border-radius: 16px;
            margin-bottom: 14px;
        }

        .qr-container svg {
            display: block;
            width: 180px;
            height: 180px;
        }

        .scan-action {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2px;
        }

        .scan-subtext {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .card-footer-info {
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px dashed #e5e7eb;
            padding-top: 8px;
            width: 100%;
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

            .qr-sheet-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .qr-card-item {
                box-shadow: none;
                border: 2px solid #23422A;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-toolbar d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.tables.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Tables
            </a>
            <span class="text-muted small">Showing {{ $tables->count() }} active tables</span>
        </div>
        <button onclick="window.print()" class="btn btn-primary px-4" style="background: var(--primary-color); border-color: var(--primary-color);">
            <i class="bi bi-printer me-2"></i> Print All QR Cards
        </button>
    </div>

    <div class="qr-sheet-grid">
        @forelse($tables as $table)
            <div class="qr-card-item">
                @if(setting('brand_logo'))
                    <img src="{{ asset(setting('brand_logo')) }}" alt="Logo" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid var(--secondary-color); background: #ffffff; margin-bottom: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                @endif
                <div class="restaurant-brand">
                    {{ setting('site_name', 'Brahmani Lili Haldar') }}
                </div>
                <div class="table-badge mt-2">
                    TABLE {{ $table->table_number }}
                </div>
                <div class="table-area">
                    {{ $table->area ?: 'Dine-In Dining' }} &bull; Seats {{ $table->capacity }}
                </div>

                <div class="qr-container">
                    {!! $table->svg !!}
                </div>

                <div class="scan-action">Scan to Order</div>
                <div class="scan-subtext">Point camera to browse menu & order</div>

                <div class="card-footer-info">
                    Table ID: #{{ $table->id }} &bull; {{ $table->table_number }}
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
