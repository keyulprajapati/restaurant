@extends('layouts.admin')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Branch-wise Sales Report</h4>
        <p class="text-muted mb-0">Sales totals by branch for the selected date range. Cancelled orders are excluded.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3 align-items-end">
                <div class="col-sm-5 col-md-4">
                    <label for="start_date" class="form-label">From</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        class="form-control"
                        required>
                </div>
                <div class="col-sm-5 col-md-4">
                    <label for="end_date" class="form-label">To</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $endDate }}"
                        class="form-control"
                        min="{{ $startDate }}"
                        required>
                </div>
                <div class="col-sm-2 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Apply</button>
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-light">Today</a>
                </div>
            </form>
            @if($errors->has('start_date') || $errors->has('end_date'))
                <div class="text-danger small mt-3">
                    {{ $errors->first('start_date') ?: $errors->first('end_date') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card h-100">
                <div class="text-muted small">Orders</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['order_count']) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card h-100">
                <div class="text-muted small">Subtotal</div>
                <div class="fs-3 fw-bold">₹{{ number_format($summary['subtotal'], 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card h-100">
                <div class="text-muted small">Discount</div>
                <div class="fs-3 fw-bold">₹{{ number_format($summary['discount'], 2) }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card h-100">
                <div class="text-muted small">Total Sales</div>
                <div class="fs-3 fw-bold text-success">₹{{ number_format($summary['sales'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-1">Sales by Branch</h5>
            <p class="text-muted small mb-0">
                {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                –
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Branch</th>
                            <th class="text-end">Orders</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Discount</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end pe-4">Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branchSales as $branch)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $branch['name'] }}</td>
                                <td class="text-end">{{ number_format($branch['order_count']) }}</td>
                                <td class="text-end">₹{{ number_format($branch['subtotal'], 2) }}</td>
                                <td class="text-end">₹{{ number_format($branch['discount'], 2) }}</td>
                                <td class="text-end">₹{{ number_format($branch['tax'], 2) }}</td>
                                <td class="text-end pe-4 fw-bold">₹{{ number_format($branch['sales'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No branches are available for this account.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($branchSales->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td class="ps-4">Total</td>
                                <td class="text-end">{{ number_format($summary['order_count']) }}</td>
                                <td class="text-end">₹{{ number_format($summary['subtotal'], 2) }}</td>
                                <td class="text-end">₹{{ number_format($summary['discount'], 2) }}</td>
                                <td class="text-end">₹{{ number_format($summary['tax'], 2) }}</td>
                                <td class="text-end pe-4">₹{{ number_format($summary['sales'], 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="mb-3">
            <h5 class="fw-bold mb-1">Detailed Orders by Branch</h5>
            <p class="text-muted small mb-0">Order-level totals and item breakdown for the selected period.</p>
        </div>

        @forelse($branchDetails as $branch)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">{{ $branch['name'] }}</h6>
                        <span class="badge text-bg-light border">
                            {{ number_format($branch['orders']->count()) }}
                            {{ \Illuminate\Support\Str::plural('order', $branch['orders']->count()) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order / Date</th>
                                    <th>Customer / Type</th>
                                    <th>Payment</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Tax</th>
                                    <th class="text-end pe-4">Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branch['orders'] as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold text-decoration-none">
                                                {{ $order->order_number }}
                                            </a>
                                            <div class="small text-muted">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                                            @if($order->table)
                                                <div class="small text-muted">Table {{ $order->table->table_number }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $order->customer?->name ?? 'Walk-in Customer' }}</div>
                                            <div class="small text-muted">
                                                {{ \Illuminate\Support\Str::headline($order->order_type) }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $order->payment_method ? \Illuminate\Support\Str::upper($order->payment_method) : 'Not recorded' }}
                                        </td>
                                        <td class="small">
                                            @forelse($order->items as $item)
                                                <div>
                                                    {{ $item->item_name }}
                                                    <span class="text-muted">× {{ rtrim(rtrim(number_format((float) $item->quantity, 2, '.', ''), '0'), '.') }}</span>
                                                    <span class="text-muted">₹{{ number_format((float) $item->total, 2) }}</span>
                                                </div>
                                            @empty
                                                <span class="text-muted">No items</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <span class="badge text-bg-{{ in_array($order->status, ['completed', 'served'], true) ? 'success' : ($order->status === 'ready' ? 'info' : 'secondary') }}">
                                                {{ \Illuminate\Support\Str::headline($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end">₹{{ number_format((float) $order->subtotal, 2) }}</td>
                                        <td class="text-end">₹{{ number_format((float) $order->discount, 2) }}</td>
                                        <td class="text-end">₹{{ number_format((float) $order->tax, 2) }}</td>
                                        <td class="text-end pe-4 fw-bold">₹{{ number_format((float) $order->grand_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">No sales for this branch in the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5 text-muted">No branches are available for this account.</div>
            </div>
        @endforelse
    </div>
@endsection
