@extends('layouts.admin')

@section('title', 'Orders')

@section('page-title', 'Order Management')

@section('content')

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <div class="d-flex justify-content-between
                    align-items-center mb-4">

            <div>

                <h4 class="fw-bold mb-1">
                    Orders
                </h4>

                <p class="text-muted mb-0">
                    Manage restaurant orders and KOT.
                </p>

            </div>

            <div class="d-flex align-items-center gap-3">

                <span class="badge text-bg-light border rounded-pill px-3 py-2">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    Auto-refresh every 15s
                </span>

                <a
                    href="{{ route('admin.pos.index') }}"
                    class="btn btn-danger">

                    <i class="bi bi-plus-lg me-2"></i>

                    New Order

                </a>

            </div>

        </div>


        {{-- Filters --}}

        <form
            method="GET"
            class="row g-3 mb-4">

            <div class="col-md-3">

                <select
                    name="status"
                    class="form-select">

                    <option value="">
                        All Status
                    </option>

                    @foreach([
                        'draft',
                        'placed',
                        'preparing',
                        'ready',
                        'served',
                        'completed',
                        'cancelled'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(
                                request('status') === $status
                            )>

                            {{ ucfirst($status) }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-3">

                <select
                    name="order_type"
                    class="form-select">

                    <option value="">
                        All Types
                    </option>

                    <option
                        value="dine_in"
                        @selected(
                            request('order_type') === 'dine_in'
                        )>
                        Dine In (Table)
                    </option>

                    <option
                        value="takeaway"
                        @selected(
                            request('order_type') === 'takeaway'
                        )>
                        Takeaway
                    </option>

                    <option
                        value="delivery"
                        @selected(
                            request('order_type') === 'delivery'
                        )>
                        Delivery
                    </option>

                </select>

            </div>


            <div class="col-md-3">

                <select
                    name="restaurant_table_id"
                    class="form-select">

                    <option value="">
                        All Tables
                    </option>

                    @foreach($tables as $tbl)

                        <option
                            value="{{ $tbl->id }}"
                            @selected(
                                request('restaurant_table_id') == $tbl->id
                            )>
                            Table {{ $tbl->table_number }} {{ $tbl->area ? '('.$tbl->area.')' : '' }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-md-3 d-flex gap-2">

                <button class="btn btn-dark flex-fill">
                    <i class="bi bi-search me-1"></i> Filter
                </button>

                <a href="{{ route('admin.orders.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>

            </div>

        </form>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>Order</th>

                        <th>Customer</th>

                        <th>Table</th>

                        <th>Type</th>

                        <th>Amount</th>

                        <th>Status</th>

                        <th>KOT</th>

                        <th></th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($orders as $order)

                        <tr>

                            <td>

                                <div class="fw-bold">

                                    {{ $order->order_number }}

                                </div>

                                <small class="text-muted">

                                    {{ $order->created_at->format(
                                        'd M Y h:i A'
                                    ) }}

                                </small>

                            </td>


                            <td>

                                {{ $order->customer?->name
                                    ?? 'Walk-in Customer' }}

                            </td>


                            <td>

                                {{ $order->table?->table_number
                                    ?? '-' }}

                            </td>


                            <td>

                                {{ ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $order->order_type
                                    )
                                ) }}

                            </td>


                            <td class="fw-bold">

                                ₹{{ number_format(
                                    (float) $order->grand_total,
                                    0
                                ) }}

                            </td>


                            <td>

                                @php

                                    $badge = match(
                                        $order->status
                                    ) {

                                        'completed' =>
                                            'success',

                                        'preparing' =>
                                            'warning',

                                        'ready' =>
                                            'info',

                                        'cancelled' =>
                                            'danger',

                                        default =>
                                            'secondary'

                                    };

                                @endphp

                                <span
                                    class="badge text-bg-{{ $badge }}">

                                    {{ ucfirst(
                                        $order->status
                                    ) }}

                                </span>

                            </td>


                            <td>

                                @forelse(
                                    $order->kots
                                    as $kot
                                )

                                    <a
                                        href="{{
                                            route(
                                                'admin.kots.show',
                                                $kot
                                            )
                                        }}"
                                        class="badge
                                               text-bg-dark
                                               text-decoration-none">

                                        {{ $kot->kot_number }}

                                    </a>

                                @empty

                                    <span
                                        class="text-muted">

                                        No KOT

                                    </span>

                                @endforelse

                            </td>


                            <td>

                                <a
                                    href="{{
                                        route(
                                            'admin.orders.show',
                                            $order
                                        )
                                    }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-5 text-muted">

                                No orders found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{ $orders->links() }}

    </div>

</div>

@push('scripts')
    <script>
        const ORDER_REFRESH_INTERVAL_MS = 15000;

        const orderRefreshTimer = setInterval(() => {
            if (document.visibilityState === 'visible') {
                window.location.reload();
            }
        }, ORDER_REFRESH_INTERVAL_MS);

        window.addEventListener('beforeunload', () => {
            clearInterval(orderRefreshTimer);
        });
    </script>
@endpush

@endsection