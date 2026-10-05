<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #f3f4f6;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111827;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 18px 0;
        }

        .receipt {
            width: 100%;
            max-width: 430px;
            margin: 0;
            padding: 14px 14px 12px;
            box-sizing: border-box;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 25px rgba(17, 24, 39, 0.06);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
            margin-bottom: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%);
            border-radius: 12px 12px 0 0;
        }

        .company {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            color: #1f2937;
        }

        .meta {
            font-size: 10px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 700;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            font-size: 13px;
            padding: 2px 0;
        }

        .section {
            margin-top: 12px;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        .items th,
        .items td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px 0;
            vertical-align: top;
        }

        .items th {
            text-align: left;
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .items td:nth-child(2),
        .items td:nth-child(3),
        .items th:nth-child(2),
        .items th:nth-child(3) {
            text-align: right;
        }

        .item-name {
            font-weight: 700;
            color: #111827;
        }

        .item-note {
            display: block;
            font-size: 10px;
            color: #6b7280;
            margin-top: 2px;
        }

        .total-box {
            margin-top: 12px;
            padding-top: 10px;
            border-top: 2px solid #111827;
            font-size: 13px;
            background: #fafafa;
            border-radius: 8px;
            padding-left: 8px;
            padding-right: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .grand {
            font-size: 17px;
            font-weight: 800;
            margin-top: 8px;
            color: #111827;
        }

        .footer {
            margin-top: 18px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
            letter-spacing: 0.4px;
        }

        @media print {
            html, body {
                width: 100%;
                height: 100%;
                overflow: hidden;
            }

            body {
                display: block;
                padding: 0;
                margin: 0;
            }

            .receipt {
                width: 100%;
                max-width: none;
                margin: 0;
                border: none;
                border-radius: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="company">{{ setting('site_name', 'Restaurant') }}</div>
            <div class="meta">Invoice / Receipt</div>
        </div>

        <div class="row">
            <span><strong>Order</strong></span>
            <span>{{ $order->order_number }}</span>
        </div>

        <div class="row">
            <span><strong>Date</strong></span>
            <span>{{ $order->created_at->format('d M Y h:i A') }}</span>
        </div>

        <div class="row">
            <span><strong>Customer</strong></span>
            <span>{{ $order->customer?->name ?? 'Walk-in Customer' }}</span>
        </div>

        <div class="row">
            <span><strong>Table</strong></span>
            <span>{{ $order->table?->table_number ?? '-' }}</span>
        </div>

        <div class="section">
            <table class="items">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Amt</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <span class="item-name">{{ $item->item_name }}</span>
                                @if($item->size)
                                    <span class="item-note">{{ rtrim(rtrim(number_format((float) $item->size, 3, '.', ''), '0'), '.') }}</span>
                                @endif
                                @if($item->unit)
                                    <span class="item-note">{{ $item->unit }}</span>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ number_format((float) $item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-box">
            <div class="total-row">
                <span>Subtotal</span>
                <span>₹{{ number_format((float) $order->subtotal, 2) }}</span>
            </div>

            @if((float) $order->discount > 0)
                <div class="total-row">
                    <span>Discount</span>
                    <span>₹{{ number_format((float) $order->discount, 2) }}</span>
                </div>
            @endif

            <div class="total-row">
                <span>Tax</span>
                <span>₹{{ number_format((float) $order->tax, 2) }}</span>
            </div>

            <div class="total-row grand">
                <span>Total</span>
                <span>₹{{ number_format((float) $order->grand_total, 2) }}</span>
            </div>
        </div>

        <div class="footer">
            Thank you for dining with us!
        </div>
    </div>

</body>
</html>
