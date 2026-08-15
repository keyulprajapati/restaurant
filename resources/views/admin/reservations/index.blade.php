@extends('layouts.admin')

@section('title', 'Reservations')

@section('page-title', 'Reservations')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Reservations
        </h3>

        <p class="text-muted mb-0">
            Manage restaurant table reservations.
        </p>

    </div>

    @can('reservations.create')

        <a
            href="{{ route('admin.reservations.create') }}"
            class="btn btn-danger">

            <i class="bi bi-plus-lg me-2"></i>

            New Reservation

        </a>

    @endcan

</div>


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


{{-- Filters --}}

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <form method="GET">

            <div class="row g-2">

                <div class="col-md-4">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Customer name / phone">

                </div>


                <div class="col-md-3">

                    <input
                        type="date"
                        name="reservation_date"
                        value="{{ request('reservation_date') }}"
                        class="form-control">

                </div>


                <div class="col-md-3">

                    <select
                        name="status"
                        class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending">
                            Pending
                        </option>

                        <option value="confirmed">
                            Confirmed
                        </option>

                        <option value="seated">
                            Seated
                        </option>

                        <option value="completed">
                            Completed
                        </option>

                        <option value="cancelled">
                            Cancelled
                        </option>

                        <option value="no_show">
                            No Show
                        </option>

                    </select>

                </div>


                <div class="col-md-2">

                    <button class="btn btn-dark w-100">

                        <i class="bi bi-search me-1"></i>

                        Search

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Reservations --}}

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                <tr>

                    <th>Customer</th>

                    <th>Date</th>

                    <th>Time</th>

                    <th>Guests</th>

                    <th>Table</th>

                    <th>Status</th>

                    <th class="text-end">
                        Actions
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($reservations as $reservation)

                    <tr>

                        <td>

                            <div class="fw-semibold">

                                {{ $reservation->customer->name ?? "" }}

                            </div>

                            <small class="text-muted">

                                {{ $reservation->customer->phone ?? "" }}

                            </small>

                        </td>


                        <td>

                            {{ $reservation->reservation_date->format('d M Y') }}

                        </td>


                        <td>

                            {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('h:i A') }}

                        </td>


                        <td>

                            <i class="bi bi-people me-1"></i>

                            {{ $reservation->guests }}

                        </td>


                        <td>

                            @if($reservation->table)

                                <span class="fw-semibold">

                                    {{ $reservation->table->table_number }}

                                </span>

                                <small class="text-muted d-block">

                                    {{ $reservation->table->capacity }}
                                    seats

                                </small>

                            @else

                                <span class="text-muted">
                                    Not Assigned
                                </span>

                            @endif

                        </td>


                        <td>

                            @switch($reservation->status)

                                @case('pending')

                                    <span class="badge bg-warning-subtle text-warning-emphasis">
                                        Pending
                                    </span>

                                    @break

                                @case('confirmed')

                                    <span class="badge bg-primary-subtle text-primary">
                                        Confirmed
                                    </span>

                                    @break

                                @case('seated')

                                    <span class="badge bg-success-subtle text-success">
                                        Seated
                                    </span>

                                    @break

                                @case('completed')

                                    <span class="badge bg-dark-subtle text-dark">
                                        Completed
                                    </span>

                                    @break

                                @case('cancelled')

                                    <span class="badge bg-danger-subtle text-danger">
                                        Cancelled
                                    </span>

                                    @break

                                @case('no_show')

                                    <span class="badge bg-secondary-subtle text-secondary">
                                        No Show
                                    </span>

                                    @break

                            @endswitch

                        </td>


                        <td class="text-end">

                            @can('reservations.edit')

                                <a
                                    href="{{ route('admin.reservations.edit', $reservation) }}"
                                    class="btn btn-sm btn-light">

                                    <i class="bi bi-pencil"></i>

                                </a>

                            @endcan


                            @can('reservations.delete')

                                <form
                                    method="POST"
                                    action="{{ route('admin.reservations.destroy', $reservation) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('Delete this reservation?')">

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

                            <i class="bi bi-calendar-x fs-1 text-muted"></i>

                            <p class="text-muted mt-2 mb-0">
                                No reservations found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-3">

            {{ $reservations->links() }}

        </div>

    </div>

</div>

@endsection