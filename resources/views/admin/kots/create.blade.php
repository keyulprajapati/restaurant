@extends('layouts.admin')

@section('title', 'Create KOT')

@section('page-title', 'Create Kitchen Order')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <div class="mb-4">

                    <h4 class="fw-bold">

                        Create KOT

                    </h4>

                    <p class="text-muted">

                        Order:
                        {{ $order->order_number }}

                        @if($order->table)

                            | Table:
                            {{ $order->table->table_number }}

                        @endif

                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('admin.kots.store') }}">

                    @csrf


                    <input
                        type="hidden"
                        name="order_id"
                        value="{{ $order->id }}">


                    @foreach(
                        $order->items
                        as $item
                    )

                        <div
                            class="border rounded-4 p-3 mb-3">

                            <div class="row align-items-center">


                                <div class="col-md-6">

                                    <div class="fw-bold">

                                        {{ $item->item_name }}

                                    </div>

                                    @if($item->size)

                                        <small class="text-muted">

                                            {{ $item->size }}

                                        </small>

                                    @endif

                                </div>


                                <div class="col-md-3">

                                    <label class="form-label">

                                        Quantity

                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $item->id }}][quantity]"
                                        value="{{ $item->quantity }}"
                                        min="0"
                                        max="{{ $item->quantity }}"
                                        step="0.01"
                                        class="form-control">

                                    <input
                                        type="hidden"
                                        name="items[{{ $item->id }}][order_item_id]"
                                        value="{{ $item->id }}">

                                </div>


                                <div class="col-md-3">

                                    <label class="form-label">

                                        Note

                                    </label>

                                    <input
                                        type="text"
                                        name="items[{{ $item->id }}][notes]"
                                        class="form-control"
                                        placeholder="Less spicy...">

                                </div>

                            </div>

                        </div>

                    @endforeach


                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Kitchen Notes

                        </label>

                        <textarea
                            name="notes"
                            class="form-control"
                            rows="3"
                            placeholder="Kitchen instructions..."></textarea>

                    </div>


                    <div class="d-flex justify-content-end gap-2">

                        <a
                            href="{{
                                route(
                                    'admin.orders.show',
                                    $order
                                )
                            }}"
                            class="btn btn-light">

                            Cancel

                        </a>


                        <button
                            class="btn btn-danger">

                            <i class="bi bi-fire me-2"></i>

                            Send to Kitchen

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection