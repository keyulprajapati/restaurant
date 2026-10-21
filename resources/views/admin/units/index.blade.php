@extends('layouts.admin')

@section('title', 'Unit Management')

@section('page-title', 'Unit Management')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            Unit Management
        </h3>
        <p class="text-muted mb-0">
            Manage measurement units for products, drinks, and combo packages.
        </p>
    </div>

    @can('units.create')
        <a href="{{ route('admin.units.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-2"></i>
            Add Unit
        </a>
    @endcan
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Quick Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-rulers fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Units</div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Active Units</div>
                    <div class="fs-4 fw-bold">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="rounded-3 bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="bi bi-pause-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Inactive Units</div>
                    <div class="fs-4 fw-bold">{{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.units.index') }}">
            <div class="row g-2">
                {{-- Search --}}
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Search unit by name, code or description...">
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-lg-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-fill">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.units.index') }}" class="btn btn-light" title="Clear Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Units Table --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3" style="width: 60px;">#</th>
                        <th class="py-3">Unit Name</th>
                        <th class="py-3">Short Code</th>
                        <th class="py-3">Description</th>
                        <th class="py-3">Assigned Products</th>
                        <th class="py-3">Status</th>
                        <th class="text-end px-4 py-3" style="width: 140px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td class="px-4 text-muted">
                                {{ $units->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px;">
                                        @if($unit->short_code === 'glass')
                                            <i class="bi bi-cup-straw fs-5"></i>
                                        @elseif($unit->short_code === 'bottle')
                                            <i class="bi bi-cup-hot fs-5"></i>
                                        @elseif($unit->short_code === 'cup')
                                            <i class="bi bi-cup fs-5"></i>
                                        @elseif(in_array($unit->short_code, ['kg', 'gram']))
                                            <i class="bi bi-speedometer2 fs-5"></i>
                                        @else
                                            <i class="bi bi-rulers fs-5"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">
                                            {{ $unit->name }}
                                        </div>
                                        <small class="text-muted">
                                            ID #{{ $unit->id }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                    {{ $unit->short_code }}
                                </span>
                            </td>

                            <td>
                                <span class="text-muted small">
                                    {{ $unit->description ?: '—' }}
                                </span>
                            </td>

                            <td>
                                @php
                                    $usageCount = $unit->products_count + $unit->combo_items_count;
                                @endphp
                                @if($usageCount > 0)
                                    <span class="badge rounded-pill bg-info-subtle text-info px-2 py-1">
                                        <i class="bi bi-box-seam me-1"></i>
                                        {{ $usageCount }} items
                                    </span>
                                @else
                                    <span class="text-muted small">0 items</span>
                                @endif
                            </td>

                            <td>
                                @can('units.edit')
                                    <form action="{{ route('admin.units.toggle-status', $unit) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="badge rounded-pill border-0 px-3 py-2 text-decoration-none cursor-pointer {{ $unit->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}"
                                                title="Click to toggle status">
                                            <i class="bi {{ $unit->is_active ? 'bi-check-circle' : 'bi-pause-circle' }} me-1"></i>
                                            {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="badge rounded-pill px-3 py-2 {{ $unit->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $unit->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @endcan
                            </td>

                            <td class="text-end px-4">
                                <div class="d-inline-flex gap-1">
                                    @can('units.edit')
                                        <a href="{{ route('admin.units.edit', $unit) }}"
                                           class="btn btn-sm btn-light text-primary"
                                           title="Edit Unit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan

                                    @can('units.delete')
                                        <button type="button"
                                                class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteUnitModal{{ $unit->id }}"
                                                title="Delete Unit"
                                                {{ $unit->isInUse() ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        {{-- Delete Modal --}}
                                        <div class="modal fade" id="deleteUnitModal{{ $unit->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered text-start">
                                                <div class="modal-content border-0 shadow rounded-4">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Delete Unit</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-4">
                                                        <p class="mb-0 text-muted">
                                                            Are you sure you want to delete unit <strong class="text-dark">"{{ $unit->name }}" ({{ $unit->short_code }})</strong>? This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash me-1"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-rulers display-5 d-block mb-3 text-secondary"></i>
                                    <h5>No units found</h5>
                                    <p class="mb-3">Start by creating a new measurement unit.</p>
                                    @can('units.create')
                                        <a href="{{ route('admin.units.create') }}" class="btn btn-danger">
                                            <i class="bi bi-plus-lg me-2"></i> Add Unit
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($units->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $units->links() }}
        </div>
    @endif
</div>

@endsection
