@extends('layouts.admin')

@section('title', 'Customers')

@section('page-title', 'Customers')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Customers
            </h4>

            <p class="text-muted mb-0">
                Manage restaurant customers and their reservation history.
            </p>

        </div>

        <a
            href="{{ route('admin.customers.create') }}"
            class="btn btn-danger">

            <i class="bi bi-person-plus me-2"></i>

            Add Customer

        </a>

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

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Search --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.customers.index') }}">

                <div class="row g-2">

                    <div class="col-md-6">

                        <div class="input-group">

                            <span class="input-group-text bg-white">

                                <i class="bi bi-search"></i>

                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Search name, phone or email">

                        </div>

                    </div>


                    <div class="col-md-3">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                @selected(request('status') === 'active')>

                                Active

                            </option>

                            <option
                                value="inactive"
                                @selected(request('status') === 'inactive')>

                                Inactive

                            </option>

                        </select>

                    </div>


                    <div class="col-md-3">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-danger">

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>

                            <a
                                href="{{ route('admin.customers.index') }}"
                                class="btn btn-light">

                                Reset

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Customer Table --}}

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Customer
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Reservations
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end px-4">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($customers as $customer)

                            <tr>

                                <td class="px-4">

                                    <div class="d-flex align-items-center">

                                        <div
                                            class="rounded-circle bg-danger-subtle text-danger
                                                   d-flex align-items-center justify-content-center
                                                   me-3"
                                            style="width:42px;height:42px;">

                                            {{ strtoupper(
                                                substr($customer->name, 0, 1)
                                            ) }}

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $customer->name }}

                                            </div>

                                            <small class="text-muted">

                                                Customer #{{ $customer->id }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <td>

                                    {{ $customer->phone }}

                                </td>


                                <td>

                                    {{ $customer->email ?: '-' }}

                                </td>


                                <td>

                                    <span class="badge bg-light text-dark">

                                        {{ $customer->reservations_count }}

                                    </span>

                                </td>


                                <td>

                                    @if($customer->is_active)

                                        <span class="badge bg-success-subtle text-success">

                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-secondary-subtle text-secondary">

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                <td class="text-end px-4">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm btn-light"
                                            data-bs-toggle="dropdown">

                                            <i class="bi bi-three-dots"></i>

                                        </button>

                                        <ul class="dropdown-menu dropdown-menu-end">

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.customers.show',
                                                        $customer
                                                    ) }}">

                                                    <i class="bi bi-eye me-2"></i>

                                                    View

                                                </a>

                                            </li>

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route(
                                                        'admin.customers.edit',
                                                        $customer
                                                    ) }}">

                                                    <i class="bi bi-pencil me-2"></i>

                                                    Edit

                                                </a>

                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route(
                                                        'admin.customers.destroy',
                                                        $customer
                                                    ) }}"
                                                    onsubmit="return confirm(
                                                        'Are you sure you want to delete this customer?'
                                                    )">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger">

                                                        <i class="bi bi-trash me-2"></i>

                                                        Delete

                                                    </button>

                                                </form>

                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i
                                            class="bi bi-people fs-1 d-block mb-3">
                                        </i>

                                        No customers found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($customers->hasPages())

            <div class="card-footer bg-white border-0">

                {{ $customers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection