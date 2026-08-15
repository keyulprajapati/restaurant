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

            <a
                href="{{ route('admin.pos.index') }}"
                class="btn btn-danger">

                <i class="bi bi-plus-lg me-2"></i>

                New Order

            </a>

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
                        Dine In
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


            <div class="col-md-2">

                <button class="btn btn-dark w-100">

                    Filter

                </button>

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
                                    $order->grand_total,
                                    2
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

@endsection