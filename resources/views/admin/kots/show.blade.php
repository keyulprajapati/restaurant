@extends('layouts.admin')

@section('title', 'KOT Details')

@section('page-title', 'Kitchen Order Ticket')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">


                <div
                    class="d-flex justify-content-between
                           align-items-start mb-4">

                    <div>

                        <h3 class="fw-bold mb-1">

                            {{ $kot->kot_number }}

                        </h3>

                        <div class="text-muted">

                            {{ $kot->created_at->format(
                                'd M Y h:i A'
                            ) }}

                        </div>

                    </div>


                    <span
                        class="badge text-bg-warning">

                        {{ strtoupper(
                            $kot->status
                        ) }}

                    </span>

                </div>


                <div class="row mb-4">

                    <div class="col-md-4">

                        <small class="text-muted">
                            Order
                        </small>

                        <div class="fw-bold">

                            {{ $kot->order->order_number }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Table
                        </small>

                        <div class="fw-bold">

                            {{ $kot->table?->table_number
                                ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <small class="text-muted">
                            Customer
                        </small>

                        <div class="fw-bold">

                            {{ $kot->order->customer?->name
                                ?? 'Walk-in' }}

                        </div>

                    </div>

                </div>


                <div class="table-responsive">

                    <table class="table">

                        <thead>

                            <tr>

                                <th>Item</th>

                                <th>Qty</th>

                                <th>Note</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $kot->items
                                as $item
                            )

                                <tr>

                                    <td>

                                        <strong>

                                            {{ $item->item_name }}

                                        </strong>

                                        @if($item->size)

                                            <small
                                                class="text-muted d-block">

                                                {{ $item->size }}

                                            </small>

                                        @endif

                                    </td>


                                    <td class="fw-bold">

                                        {{ $item->quantity }}

                                    </td>


                                    <td>

                                        {{ $item->notes ?? '-' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                @if($kot->notes)

                    <div
                        class="alert alert-warning">

                        <strong>
                            Kitchen Notes:
                        </strong>

                        {{ $kot->notes }}

                    </div>

                @endif


                <div
                    class="d-flex gap-2 flex-wrap mt-4">

                    @if(
                        $kot->status === 'pending'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.kots.status',
                                    $kot
                                )
                            }}">

                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="preparing">

                            <button
                                class="btn btn-warning">

                                Start Preparing

                            </button>

                        </form>

                    @endif


                    @if(
                        $kot->status === 'preparing'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.kots.status',
                                    $kot
                                )
                            }}">

                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="ready">

                            <button
                                class="btn btn-success">

                                Mark Ready

                            </button>

                        </form>

                    @endif


                    @if(
                        $kot->status === 'ready'
                    )

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.kots.status',
                                    $kot
                                )
                            }}">

                            @csrf
                            @method('PUT')

                            <input
                                type="hidden"
                                name="status"
                                value="served">

                            <button
                                class="btn btn-primary">

                                Mark Served

                            </button>

                        </form>

                    @endif


                    <a
                        href="{{
                            route(
                                'admin.kots.print',
                                $kot
                            )
                        }}"
                        target="_blank"
                        class="btn btn-dark">

                        <i class="bi bi-printer me-2"></i>

                        Print KOT

                    </a>


                    <a
                        href="{{
                            route(
                                'admin.orders.show',
                                $kot->order
                            )
                        }}"
                        class="btn btn-light">

                        Back to Order

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection