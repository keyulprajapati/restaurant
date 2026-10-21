@extends('layouts.admin')

@section('title', 'Branches')
@section('page-title', 'Branches')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Branch Management</h4>
        <p class="text-muted mb-0">Create and manage restaurant branches for multi-location operations.</p>
    </div>

    <a href="{{ route('admin.branches.create') }}" class="btn btn-danger">
        <i class="bi bi-plus-lg me-1"></i> Add Branch
    </a>
</div>

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

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form method="GET">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search branch name, code or contact">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" @selected(request('status') === '1')>Active</option>
                        <option value="0" @selected(request('status') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-dark flex-fill" type="submit">Filter</button>
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Branch</th>
                        <th>Code</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $branch)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $branch->name }}</div>
                                @if($branch->address)
                                    <small class="text-muted">{{ $branch->address }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $branch->code }}</span></td>
                            <td>
                                @if($branch->phone)
                                    <div>{{ $branch->phone }}</div>
                                @endif
                                @if($branch->email)
                                    <small class="text-muted">{{ $branch->email }}</small>
                                @endif
                            </td>
                            <td>
                                @if($branch->is_active)
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                                    <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" onsubmit="return confirm('Delete this branch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                No branches found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $branches->links() }}
</div>
@endsection
