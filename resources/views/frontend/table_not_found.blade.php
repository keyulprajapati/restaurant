<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Table Not Found | {{ setting('site_name', 'Brahmani Lili Haldar') }}</title>

    @if(setting('favicon_icon'))
        <link rel="icon" href="{{ asset(setting('favicon_icon')) }}">
    @endif

    <style>
        :root {
            --primary: {{ setting('theme_primary_color', '#23422A') }};
            --secondary: {{ setting('theme_secondary_color', '#C69A39') }};
            --bg-color: #f7f9f7;
            --card-bg: #ffffff;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --radius-lg: 24px;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .error-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border-color);
            max-width: 440px;
            width: 100%;
            padding: 40px 24px;
            text-align: center;
        }

        .brand-logo-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid var(--secondary);
            box-shadow: 0 4px 12px rgba(198, 154, 57, 0.35);
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .brand-logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #fee2e2;
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .icon-circle svg {
            width: 32px;
            height: 32px;
            fill: currentColor;
        }

        h1 {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .info-pill {
            background: #f3f4f6;
            padding: 12px 18px;
            border-radius: 14px;
            font-size: 13px;
            color: #4b5563;
            border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <div class="error-card">
        @if(setting('brand_logo'))
            <div class="brand-logo-wrapper">
                <img src="{{ asset(setting('brand_logo')) }}" alt="Logo">
            </div>
        @else
            <div class="icon-circle">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
        @endif

        <h1>Table Not Found</h1>
        <p>
            Table <strong>"{{ $identifier }}"</strong> is currently not active or could not be verified. Please ask our restaurant floor staff for assistance.
        </p>

        <div class="info-pill">
            &bull; {{ setting('site_name', 'Brahmani Lili Haldar') }} &bull;
        </div>
    </div>
</body>
</html>
