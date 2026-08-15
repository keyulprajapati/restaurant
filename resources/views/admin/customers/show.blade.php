@extends('layouts.admin')

@section('title', 'Customer Details')

@section('page-title', 'Customer Details')

@section('content')

<div class="row g-4">

    {{-- Customer Profile --}}

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 text-center">

                <div
                    class="rounded-circle bg-danger-subtle text-danger
                           d-flex align-items-center justify-content-center
                           mx-auto mb-3"
                    style="width:80px;height:80px;font-size:30px;">

                    {{ strtoupper(
                        substr($customer->name, 0, 1)
                    ) }}

                </div>


                <h4 class="fw-bold mb-1">

                    {{ $customer->name }}

                </h4>


                <div class="text-muted mb-4">

                    Customer #{{ $customer->id }}

                </div>


                <div class="text-start">


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Mobile
                        </small>

                        <span class="fw-semibold">

                            <i class="bi bi-telephone me-2"></i>

                            {{ $customer->phone }}

                        </span>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Email
                        </small>

                        <span class="fw-semibold">

                            <i class="bi bi-envelope me-2"></i>

                            {{ $customer->email ?: '-' }}

                        </span>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Date of Birth
                        </small>

                        <span class="fw-semibold">

                            {{ $customer->date_of_birth
                                ? $customer->date_of_birth->format('d M Y')
                                : '-' }}

                        </span>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Address
                        </small>

                        <span>

                            {{ $customer->address ?: '-' }}

                        </span>

                    </div>


                    <div>

                        <small class="text-muted d-block">
                            Status
                        </small>

                        @if($customer->is_active)

                            <span class="badge bg-success-subtle text-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary-subtle text-secondary">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>


                <div class="mt-4">

                    <a
                        href="{{ route(
                            'admin.customers.edit',
                            $customer
                        ) }}"
                        class="btn btn-danger w-100">

                        <i class="bi bi-pencil me-2"></i>

                        Edit Customer

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- Reservation History --}}

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <div class="d-flex justify-content-between mb-4">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Reservation History
                        </h5>

                        <p class="text-muted mb-0">
                            Customer's previous reservations.
                        </p>

                    </div>

                    <span class="badge bg-danger-subtle text-danger align-self-start">

                        {{ $customer->reservations->count() }}

                        Reservations

                    </span>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Time
                                </th>

                                <th>
                                    Table
                                </th>

                                <th>
                                    Guests
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(
                                $customer->reservations
                                as $reservation
                            )

                                <tr>

                                    <td>

                                        {{ $reservation->reservation_date
                                            ? $reservation->reservation_date->format('d M Y')
                                            : '-' }}

                                    </td>


                                    <td>

                                        {{ \Carbon\Carbon::parse(
                                            $reservation->reservation_time
                                        )->format('h:i A') }}

                                    </td>


                                    <td>

                                        {{ $reservation->table?->table_number ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $reservation->guests }}

                                    </td>


                                    <td>

                                        @php

                                            $statusClass = match(
                                                $reservation->status
                                            ) {

                                                'confirmed' =>
                                                    'bg-success-subtle text-success',

                                                'pending' =>
                                                    'bg-warning-subtle text-warning',

                                                'seated' =>
                                                    'bg-primary-subtle text-primary',

                                                'completed' =>
                                                    'bg-info-subtle text-info',

                                                'cancelled',
                                                'no_show' =>
                                                    'bg-danger-subtle text-danger',

                                                default =>
                                                    'bg-light text-dark',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $statusClass }}">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $reservation->status
                                                )
                                            ) }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center text-muted py-5">

                                        <i
                                            class="bi bi-calendar-x fs-1 d-block mb-3">
                                        </i>

                                        No reservations found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<div class="mt-4">

    <a
        href="{{ route('admin.customers.index') }}"
        class="btn btn-light">

        <i class="bi bi-arrow-left me-2"></i>

        Back to Customers

    </a>

</div>

@endsection