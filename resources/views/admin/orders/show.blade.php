@extends('layouts.admin')

@section('title', 'Order Details')

@section('page-title', 'Order Details')

@section('content')

<div class="row g-4">


    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between mb-4">

                    <div>

                        <h4 class="fw-bold">

                            {{ $order->order_number }}

                        </h4>

                        <div class="text-muted">

                            {{ $order->created_at->format(
                                'd M Y h:i A'
                            ) }}

                        </div>

                    </div>


                    <span
                        class="badge text-bg-primary
                               align-self-start">

                        {{ ucfirst($order->status) }}

                    </span>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>Item</th>

                                <th>Price</th>

                                <th>Qty</th>

                                <th class="text-end">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $order->items
                                as $item
                            )

                                <tr>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->item_name }}

                                        </div>

                                        @if($item->size)

                                            <small class="text-muted">

                                                {{ $item->size }}

                                            </small>

                                        @endif

                                    </td>

                                    <td>
                                        ₹{{ number_format(
                                            $item->unit_price,
                                            2
                                        ) }}
                                    </td>

                                    <td>
                                        {{ $item->quantity }}
                                    </td>

                                    <td class="text-end fw-semibold">

                                        ₹{{ number_format(
                                            $item->total,
                                            2
                                        ) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="border-top pt-3">

                    <div class="d-flex justify-content-between">

                        <span>Subtotal</span>

                        <strong>
                            ₹{{ number_format(
                                $order->subtotal,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span>Discount</span>

                        <strong>
                            ₹{{ number_format(
                                $order->discount,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span>Tax</span>

                        <strong>
                            ₹{{ number_format(
                                $order->tax,
                                2
                            ) }}
                        </strong>

                    </div>


                    <div
                        class="d-flex justify-content-between
                               fs-5 fw-bold mt-2">

                        <span>
                            Grand Total
                        </span>

                        <span class="text-danger">

                            ₹{{ number_format(
                                $order->grand_total,
                                2
                            ) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-xl-4">


        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">
                    Order Information
                </h5>


                <div class="mb-3">

                    <small class="text-muted">
                        Customer
                    </small>

                    <div class="fw-semibold">

                        {{ $order->customer?->name
                            ?? 'Walk-in Customer' }}

                    </div>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Table
                    </small>

                    <div class="fw-semibold">

                        {{ $order->table?->table_number
                            ?? '-' }}

                    </div>

                </div>


                <div class="mb-3">

                    <small class="text-muted">
                        Order Type
                    </small>

                    <div class="fw-semibold">

                        {{ ucfirst(
                            str_replace(
                                '_',
                                ' ',
                                $order->order_type
                            )
                        ) }}

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{
                        route(
                            'admin.orders.status',
                            $order
                        )
                    }}">

                    @csrf
                    @method('PUT')


                    <label class="form-label fw-semibold">

                        Change Status

                    </label>


                    <select
                        name="status"
                        class="form-select mb-3">

                        @foreach([
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
                                    $order->status === $status
                                )>

                                {{ ucfirst($status) }}

                            </option>

                        @endforeach

                    </select>


                    <button
                        class="btn btn-dark w-100">

                        Update Status

                    </button>

                </form>

            </div>

        </div>


        {{-- KOT --}}

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between">

                    <h5 class="fw-bold">
                        Kitchen Orders
                    </h5>

                    <a
                        href="{{
                            route(
                                'admin.kots.create',
                                $order
                            )
                        }}"
                        class="btn btn-sm btn-danger">

                        <i class="bi bi-plus"></i>

                        Create KOT

                    </a>

                </div>


                <hr>


                @forelse(
                    $order->kots
                    as $kot
                )

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-3">

                        <div>

                            <a
                                href="{{
                                    route(
                                        'admin.kots.show',
                                        $kot
                                    )
                                }}"
                                class="fw-bold
                                       text-decoration-none">

                                {{ $kot->kot_number }}

                            </a>

                            <div>

                                <small class="text-muted">

                                    {{ $kot->created_at->format(
                                        'h:i A'
                                    ) }}

                                </small>

                            </div>

                        </div>


                        <span
                            class="badge text-bg-secondary">

                            {{ ucfirst(
                                $kot->status
                            ) }}

                        </span>

                    </div>

                @empty

                    <p class="text-muted mb-0">

                        No KOT generated.

                    </p>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection