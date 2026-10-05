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

{{-- Dynamic QR Code Modal --}}
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="qrModalLabel">
                    <i class="bi bi-qr-code text-danger me-2"></i>Table QR Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-3 px-4">
                <div class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fs-6 mb-1" id="modalTableNumber">
                    TABLE T01
                </div>
                <div class="text-muted small mb-3" id="modalTableArea">Ground Floor</div>

                {{-- QR Container --}}
                <div class="p-3 bg-white border border-2 border-dashed rounded-4 d-inline-block shadow-sm mb-3" id="modalQrSvg">
                    <div class="spinner-border text-danger" role="status">
                        <span class="visually-hidden">Loading QR...</span>
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
    document.getElementById('modalTableNumber').textContent = 'TABLE ' + tableNumber;
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
                svgEl.setAttribute('width', '200');
                svgEl.setAttribute('height', '200');
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