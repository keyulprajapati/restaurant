@extends('layouts.admin')

@section('title', 'Table Management')

@section('page-title', 'Table Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Table Management
        </h3>

        <p class="text-muted mb-0">
            Manage restaurant dining tables and seating.
        </p>
    </div>

    @can('tables.create')

        <a
            href="{{ route('admin.tables.create') }}"
            class="btn btn-danger">

            <i class="bi bi-plus-lg me-2"></i>

            Add Table

        </a>

    @endcan

</div>


{{-- Alerts --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- Summary --}}

<div class="row g-3 mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <small class="text-muted">
                            Available
                        </small>

                        <h3 class="fw-bold mb-0">

                            {{ \App\Models\RestaurantTable::where('status', 'available')->where('is_active', true)->count() }}

                        </h3>

                    </div>

                    <div class="bg-success-subtle
                                text-success
                                rounded-3
                                p-3">

                        <i class="bi bi-check-circle fs-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <small class="text-muted">
                            Occupied
                        </small>

                        <h3 class="fw-bold mb-0">

                            {{ \App\Models\RestaurantTable::where('status', 'occupied')->where('is_active', true)->count() }}

                        </h3>

                    </div>

                    <div class="bg-danger-subtle
                                text-danger
                                rounded-3
                                p-3">

                        <i class="bi bi-people fs-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <div class="d-flex
                            justify-content-between
                            align-items-center">

                    <div>

                        <small class="text-muted">
                            Reserved
                        </small>

                        <h3 class="fw-bold mb-0">

                            {{ \App\Models\RestaurantTable::where('status', 'reserved')->where('is_active', true)->count() }}

                        </h3>

                    </div>

                    <div class="bg-warning-subtle
                                text-warning
                                rounded-3
                                p-3">

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

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Search table number, name or area">

                </div>


                <div class="col-md-3">

                    <select
                        name="status"
                        class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="available"
                            @selected(request('status') === 'available')>

                            Available

                        </option>

                        <option
                            value="occupied"
                            @selected(request('status') === 'occupied')>

                            Occupied

                        </option>

                        <option
                            value="reserved"
                            @selected(request('status') === 'reserved')>

                            Reserved

                        </option>

                    </select>

                </div>


                <div class="col-md-2">

                    <select
                        name="area"
                        class="form-select">

                        <option value="">
                            All Areas
                        </option>

                        @foreach($areas as $area)

                            <option
                                value="{{ $area }}"
                                @selected(request('area') === $area)>

                                {{ $area }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2 d-flex gap-2">

                    <button
                        class="btn btn-dark flex-fill">

                        <i class="bi bi-search"></i>

                    </button>

                    <a
                        href="{{ route('admin.tables.index') }}"
                        class="btn btn-light">

                        <i class="bi bi-arrow-counterclockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Table list --}}

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

                    <th>Configuration</th>

                    <th class="text-end">
                        Actions
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($tables as $table)

                    <tr>

                        <td>

                            <div class="d-flex align-items-center">

                                <div class="rounded-3
                                            bg-danger-subtle
                                            text-danger
                                            p-2 me-3">

                                    <i class="bi bi-table"></i>

                                </div>

                                <div>

                                    <div class="fw-bold">

                                        {{ $table->table_number }}

                                    </div>

                                    @if($table->name)

                                        <small class="text-muted">

                                            {{ $table->name }}

                                        </small>

                                    @endif

                                </div>

                            </div>

                        </td>


                        <td>

                            <i class="bi bi-people me-1"></i>

                            {{ $table->capacity }}

                        </td>


                        <td>

                            {{ $table->area ?: '-' }}

                        </td>


                        <td>

                            {{ $table->type_label }}

                        </td>


                        <td>

                            @switch($table->status)

                                @case('available')

                                    <span class="badge
                                        bg-success-subtle
                                        text-success">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Available

                                    </span>

                                    @break

                                @case('occupied')

                                    <span class="badge
                                        bg-danger-subtle
                                        text-danger">

                                        <i class="bi bi-person-fill me-1"></i>

                                        Occupied

                                    </span>

                                    @break

                                @case('reserved')

                                    <span class="badge
                                        bg-warning-subtle
                                        text-warning-emphasis">

                                        <i class="bi bi-calendar-check me-1"></i>

                                        Reserved

                                    </span>

                                    @break

                            @endswitch

                        </td>


                        <td>

                            @if($table->is_active)

                                <span class="badge
                                    bg-success-subtle
                                    text-success">

                                    Active

                                </span>

                            @else

                                <span class="badge
                                    bg-secondary-subtle
                                    text-secondary">

                                    Inactive

                                </span>

                            @endif

                        </td>


                        <td class="text-end">

                            @can('tables.edit')

                                <a
                                    href="{{ route('admin.tables.edit', $table) }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan


                            @can('tables.delete')

                                <form
                                    action="{{ route('admin.tables.destroy', $table) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this table?')">

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        class="btn btn-sm btn-light text-danger">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            @endcan

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center py-5">

                            <i class="bi bi-table
                                      fs-1
                                      text-muted">
                            </i>

                            <p class="text-muted mt-2">
                                No restaurant tables found.
                            </p>

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

@endsection