@extends('layouts.admin')

@section('title', 'Taxes')

@section('page-title', 'Tax Management')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">Tax Management</h3>
        <p class="text-muted mb-0">Configure tax rates and rules for restaurant orders.</p>
    </div>

    @can('taxes.create')
        <a href="{{ route('admin.taxes.create') }}" class="btn btn-danger">
            <i class="bi bi-plus-lg me-2"></i>Add Tax Rule
        </a>
    @endcan
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Search & Filter Card --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.taxes.index') }}">
            <div class="row g-2">

                {{-- Search --}}
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control border-start-0 ps-0" placeholder="Search by name or code...">
                    </div>
                </div>

                {{-- Tax Type Filter --}}
                <div class="col-lg-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="percentage" @selected(request('type') === 'percentage')>Percentage (%)</option>
                        <option value="fixed" @selected(request('type') === 'fixed')>Fixed Amount</option>
                    </select>
                </div>

                {{-- Applies To Filter --}}
                <div class="col-lg-2">
                    <select name="applies_to" class="form-select">
                        <option value="">All Applies To</option>
                        <option value="all" @selected(request('applies_to') === 'all')>All Items</option>
                        <option value="food" @selected(request('applies_to') === 'food')>Food</option>
                        <option value="beverage" @selected(request('applies_to') === 'beverage')>Beverage</option>
                        <option value="service" @selected(request('applies_to') === 'service')>Service</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="col-lg-2">
                    <select name="is_active" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" @selected(request('is_active') === '1')>Active</option>
                        <option value="0" @selected(request('is_active') === '0')>Inactive</option>
                    </select>
                </div>

                {{-- Filter & Reset Buttons --}}
                <div class="col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark flex-fill">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>

                    @if(request()->hasAny(['search', 'type', 'applies_to', 'is_active']))
                        <a href="{{ route('admin.taxes.index') }}" class="btn btn-light border" title="Reset Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Taxes List Table --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4" style="width: 60px;">#</th>
                        <th>Tax Name & Code</th>
                        <th>Type</th>
                        <th>Rate / Amount</th>
                        <th>Applies To</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th class="text-end px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($taxes as $tax)
                        <tr>
                            <td class="px-4 text-muted">
                                {{ $taxes->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-3"
                                         style="width:42px; height:42px;">
                                        <i class="bi bi-percent fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $tax->name }}</div>
                                        <span class="badge bg-secondary-subtle text-secondary font-monospace">{{ $tax->code }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($tax->type === 'percentage')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-percent me-1"></i>Percentage
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-currency-dollar me-1"></i>Fixed Amount
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-bold text-dark">
                                    @if($tax->type === 'percentage')
                                        {{ number_format($tax->rate, 2) }}%
                                    @else
                                        ₹{{ number_format($tax->rate, 2) }}
                                    @endif
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-capitalize">
                                    <i class="bi bi-tag me-1 text-muted"></i>{{ $tax->applies_to }}
                                </span>
                            </td>

                            <td>
                                @if($tax->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3 py-1">
                                        <i class="bi bi-x-circle me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="text-muted small">
                                {{ Str::limit($tax->description ?? 'No description', 40) }}
                            </td>

                            <td class="text-end px-4">
                                <div class="d-inline-flex gap-2">
                                    @can('taxes.edit')
                                        <a href="{{ route('admin.taxes.edit', $tax) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit Tax">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan

                                    @can('taxes.delete')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteTaxModal{{ $tax->id }}"
                                                title="Delete Tax">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                        {{-- Delete Confirmation Modal --}}
                                        <div class="modal fade text-start" id="deleteTaxModal{{ $tax->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold">Delete Tax Rule</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        <p class="mb-0">Are you sure you want to delete <strong>{{ $tax->name }}</strong> (<code>{{ $tax->code }}</code>)? This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer border-0 pt-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="{{ route('admin.taxes.destroy', $tax) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete Tax</button>
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
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 64px; height: 64px;">
                                        <i class="bi bi-percent fs-3"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted mb-1">No Tax Rules Found</h5>
                                    <p class="text-muted mb-3 small">Get started by creating your first tax rule for orders.</p>
                                    @can('taxes.create')
                                        <a href="{{ route('admin.taxes.create') }}" class="btn btn-danger btn-sm">
                                            <i class="bi bi-plus-lg me-1"></i> Add Tax Rule
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

    @if($taxes->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $taxes->links() }}
        </div>
    @endif
</div>

@endsection
