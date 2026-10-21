@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }}, {{ auth()->user()->name }}
        </h3>
        <p class="text-muted mb-0">
            {{ now()->format('l, d F Y') }}
            @if($selectedBranchName)
                <span class="mx-1">/</span>{{ $selectedBranchName }}
            @else
                <span class="mx-1">/</span>All accessible branches
            @endif
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        @if($branches->count() > 1)
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <select name="branch_id" class="form-select" aria-label="Filter dashboard by branch" onchange="this.form.submit()">
                    <option value="">All accessible branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected((string) $selectedBranchId === (string) $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </form>
        @endif
        @can('orders.create')
            <a href="{{ route('admin.pos.index') }}" class="btn btn-danger px-3">
                <i class="bi bi-plus-lg me-1"></i> New order
            </a>
        @endcan
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-2">Today's orders</div>
                    <h3 class="fw-bold mb-2">{{ number_format($todayOrders) }}</h3>
                    @if($ordersChange !== null)
                        <small class="{{ $ordersChange >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi {{ $ordersChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                            {{ number_format(abs($ordersChange), 1) }}%
                        </small>
                    @else
                        <small class="text-muted">New activity</small>
                    @endif
                    <span class="text-muted small">vs yesterday</span>
                </div>
                <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-2">Today's sales</div>
                    <h3 class="fw-bold mb-2">Rs. {{ number_format((float) $todaySales, 2) }}</h3>
                    @if($salesChange !== null)
                        <small class="{{ $salesChange >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="bi {{ $salesChange >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                            {{ number_format(abs($salesChange), 1) }}%
                        </small>
                    @else
                        <small class="text-muted">New sales</small>
                    @endif
                    <span class="text-muted small">vs yesterday</span>
                </div>
                <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-currency-rupee"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-2">Customers this month</div>
                    <h3 class="fw-bold mb-2">{{ number_format((int) $monthlyCustomers) }}</h3>
                    <small class="text-muted">Distinct customers with orders</small>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-2">Available tables</div>
                    <h3 class="fw-bold mb-2">{{ $availableTables }} <span class="fs-6 text-muted fw-normal">/ {{ $activeTables }}</span></h3>
                    <small class="text-muted">{{ $occupiedTables }} currently occupied</small>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-grid-3x3-gap"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Sales, last 7 days</h5>
                        <p class="text-muted small mb-0">Order totals excluding cancelled orders</p>
                    </div>
                    @can('orders.view')
                        <a href="{{ route('admin.orders.index', array_filter(['branch_id' => $selectedBranchId])) }}" class="btn btn-sm btn-light">View orders</a>
                    @endcan
                </div>

                <div class="d-flex align-items-end gap-2" style="height: 190px;">
                    @foreach($weeklySales as $day)
                        @php($barHeight = $maxDailySales > 0 ? max(4, ($day['total'] / $maxDailySales) * 100) : 4)
                        <div class="d-flex flex-column align-items-center justify-content-end h-100 flex-fill" title="{{ $day['date'] }}: Rs. {{ number_format($day['total'], 2) }}">
                            <small class="text-muted text-truncate w-100 text-center mb-1">{{ $day['total'] > 0 ? number_format($day['total'], 0) : '' }}</small>
                            <div class="w-100 rounded-top bg-success" style="height: {{ $barHeight }}%; min-height: 4px; opacity: .85;"></div>
                            <small class="text-muted mt-2">{{ $day['label'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Recent orders</h5>
                        <p class="text-muted small mb-0">Latest activity across accessible branches</p>
                    </div>
                    @can('orders.view')
                        <a href="{{ route('admin.orders.index', array_filter(['branch_id' => $selectedBranchId])) }}" class="btn btn-sm btn-light">View all</a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Order</th>
                                @if(!$selectedBranchId && $branches->count() > 1)<th>Branch</th>@endif
                                <th>Customer</th>
                                <th>Table</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                @php($statusClass = match($order->status) { 'completed', 'served' => 'success', 'preparing' => 'warning', 'ready' => 'info', 'cancelled' => 'danger', default => 'secondary' })
                                <tr>
                                    <td>
                                        @can('orders.view')
                                            <a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold text-decoration-none">{{ $order->order_number }}</a>
                                        @else
                                            <span class="fw-semibold">{{ $order->order_number }}</span>
                                        @endcan
                                        <small class="text-muted d-block">{{ $order->created_at->format('d M, h:i A') }}</small>
                                    </td>
                                    @if(!$selectedBranchId && $branches->count() > 1)<td>{{ $order->branch?->name ?? '-' }}</td>@endif
                                    <td>{{ $order->customer?->name ?? 'Walk-in' }}</td>
                                    <td>{{ $order->table?->table_number ?? ucfirst(str_replace('_', ' ', $order->order_type)) }}</td>
                                    <td class="fw-semibold">Rs. {{ number_format((float) $order->grand_total, 2) }}</td>
                                    <td><span class="badge text-bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No orders found for this branch and date range.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="col-xl-4">
        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-bold mb-1">Kitchen queue</h5>
                        <p class="text-muted small mb-0">Pending and preparing KOTs</p>
                    </div>
                    <span class="badge text-bg-warning fs-6">{{ $openKots }}</span>
                </div>
                @can('kots.view')
                    <a href="{{ route('admin.kots.index', array_filter(['branch_id' => $selectedBranchId])) }}" class="btn btn-light w-100 mt-3">Open kitchen queue</a>
                @endcan
            </div>
        </section>

        <section class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-1">Upcoming reservations</h5>
                        <p class="text-muted small mb-0">Pending or confirmed</p>
                    </div>
                    @can('reservations.view')
                        <a href="{{ route('admin.reservations.index', array_filter(['branch_id' => $selectedBranchId])) }}" class="btn btn-sm btn-light">All</a>
                    @endcan
                </div>

                @forelse($upcomingReservations as $reservation)
                    <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                        <div class="min-w-0">
                            <div class="fw-semibold text-truncate">{{ $reservation->customer?->name ?? 'Customer' }}</div>
                            <small class="text-muted">{{ $reservation->branch?->name ?? 'Unassigned' }} · {{ $reservation->table?->table_number ?? 'Table unassigned' }}</small>
                        </div>
                        <div class="text-end text-nowrap">
                            <div>{{ $reservation->reservation_date->format('d M') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No upcoming reservations.</p>
                @endforelse

                @can('reservations.create')
                    <a href="{{ route('admin.reservations.create', array_filter(['branch_id' => $selectedBranchId])) }}" class="btn btn-outline-secondary w-100 mt-3">
                        <i class="bi bi-calendar-plus me-1"></i> New reservation
                    </a>
                @endcan
            </div>
        </section>

        <section class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Quick actions</h5>
                @can('orders.create')
                    <a href="{{ route('admin.pos.index', array_filter(['branch_id' => $selectedBranchId])) }}" class="btn btn-danger w-100 mb-2"><i class="bi bi-plus-circle me-2"></i>Create order</a>
                @endcan
                @can('tables.view')
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-light w-100"><i class="bi bi-grid me-2"></i>Manage tables</a>
                @endcan
            </div>
        </section>
    </div>
</div>
@endsection