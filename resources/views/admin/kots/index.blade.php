@extends('layouts.admin')

@section('title', 'Kitchen Orders')

@section('page-title', 'Kitchen Orders')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="fw-bold mb-1">
            Kitchen Orders
        </h4>

        <p class="text-muted mb-0">
            Live kitchen queue updates automatically.
        </p>

    </div>

    <span class="badge text-bg-light border rounded-pill px-3 py-2">
        <i class="bi bi-arrow-repeat me-1"></i>
        Auto-refresh every 15s
    </span>

</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <select name="branch_id" class="form-select">
            <option value="">All Accessible Branches</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
            @foreach(['pending', 'preparing', 'ready', 'served', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-dark w-100"><i class="bi bi-search me-1"></i> Filter</button>
    </div>
</form>

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

                            <small class="d-block text-muted">{{ $kot->order->branch?->name ?? 'Unassigned branch' }}</small>

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


                    @php
                        $comboItemLines = $kot->items->filter(fn ($item) => !empty($item->notes) && str_starts_with($item->notes, 'Combo: '));
                        $mainItemLines = $kot->items->filter(fn ($item) => empty($item->notes) || !str_starts_with($item->notes, 'Combo: '));
                    @endphp

                    @foreach($mainItemLines as $item)

                        <div
                            class="d-flex
                                   justify-content-between
                                   border-bottom
                                   py-2">

                            <div>

                                <strong>

                                    {{ $item->item_name }}

                                </strong>

                                @if($item->size || $item->unit)

                                    <small
                                        class="text-muted d-block">

                                        {{ trim(($item->size ?? '') . ' ' . ($item->unit ?? '')) }}

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

                    @if($comboItemLines->isNotEmpty())
                        <div class="mt-3 mb-2 rounded-3 border border-warning-subtle bg-warning-subtle p-2" style="border-left: 4px solid #f59e0b;">
                            <small class="text-uppercase text-warning-emphasis fw-bold" style="letter-spacing: 0.08em;">
                                Combo items
                            </small>
                        </div>

                        @foreach($comboItemLines as $item)
                            <div class="d-flex justify-content-between border-bottom py-2" style="background: rgba(245, 158, 11, 0.03);">
                                <div>
                                    <strong>{{ $item->item_name }}</strong>

                                    @if($item->size || $item->unit)
                                        <small class="text-muted d-block">{{ trim(($item->size ?? '') . ' ' . ($item->unit ?? '')) }}</small>
                                    @endif
                                </div>

                                <div class="fw-bold fs-5">× {{ $item->quantity }}</div>
                            </div>
                        @endforeach
                    @endif


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

@push('scripts')
    <script>
        const KOT_REFRESH_INTERVAL_MS = 15000;

        const kotRefreshTimer = setInterval(() => {
            if (document.visibilityState === 'visible') {
                window.location.reload();
            }
        }, KOT_REFRESH_INTERVAL_MS);

        window.addEventListener('beforeunload', () => {
            clearInterval(kotRefreshTimer);
        });
    </script>
@endpush

@endsection