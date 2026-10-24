@extends('layouts.admin')

@section('title', 'Payment Report')
@section('page-title', 'Payment Report')

@section('content')
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Branch-wise Payment Report</h4>
        <p class="text-muted mb-0">Recorded payments grouped by branch and payment method. Cancelled orders and orders without a recorded method are excluded.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.reports.payments') }}" class="row g-3 align-items-end">
                <div class="col-sm-5 col-md-3">
                    <label for="start_date" class="form-label">From</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $startDate }}"
                        class="form-control"
                        required>
                </div>
                <div class="col-sm-5 col-md-3">
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
                <div class="col-sm-6 col-md-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="form-select">
                        <option value="" @selected($paymentMethod === '')>All Methods</option>
                        <option value="cash" @selected($paymentMethod === 'cash')>Cash</option>
                        <option value="upi" @selected($paymentMethod === 'upi')>UPI</option>
                        <option value="card" @selected($paymentMethod === 'card')>Card</option>
                        <option value="other" @selected($paymentMethod === 'other')>Other</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Apply</button>
                    <a href="{{ route('admin.reports.payments') }}" class="btn btn-light">Today</a>
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
        <div class="col-sm-6">
            <div class="stat-card h-100">
                <div class="text-muted small">Recorded Payments</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['order_count']) }}</div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="stat-card h-100">
                <div class="text-muted small">Total Amount</div>
                <div class="fs-3 fw-bold text-success">₹{{ number_format($summary['amount'], 2) }}</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 px-4 pt-4">
            <h5 class="fw-bold mb-1">Payments by Branch and Method</h5>
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
                            <th>Payment Method</th>
                            <th class="text-end">Orders</th>
                            <th class="text-end pe-4">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $payment->branch_name }}</td>
                                <td>{{ \Illuminate\Support\Str::upper($payment->payment_method) }}</td>
                                <td class="text-end">{{ number_format($payment->order_count) }}</td>
                                <td class="text-end pe-4 fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No recorded payments for the selected period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($payments->isNotEmpty())
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td class="ps-4" colspan="2">Total</td>
                                <td class="text-end">{{ number_format($summary['order_count']) }}</td>
                                <td class="text-end pe-4">₹{{ number_format($summary['amount'], 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
