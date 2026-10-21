@extends('layouts.admin')

@section('title', 'Table Management')
@section('page-title', 'Table Management')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Table Management</h3>
        <p class="text-muted mb-0">Manage restaurant dining tables, seating, and live QR code ordering.</p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.tables.print-all-qr') }}" class="btn btn-outline-dark" target="_blank">
            <i class="bi bi-printer me-1"></i> Print All QR Cards
        </a>

        @can('tables.create')
            <a href="{{ route('admin.tables.extra') }}" class="btn btn-outline-danger">
                <i class="bi bi-grid-plus me-1"></i> Add Extra Tables
            </a>

            <a href="{{ route('admin.tables.create') }}" class="btn btn-danger">
                <i class="bi bi-plus-lg me-1"></i> Add Single Table
            </a>
        @endcan
    </div>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Summary Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Available Tables</small>
                        <h3 class="fw-bold mb-0 text-success">
                            {{ \App\Models\RestaurantTable::where('status', 'available')->where('is_active', true)->count() }}
                        </h3>
                    </div>
                    <div class="bg-success-subtle text-success rounded-3 p-3">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Occupied Tables</small>
                        <h3 class="fw-bold mb-0 text-danger">
                            {{ \App\Models\RestaurantTable::where('status', 'occupied')->where('is_active', true)->count() }}
                        </h3>
                    </div>
                    <div class="bg-danger-subtle text-danger rounded-3 p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">Reserved Tables</small>
                        <h3 class="fw-bold mb-0 text-warning-emphasis">
                            {{ \App\Models\RestaurantTable::where('status', 'reserved')->where('is_active', true)->count() }}
                        </h3>
                    </div>
                    <div class="bg-warning-subtle text-warning rounded-3 p-3">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Search table number, name or area">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Live Status</option>
                        <option value="available" @selected(request('status') === 'available')>Available</option>
                        <option value="occupied" @selected(request('status') === 'occupied')>Occupied</option>
                        <option value="reserved" @selected(request('status') === 'reserved')>Reserved</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="area" class="form-select">
                        <option value="">All Areas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-dark flex-fill" title="Filter">
                        <i class="bi bi-search"></i>
                    </button>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-light" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table List --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>Capacity</th>
                        <th>Area</th>
                        <th>Type</th>
                        <th>Live Status</th>
                        <th>Config</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($tables as $table)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 bg-danger-subtle text-danger p-2 me-3 fs-5">
                                    <i class="bi bi-table"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-6">
                                        {{ $table->table_number }}
                                        <small class="text-muted fw-normal ms-1">(ID: #{{ $table->id }})</small>
                                    </div>
                                    @if($table->name)
                                        <small class="text-muted d-block">{{ $table->name }}</small>
                                    @endif

                                    @if($table->activeOrder)
                                        <a href="{{ route('admin.orders.show', $table->activeOrder) }}"
                                           class="badge bg-warning-subtle text-warning-emphasis text-decoration-none mt-1">
                                            <i class="bi bi-receipt me-1"></i>Active: {{ $table->activeOrder->order_number }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            <i class="bi bi-people me-1 text-muted"></i>
                            <strong>{{ $table->capacity }}</strong> seats
                        </td>

                        <td>
                            <span class="badge bg-light text-dark border">
                                <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $table->area ?: 'General' }}
                            </span>
                        </td>

                        <td>
                            {{ $table->type_label }}
                        </td>

                        <td>
                            @switch($table->status)
                                @case('available')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-check-circle me-1"></i>Available
                                    </span>
                                    @break
                                @case('occupied')
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="bi bi-person-fill me-1"></i>Occupied
                                    </span>
                                    @break
                                @case('reserved')
                                    <span class="badge bg-warning-subtle text-warning-emphasis">
                                        <i class="bi bi-calendar-check me-1"></i>Reserved
                                    </span>
                                    @break
                            @endswitch
                        </td>

                        <td>
                            @if($table->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                {{-- QR Code Modal Trigger --}}
                                <button type="button"
                                        class="btn btn-sm btn-outline-dark"
                                        onclick="openQrModal('{{ $table->id }}', '{{ $table->table_number }}', '{{ $table->area ?: 'Dining Area' }}', '{{ $table->url }}')"
                                        title="View Table QR Code">
                                    <i class="bi bi-qr-code me-1"></i> QR
                                </button>

                                {{-- Direct Print QR --}}
                                <a href="{{ route('admin.tables.print-qr', $table) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-light"
                                   title="Print Table Tent Card">
                                    <i class="bi bi-printer"></i>
                                </a>

                                @can('tables.edit')
                                    <a href="{{ route('admin.tables.edit', $table) }}"
                                       class="btn btn-sm btn-light"
                                       title="Edit Table">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan

                                @can('tables.delete')
                                    <form action="{{ route('admin.tables.destroy', $table) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Delete Table {{ $table->table_number }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-light text-danger" title="Delete Table">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-table fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-3">No restaurant tables found.</p>
                            @can('tables.create')
                                <a href="{{ route('admin.tables.extra') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-grid-plus me-1"></i> Add Tables in Bulk
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $tables->links() }}
        </div>
    </div>
</div>

<style>
/* Modal Stand Card Preview */
.modal-stand-card {
    width: 326px;
    background-color: #F6F0E7;
    border-radius: 24px;
    padding: 24px 18px 20px 18px;
    position: relative;
    overflow: hidden;
    margin: 0 auto;
    box-shadow: 0 10px 28px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.06);
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
}
.modal-stand-watermark {
    position: absolute;
    top: 175px;
    left: 50%;
    transform: translateX(-50%);
    width: 280px;
    opacity: 0.18;
    pointer-events: none;
    z-index: 1;
    filter: contrast(1.1);
}
.modal-card-inner {
    position: relative;
    z-index: 2;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.modal-badge-rosette {
    width: 132px;
    height: 132px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}
.modal-rosette-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.modal-badge-content {
    position: relative;
    z-index: 3;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: -2px;
    text-align: center;
}
.modal-badge-label {
    font-size: 16px;
    font-weight: 600;
    color: #364728;
    letter-spacing: -0.2px;
    line-height: 1.1;
    margin-bottom: 1px;
}
.modal-badge-num {
    font-size: 54px;
    font-weight: 900;
    color: #1E2D16;
    line-height: 0.95;
    letter-spacing: -1.5px;
}
.modal-badge-num.text-scale-md {
    font-size: 40px;
    letter-spacing: -1px;
}
.modal-badge-num.text-scale-sm {
    font-size: 28px;
    letter-spacing: normal;
}
.modal-menu-heading {
    font-size: 23px;
    font-weight: 800;
    color: #48473F;
    margin-bottom: 12px;
    letter-spacing: -0.3px;
    text-align: center;
    line-height: 1.1;
}
.modal-scanner-frame {
    width: 220px;
    height: 220px;
    border: 2px solid #565750;
    border-radius: 28px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    margin-bottom: 14px;
}
.modal-corner-bracket {
    position: absolute;
    width: 20px;
    height: 20px;
    border-color: #565750;
    border-style: solid;
}
.modal-bracket-tl {
    top: 10px;
    left: 10px;
    border-width: 2px 0 0 2px;
}
.modal-bracket-tr {
    top: 10px;
    right: 10px;
    border-width: 2px 2px 0 0;
}
.modal-bracket-bl {
    bottom: 10px;
    left: 10px;
    border-width: 0 0 2px 2px;
}
.modal-bracket-br {
    bottom: 10px;
    right: 10px;
    border-width: 0 2px 2px 0;
}
.modal-qr-white {
    background-color: #ffffff;
    width: 178px;
    height: 178px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    padding: 7px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
.modal-qr-white svg {
    width: 100% !important;
    height: 100% !important;
    max-width: 100%;
    display: block;
}
.modal-utensils {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 4px;
}
.modal-order-here {
    font-size: 20px;
    font-weight: 800;
    color: #48473F;
    letter-spacing: -0.2px;
    text-align: center;
    line-height: 1.1;
}
</style>

{{-- Dynamic QR Code Modal --}}
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="qrModalLabel">
                    <i class="bi bi-qr-code text-danger me-2"></i>Table QR Code Scanner
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-3 px-4">
                <div class="text-muted small mb-3" id="modalTableArea">Ground Floor</div>

                {{-- Authentic Stand Card Scanner Preview --}}
                <div class="modal-stand-card mb-3">
                    @if(file_exists(public_path('watermark_logo.png')))
                        <img class="modal-stand-watermark" src="{{ asset('watermark_logo.png') }}" alt="Watermark">
                    @elseif(setting('brand_logo') && file_exists(public_path(setting('brand_logo'))))
                        <img class="modal-stand-watermark" src="{{ asset(setting('brand_logo')) }}" alt="Watermark">
                    @endif

                    <div class="modal-card-inner">
                        <!-- 1. Scalloped Rosette Badge -->
                        <div class="modal-badge-rosette">
                            <svg class="modal-rosette-svg" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M 100 14.5 L 102.09 15.02 L 104.1 16.5 L 105.99 18.73 L 107.74 21.38 L 109.37 24.06 L 110.92 26.4 L 112.48 28.08 L 114.14 28.89 L 115.99 28.78 L 118.08 27.83 L 120.41 26.26 L 122.93 24.4 L 125.56 22.63 L 128.16 21.29 L 130.59 20.69 L 132.72 21.01 L 134.45 22.29 L 135.74 24.43 L 136.64 27.21 L 137.24 30.33 L 137.71 33.43 L 138.25 36.18 L 139.05 38.33 L 140.28 39.72 L 142.03 40.32 L 144.32 40.24 L 147.07 39.68 L 150.12 38.93 L 153.23 38.3 L 156.14 38.06 L 158.61 38.44 L 160.46 39.54 L 161.56 41.39 L 161.94 43.86 L 161.7 46.77 L 161.07 49.88 L 160.32 52.93 L 159.76 55.68 L 159.68 57.97 L 160.28 59.72 L 161.67 60.95 L 163.82 61.75 L 166.57 62.29 L 169.67 62.76 L 172.79 63.36 L 175.57 64.26 L 177.71 65.55 L 178.99 67.28 L 179.31 69.41 L 178.71 71.84 L 177.37 74.44 L 175.6 77.07 L 173.74 79.59 L 172.17 81.92 L 171.22 84.01 L 171.11 85.86 L 171.92 87.52 L 173.6 89.08 L 175.94 90.63 L 178.62 92.26 L 181.27 94.01 L 183.5 95.9 L 184.98 97.91 L 185.5 100 L 184.98 102.09 L 183.5 104.1 L 181.27 105.99 L 178.62 107.74 L 175.94 109.37 L 173.6 110.92 L 171.92 112.48 L 171.11 114.14 L 171.22 115.99 L 172.17 118.08 L 173.74 120.41 L 175.6 122.93 L 177.37 125.56 L 178.71 128.16 L 179.31 130.59 L 178.99 132.72 L 177.71 134.45 L 175.57 135.74 L 172.79 136.64 L 169.67 137.24 L 166.57 137.71 L 163.82 138.25 L 161.67 139.05 L 160.28 140.28 L 159.68 142.03 L 159.76 144.32 L 160.32 147.07 L 161.07 150.12 L 161.7 153.23 L 161.94 156.14 L 161.56 158.61 L 160.46 160.46 L 158.61 161.56 L 156.14 161.94 L 153.23 161.7 L 150.12 161.07 L 147.07 160.32 L 144.32 159.76 L 142.03 159.68 L 140.28 160.28 L 139.05 161.67 L 138.25 163.82 L 137.71 166.57 L 137.24 169.67 L 136.64 172.79 L 135.74 175.57 L 134.45 177.71 L 132.72 178.99 L 130.59 179.31 L 128.16 178.71 L 125.56 177.37 L 122.93 175.6 L 120.41 173.74 L 118.08 172.17 L 115.99 171.22 L 114.14 171.11 L 112.48 171.92 L 110.92 173.6 L 109.37 175.94 L 107.74 178.62 L 105.99 181.27 L 104.1 183.5 L 102.09 184.98 L 100 185.5 L 97.91 184.98 L 95.9 183.5 L 94.01 181.27 L 92.26 178.62 L 90.63 175.94 L 89.08 173.6 L 87.52 171.92 L 85.86 171.11 L 84.01 171.22 L 81.92 172.17 L 79.59 173.74 L 77.07 175.6 L 74.44 177.37 L 71.84 178.71 L 69.41 179.31 L 67.28 178.99 L 65.55 177.71 L 64.26 175.57 L 63.36 172.79 L 62.76 169.67 L 62.29 166.57 L 61.75 163.82 L 60.95 161.67 L 59.72 160.28 L 57.97 159.68 L 55.68 159.76 L 52.93 160.32 L 49.88 161.07 L 46.77 161.7 L 43.86 161.94 L 41.39 161.56 L 39.54 160.46 L 38.44 158.61 L 38.06 156.14 L 38.3 153.23 L 38.93 150.12 L 39.68 147.07 L 40.24 144.32 L 40.32 142.03 L 39.72 140.28 L 38.33 139.05 L 36.18 138.25 L 33.43 137.71 L 30.33 137.24 L 27.21 136.64 L 24.43 135.74 L 22.29 134.45 L 21.01 132.72 L 20.69 130.59 L 21.29 128.16 L 22.63 125.56 L 24.4 122.93 L 26.26 120.41 L 27.83 118.08 L 28.78 115.99 L 28.89 114.14 L 28.08 112.48 L 26.4 110.92 L 24.06 109.37 L 21.38 107.74 L 18.73 105.99 L 16.5 104.1 L 15.02 102.09 L 14.5 100 L 15.02 97.91 L 16.5 95.9 L 18.73 94.01 L 21.38 92.26 L 24.06 90.63 L 26.4 89.08 L 28.08 87.52 L 28.89 85.86 L 28.78 84.01 L 27.83 81.92 L 26.26 79.59 L 24.4 77.07 L 22.63 74.44 L 21.29 71.84 L 20.69 69.41 L 21.01 67.28 L 22.29 65.55 L 24.43 64.26 L 27.21 63.36 L 30.33 62.76 L 33.43 62.29 L 36.18 61.75 L 38.33 60.95 L 39.72 59.72 L 40.32 57.97 L 40.24 55.68 L 39.68 52.93 L 38.93 49.88 L 38.3 46.77 L 38.06 43.86 L 38.44 41.39 L 39.54 39.54 L 41.39 38.44 L 43.86 38.06 L 46.77 38.3 L 49.88 38.93 L 52.93 39.68 L 55.68 40.24 L 57.97 40.32 L 59.72 39.72 L 60.95 38.33 L 61.75 36.18 L 62.29 33.43 L 62.76 30.33 L 63.36 27.21 L 64.26 24.43 L 65.55 22.29 L 67.28 21.01 L 69.41 20.69 L 71.84 21.29 L 74.44 22.63 L 77.07 24.4 L 79.59 26.26 L 81.92 27.83 L 84.01 28.78 L 85.86 28.89 L 87.52 28.08 L 89.08 26.4 L 90.63 24.06 L 92.26 21.38 L 94.01 18.73 L 95.9 16.5 L 97.91 15.02 Z"
                                      fill="#F6F0E7" stroke="#364728" stroke-width="1.8" stroke-linejoin="round"/>
                            </svg>
                            <div class="modal-badge-content">
                                <span class="modal-badge-label">Table No.</span>
                                <span class="modal-badge-num" id="modalTableNumber">12</span>
                            </div>
                        </div>

                        <!-- 2. "Our Menu" -->
                        <div class="modal-menu-heading">Our Menu</div>

                        <!-- 3. Outer Scanner Frame -->
                        <div class="modal-scanner-frame">
                            <div class="modal-corner-bracket modal-bracket-tl"></div>
                            <div class="modal-corner-bracket modal-bracket-tr"></div>
                            <div class="modal-corner-bracket modal-bracket-bl"></div>
                            <div class="modal-corner-bracket modal-bracket-br"></div>

                            <div class="modal-qr-white" id="modalQrSvg">
                                <div class="spinner-border text-danger" role="status">
                                    <span class="visually-hidden">Loading QR...</span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Crossed Fork & Spoon with Golden Leaves -->
                        <div class="modal-utensils">
                            <svg width="48" height="44" viewBox="0 0 68 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <!-- Golden Leaves Accent behind handles -->
                                <g fill="#DFBA53" opacity="0.95">
                                    <path d="M 34 46 C 28 43 21 35 22 26 C 27 28 32 35 34 44 Z" />
                                    <path d="M 34 46 C 40 43 47 35 46 26 C 41 28 36 35 34 44 Z" />
                                </g>

                                <!-- Fork (Left, angled -23deg) - behind spoon crossing -->
                                <g transform="translate(34, 37) rotate(-23) translate(-34, -37)">
                                    <path d="M 32.2 24 L 32.2 49 C 32.2 50.8 33 52 34 52 C 35 52 35.8 50.8 35.8 49 L 35.8 24" 
                                          stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                                    <path d="M 27 10 L 28.5 21 C 28.8 23.5 31 25.5 34 25.5 C 37 25.5 39.2 23.5 39.5 21 L 41 10 
                                             C 40.8 9.5 39.5 9.5 39.3 10.2 L 37.8 19.5 C 37.6 20.2 36.6 20.2 36.4 19.5 L 35.2 10.2 
                                             C 35 9.5 33.8 9.5 33.6 10.2 L 32.4 19.5 C 32.2 20.2 31.2 20.2 31 19.5 L 29.5 10.2 
                                             C 29.3 9.5 28 9.5 27.8 10 Z" 
                                          stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                                </g>

                                <!-- Spoon (Right, angled +23deg) - in front of fork -->
                                <g transform="translate(34, 37) rotate(23) translate(-34, -37)">
                                    <path d="M 32.2 23 L 32.2 49 C 32.2 50.8 33 52 34 52 C 35 52 35.8 50.8 35.8 49 L 35.8 23" 
                                          stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                                    <path d="M 34 7.5 C 29.5 7.5 26.5 12 27 17 C 27.5 21 30.5 24 34 24 C 37.5 24 40.5 21 41 17 C 41.5 12 38.5 7.5 34 7.5 Z" 
                                          stroke="#243015" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" fill="#F6F0E7" />
                                </g>
                            </svg>
                        </div>

                        <!-- 5. "Order here" -->
                        <div class="modal-order-here">Order here</div>
                    </div>
                </div>

                {{-- Mobile Wi-Fi Notice --}}
                <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 text-start">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-wifi fs-5 text-success"></i>
                        <div>
                            <strong>Mobile Wi-Fi Ready:</strong>
                            <div class="text-muted" style="font-size: 11px;">
                                Mobile phones on your Wi-Fi will open this link directly.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Mobile Menu URL --}}
                <div class="mb-3 text-start">
                    <label class="form-label small fw-semibold text-muted mb-1">Direct Table Order URL</label>
                    <div class="input-group input-group-sm">
                        <input type="text" id="modalTableUrl" class="form-control bg-light" readonly>
                        <button class="btn btn-dark" type="button" onclick="copyTableUrl()">
                            <i class="bi bi-clipboard me-1"></i> Copy
                        </button>
                    </div>
                </div>

                {{-- Optional Base URL Customizer --}}
                <div class="accordion accordion-flush mb-2" id="advancedQrAccordion">
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2 px-0 small text-secondary bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBaseUrl">
                                <i class="bi bi-gear me-1"></i> Change Mobile Network / Domain URL
                            </button>
                        </h2>
                        <div id="collapseBaseUrl" class="accordion-collapse collapse" data-bs-parent="#advancedQrAccordion">
                            <div class="accordion-body px-0 pt-2 pb-0 text-start">
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" id="modalCustomBaseUrl" class="form-control" placeholder="http://192.168.x.x/restaurant/public">
                                    <button class="btn btn-outline-danger" type="button" onclick="applyCustomBaseUrl()">
                                        Save &amp; Apply
                                    </button>
                                </div>
                                <small class="text-muted" style="font-size: 11px;">
                                    If you change Wi-Fi networks or use a custom domain/tunnel, update it here.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 justify-content-between">
                <a id="modalTestMenuBtn" href="#" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Test Menu
                </a>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm" onclick="downloadQrSvg()">
                        <i class="bi bi-download me-1"></i> Download SVG
                    </button>
                    <a id="modalPrintCardBtn" href="#" target="_blank" class="btn btn-danger btn-sm">
                        <i class="bi bi-printer me-1"></i> Print Stand Card
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentQrSvg = '';
let currentTableNum = '';
let currentTableId = '';

function openQrModal(tableId, tableNumber, tableArea, url) {
    currentTableId = tableId;
    currentTableNum = tableNumber;
    
    // Clean table number for badge display (e.g. "01", "02", "1", "2", "12")
    const cleanNum = String(tableNumber).replace(/^Table\s*(No\.?)?\s*/i, '').replace(/^T-?\s*/i, '');
    const numEl = document.getElementById('modalTableNumber');
    numEl.textContent = cleanNum;
    numEl.className = 'modal-badge-num';
    if (cleanNum.length >= 5) {
        numEl.classList.add('text-scale-sm');
    } else if (cleanNum.length >= 3) {
        numEl.classList.add('text-scale-md');
    }

    document.getElementById('modalTableArea').textContent = tableArea;
    document.getElementById('modalTableUrl').value = url;
    document.getElementById('modalTestMenuBtn').href = url;
    document.getElementById('modalPrintCardBtn').href = `/admin/tables/${tableId}/print-qr`;

    loadQrCode(tableId);

    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    modal.show();
}

function loadQrCode(tableId, baseUrl = null) {
    const svgContainer = document.getElementById('modalQrSvg');
    svgContainer.innerHTML = '<div class="spinner-border text-danger m-4" role="status"></div>';

    let fetchUrl = `/admin/tables/${tableId}/qrcode`;
    if (baseUrl) {
        fetchUrl += '?base_url=' + encodeURIComponent(baseUrl);
    }

    fetch(fetchUrl)
        .then(res => res.json())
        .then(data => {
            currentQrSvg = data.svg;
            svgContainer.innerHTML = data.svg;
            document.getElementById('modalTableUrl').value = data.url;
            document.getElementById('modalTestMenuBtn').href = data.url;
            if (data.current_base && document.getElementById('modalCustomBaseUrl')) {
                document.getElementById('modalCustomBaseUrl').value = data.current_base;
            }

            const svgEl = svgContainer.querySelector('svg');
            if (svgEl) {
                svgEl.setAttribute('width', '100%');
                svgEl.setAttribute('height', '100%');
            }
        })
        .catch(err => {
            svgContainer.innerHTML = '<span class="text-danger small">Error generating QR code.</span>';
        });
}

function applyCustomBaseUrl() {
    const customBase = document.getElementById('modalCustomBaseUrl').value.trim();
    if (!customBase) return;

    fetch('/admin/tables/save-qr-base', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ qr_base_url: customBase })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        loadQrCode(currentTableId, customBase);
    })
    .catch(err => {
        alert('Could not update base URL: ' + err.message);
    });
}

function copyTableUrl() {
    const urlInput = document.getElementById('modalTableUrl');
    urlInput.select();
    navigator.clipboard.writeText(urlInput.value).then(() => {
        alert('Table menu URL copied to clipboard: ' + urlInput.value);
    });
}

function downloadQrSvg() {
    if (!currentQrSvg) return;
    const blob = new Blob([currentQrSvg], { type: 'image/svg+xml' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `table-${currentTableNum}-qr.svg`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>

@endsection