@extends('layouts.admin')

@section('title', 'Edit Reservation')

@section('page-title', 'Edit Reservation')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-9">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-4">

                    <div>

                        <h4 class="fw-bold mb-1">
                            Edit Reservation
                        </h4>

                        <p class="text-muted mb-0">
                            Update reservation details and customer information.
                        </p>

                    </div>

                    <div>

                        <span class="badge bg-light text-dark">
                            #{{ $reservation->id }}
                        </span>

                    </div>

                </div>


                {{-- Validation Errors --}}
                @if($errors->any())

                    <div class="alert alert-danger alert-dismissible fade show">

                        <div class="fw-semibold mb-2">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            Please correct the following errors:

                        </div>

                        <ul class="mb-0">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                        </button>

                    </div>

                @endif


                {{-- Success --}}
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


                <form
                    method="POST"
                    action="{{ route('admin.reservations.update', $reservation) }}">

                    @csrf

                    @method('PUT')


                    <div class="row g-3">


                        {{-- =====================================================
                             CUSTOMER
                        ====================================================== --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">

                                Customer
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="hidden"
                                name="customer_id"
                                id="customer_id"
                                value="{{ old('customer_id', $reservation->customer_id) }}">


                            <div class="position-relative">

                                <div class="input-group">

                                    <span class="input-group-text bg-white">

                                        <i class="bi bi-person"></i>

                                    </span>

                                    <input
                                        type="text"
                                        id="customer_search"
                                        class="form-control"
                                        value="{{ old(
                                            'customer_search',
                                            $reservation->customer
                                                ? $reservation->customer->name . ' - ' . $reservation->customer->phone
                                                : ''
                                        ) }}"
                                        placeholder="Search customer by name or mobile number"
                                        autocomplete="off">

                                </div>


                                {{-- Search Results --}}

                                <div
                                    id="customer_results"
                                    class="list-group position-absolute w-100 shadow-sm"
                                    style="
                                        display:none;
                                        z-index:1050;
                                        max-height:300px;
                                        overflow-y:auto;
                                    ">
                                </div>

                            </div>


                            <div class="form-text">

                                Search using customer name or mobile number.

                            </div>

                        </div>


                        {{-- =====================================================
                             CUSTOMER DETAILS
                        ====================================================== --}}

                        <div
                            id="customer_details"
                            class="row g-3 mt-1">


                            {{-- Customer Name --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Customer Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="customer_name"
                                    id="customer_name"
                                    value="{{ old(
                                        'customer_name',
                                        $reservation->customer?->name
                                    ) }}"
                                    class="form-control"
                                    placeholder="Customer name"
                                    required>

                            </div>


                            {{-- Phone --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Mobile Number
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    id="phone"
                                    value="{{ old(
                                        'phone',
                                        $reservation->customer?->phone
                                    ) }}"
                                    class="form-control"
                                    placeholder="Mobile number"
                                    required>

                            </div>


                            {{-- Email --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="{{ old(
                                        'email',
                                        $reservation->customer?->email
                                    ) }}"
                                    class="form-control"
                                    placeholder="customer@example.com">

                            </div>

                        </div>


                        <div class="col-12">

                            <hr class="my-2">

                        </div>


                        {{-- =====================================================
                             RESERVATION DATE
                        ====================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Reservation Date
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="date"
                                name="reservation_date"
                                value="{{ old(
                                    'reservation_date',
                                    $reservation->reservation_date
                                        ? $reservation->reservation_date->format('Y-m-d')
                                        : ''
                                ) }}"
                                class="form-control"
                                required>

                        </div>


                        {{-- =====================================================
                             RESERVATION TIME
                        ====================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Reservation Time
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="time"
                                name="reservation_time"
                                value="{{ old(
                                    'reservation_time',
                                    \Carbon\Carbon::parse(
                                        $reservation->reservation_time
                                    )->format('H:i')
                                ) }}"
                                class="form-control"
                                required>

                        </div>


                        {{-- =====================================================
                             GUESTS
                        ====================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Number of Guests
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-white">

                                    <i class="bi bi-people"></i>

                                </span>

                                <input
                                    type="number"
                                    name="guests"
                                    value="{{ old(
                                        'guests',
                                        $reservation->guests
                                    ) }}"
                                    class="form-control"
                                    min="1"
                                    max="100"
                                    required>

                            </div>

                        </div>


                        {{-- =====================================================
                             DURATION
                        ====================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Reservation Duration

                            </label>

                            <select
                                name="duration_minutes"
                                class="form-select">

                                <option
                                    value="60"
                                    @selected(
                                        old(
                                            'duration_minutes',
                                            $reservation->duration_minutes
                                        ) == 60
                                    )>

                                    1 Hour

                                </option>

                                <option
                                    value="90"
                                    @selected(
                                        old(
                                            'duration_minutes',
                                            $reservation->duration_minutes
                                        ) == 90
                                    )>

                                    1.5 Hours

                                </option>

                                <option
                                    value="120"
                                    @selected(
                                        old(
                                            'duration_minutes',
                                            $reservation->duration_minutes
                                        ) == 120
                                    )>

                                    2 Hours

                                </option>

                                <option
                                    value="180"
                                    @selected(
                                        old(
                                            'duration_minutes',
                                            $reservation->duration_minutes
                                        ) == 180
                                    )>

                                    3 Hours

                                </option>

                                <option
                                    value="240"
                                    @selected(
                                        old(
                                            'duration_minutes',
                                            $reservation->duration_minutes
                                        ) == 240
                                    )>

                                    4 Hours

                                </option>

                            </select>

                        </div>


                        {{-- =====================================================
                             TABLE
                        ====================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Restaurant Table

                            </label>

                            <select
                                name="restaurant_table_id"
                                class="form-select">

                                <option value="">
                                    No Table Assigned
                                </option>

                                @foreach($tables as $table)

                                    <option
                                        value="{{ $table->id }}"
                                        @selected(
                                            old(
                                                'restaurant_table_id',
                                                $reservation->restaurant_table_id
                                            ) == $table->id
                                        )>

                                        {{ $table->table_number }}

                                        @if($table->name)

                                            - {{ $table->name }}

                                        @endif

                                        ({{ $table->capacity }} Seats)

                                    </option>

                                @endforeach

                            </select>


                            @error('restaurant_table_id')

                                <div class="text-danger small mt-1">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- =====================================================
                             STATUS
                        ====================================================== --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">

                                Reservation Status

                            </label>

                            <select
                                name="status"
                                class="form-select">

                                <option
                                    value="pending"
                                    @selected(
                                        old(
                                            'status',
                                            $reservation->status
                                        ) === 'pending'
                                    )>

                                    Pending

                                </option>

                                <option
                                    value="confirmed"
                                    @selected(
                                        old(
                                            'status',
                                            $reservation->status
                                        ) === 'confirmed'
                                    )>

                                    Confirmed

                                </option>

                                <option
                                    value="seated"
                                    @selected(
                                        old(
                                            'status',
                                            $reservation->status
                                        ) === 'seated'
                                    )>

                                    Seated

                                </option>

                                <option
                                    value="completed"
                                    @selected(
                                        old(
                                            'status',
                                            $reservation->status
                                        ) === 'completed'
                                    )>

                                    Completed

                                </option>

                                <option
                                    value="cancelled"
                                    @selected(
                                        old(
                                            'status',
                                            $reservation->status
                                        ) === 'cancelled'
                                    )>

                                    Cancelled

                                </option>

                                <option
                                    value="no_show"
                                    @selected(
                                        old(
                                            'status',
                                            $reservation->status
                                        ) === 'no_show'
                                    )>

                                    No Show

                                </option>

                            </select>

                        </div>


                        {{-- =====================================================
                             SPECIAL REQUEST
                        ====================================================== --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">

                                Special Request

                            </label>

                            <textarea
                                name="special_request"
                                rows="3"
                                class="form-control"
                                placeholder="Birthday, anniversary, Jain food, window seat, etc.">{{ old(
                                    'special_request',
                                    $reservation->special_request
                                ) }}</textarea>

                        </div>


                        {{-- =====================================================
                             INTERNAL NOTES
                        ====================================================== --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">

                                Internal Notes

                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="form-control"
                                placeholder="Internal staff notes">{{ old(
                                    'notes',
                                    $reservation->notes
                                ) }}</textarea>

                        </div>

                    </div>


                    {{-- =====================================================
                         BUTTONS
                    ====================================================== --}}

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">

                        <a
                            href="{{ route('admin.reservations.index') }}"
                            class="btn btn-light">

                            <i class="bi bi-arrow-left me-2"></i>

                            Back

                        </a>


                        <button
                            type="submit"
                            class="btn btn-danger px-4">

                            <i class="bi bi-check-lg me-2"></i>

                            Update Reservation

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('customer_search');

    const resultsBox =
        document.getElementById('customer_results');

    const customerId =
        document.getElementById('customer_id');

    const customerName =
        document.getElementById('customer_name');

    const phone =
        document.getElementById('phone');

    const email =
        document.getElementById('email');


    let searchTimer = null;


    /*
    |--------------------------------------------------------------------------
    | Customer Search
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener('input', function () {

        const search =
            this.value.trim();


        clearTimeout(searchTimer);


        /*
        |--------------------------------------------------------------------------
        | If user changes the search value, don't automatically assume
        | the old customer is still selected.
        |--------------------------------------------------------------------------
        */

        customerId.value = '';


        if (search.length < 2) {

            resultsBox.innerHTML = '';

            resultsBox.style.display = 'none';

            return;
        }


        searchTimer = setTimeout(function () {

            fetch(
                `{{ route('admin.customers.search') }}?search=${encodeURIComponent(search)}`,
                {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }
            )
            .then(function (response) {

                if (!response.ok) {

                    throw new Error(
                        'Customer search failed.'
                    );

                }

                return response.json();

            })
            .then(function (customers) {

                resultsBox.innerHTML = '';


                if (!customers.length) {

                    resultsBox.innerHTML = `

                        <div class="list-group-item">

                            <div class="text-muted mb-2">

                                <i class="bi bi-person-x me-1"></i>

                                No customer found.

                            </div>

                            <button
                                type="button"
                                class="btn btn-sm btn-danger"
                                id="create_customer_btn">

                                <i class="bi bi-person-plus me-1"></i>

                                Use New Customer

                            </button>

                        </div>

                    `;


                    resultsBox.style.display =
                        'block';


                    document
                        .getElementById('create_customer_btn')
                        .addEventListener(
                            'click',
                            function () {

                                customerId.value = '';

                                phone.value = search;

                                customerName.focus();

                                resultsBox.style.display =
                                    'none';

                            }
                        );

                    return;
                }


                customers.forEach(function (customer) {

                    const item =
                        document.createElement('button');


                    item.type = 'button';

                    item.className =
                        'list-group-item list-group-item-action';


                    item.innerHTML = `

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <div class="fw-semibold">

                                    ${escapeHtml(customer.name)}

                                </div>

                                <small class="text-muted">

                                    ${escapeHtml(customer.phone)}

                                    ${
                                        customer.email
                                            ? ' • ' + escapeHtml(customer.email)
                                            : ''
                                    }

                                </small>

                            </div>

                            <i class="bi bi-chevron-right text-muted"></i>

                        </div>

                    `;


                    item.addEventListener(
                        'click',
                        function () {

                            selectCustomer(
                                customer
                            );

                        }
                    );


                    resultsBox.appendChild(item);

                });


                resultsBox.style.display =
                    'block';

            })
            .catch(function (error) {

                console.error(error);

                resultsBox.innerHTML = `

                    <div class="list-group-item text-danger">

                        Unable to search customers.

                    </div>

                `;

                resultsBox.style.display =
                    'block';

            });

        }, 300);

    });


    /*
    |--------------------------------------------------------------------------
    | Select Existing Customer
    |--------------------------------------------------------------------------
    */

    function selectCustomer(customer)
    {
        customerId.value =
            customer.id;


        searchInput.value =
            `${customer.name} - ${customer.phone}`;


        customerName.value =
            customer.name;


        phone.value =
            customer.phone;


        email.value =
            customer.email ?? '';


        resultsBox.innerHTML = '';

        resultsBox.style.display =
            'none';
    }


    /*
    |--------------------------------------------------------------------------
    | Close Search Dropdown
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !searchInput.contains(event.target)
                &&
                !resultsBox.contains(event.target)
            ) {

                resultsBox.style.display =
                    'none';

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Basic HTML escaping
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value)
    {
        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

});

</script>

@endpush