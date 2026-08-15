@extends('layouts.admin')

@section('title', 'Kitchen Orders')

@section('page-title', 'Kitchen Orders')

@section('content')

<div class="row g-4">

    @forelse($kots as $kot)

        <div class="col-xl-4 col-lg-6">

            <div
                class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 p-4">

                    <div
                        class="d-flex justify-content-between">

                        <div>

                            <h5 class="fw-bold mb-1">

                                {{ $kot->kot_number }}

                            </h5>

                            <small class="text-muted">

                                {{ $kot->created_at->format(
                                    'h:i A'
                                ) }}

                            </small>

                        </div>


                        <span
                            class="badge
                            @if($kot->status === 'pending')
                                text-bg-danger
                            @elseif($kot->status === 'preparing')
                                text-bg-warning
                            @elseif($kot->status === 'ready')
                                text-bg-success
                            @else
                                text-bg-secondary
                            @endif">

                            {{ strtoupper(
                                $kot->status
                            ) }}

                        </span>

                    </div>

                </div>


                <div class="card-body px-4">

                    <div class="mb-3">

                        <strong>

                            Table:

                            {{ $kot->table?->table_number
                                ?? 'Takeaway' }}

                        </strong>

                        <br>

                        <small class="text-muted">

                            Order:
                            {{ $kot->order->order_number }}

                        </small>

                    </div>


                    @foreach(
                        $kot->items
                        as $item
                    )

                        <div
                            class="d-flex
                                   justify-content-between
                                   border-bottom
                                   py-2">

                            <div>

                                <strong>

                                    {{ $item->item_name }}

                                </strong>

                                @if($item->size)

                                    <small
                                        class="text-muted d-block">

                                        {{ $item->size }}

                                    </small>

                                @endif

                                @if($item->notes)

                                    <small
                                        class="text-danger d-block">

                                        {{ $item->notes }}

                                    </small>

                                @endif

                            </div>


                            <div
                                class="fw-bold fs-5">

                                × {{ $item->quantity }}

                            </div>

                        </div>

                    @endforeach


                    @if($kot->notes)

                        <div
                            class="alert alert-warning mt-3 mb-0">

                            <strong>
                                Note:
                            </strong>

                            {{ $kot->notes }}

                        </div>

                    @endif

                </div>


                <div class="card-footer bg-white border-0 p-4">

                    <div class="d-flex gap-2">


                        @if(
                            $kot->status === 'pending'
                        )

                            <form
                                method="POST"
                                class="flex-grow-1"
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
                                    class="btn btn-warning w-100">

                                    Start

                                </button>

                            </form>

                        @elseif(
                            $kot->status === 'preparing'
                        )

                            <form
                                method="POST"
                                class="flex-grow-1"
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
                                    class="btn btn-success w-100">

                                    Ready

                                </button>

                            </form>

                        @elseif(
                            $kot->status === 'ready'
                        )

                            <form
                                method="POST"
                                class="flex-grow-1"
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
                                    class="btn btn-primary w-100">

                                    Served

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

                            <i class="bi bi-printer"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">

            <div
                class="card border-0 shadow-sm rounded-4">

                <div
                    class="card-body text-center py-5">

                    <i
                        class="bi bi-fire fs-1 text-muted">
                    </i>

                    <h5 class="mt-3">
                        No Kitchen Orders
                    </h5>

                    <p class="text-muted">
                        New KOTs will appear here.
                    </p>

                </div>

            </div>

        </div>

    @endforelse

</div>


<div class="mt-4">

    {{ $kots->links() }}

</div>

@endsection