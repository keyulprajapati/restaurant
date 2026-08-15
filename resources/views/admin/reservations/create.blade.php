@extends('layouts.admin')

@section('title', 'New Reservation')

@section('page-title', 'New Reservation')

@section('content')

    <div class="row justify-content-center">

        <div class="col-xl-9">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-lg-5">

                    <div class="mb-4">

                        <h4 class="fw-bold mb-1">
                            New Reservation
                        </h4>

                        <p class="text-muted mb-0">
                            Create a restaurant table reservation.
                        </p>

                    </div>


                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form method="POST" action="{{ route('admin.reservations.store') }}">

                        @csrf


                        <div class="row g-3">


                            {{-- Customer --}}

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Customer
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') }}">

                                <input type="text" id="customer_search" class="form-control"
                                    placeholder="Search by customer name or mobile number" autocomplete="off">

                                <div id="customer_results" class="list-group mt-2" style="display:none;">
                                </div>

                            </div>


                            <div id="new_customer_fields" class="row g-3" style="display:none;">

                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Customer Name
                                    </label>

                                    <input type="text" name="customer_name" id="customer_name" class="form-control"
                                        placeholder="Customer name">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Mobile Number
                                    </label>

                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Mobile number">

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Email
                                    </label>

                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">

                                </div>

                            </div>


                            {{-- Guests --}}

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">

                                    Guests
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="number" name="guests" value="{{ old('guests', 2) }}" min="1" max="100"
                                    class="form-control" required>

                            </div>


                            {{-- Duration --}}

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Duration
                                </label>

                                <select name="duration_minutes" class="form-select">

                                    <option value="60" @selected(old('duration_minutes') == 60)>

                                        1 Hour

                                    </option>

                                    <option value="90" @selected(old('duration_minutes', 90) == 90)>

                                        1.5 Hours

                                    </option>

                                    <option value="120" @selected(old('duration_minutes') == 120)>

                                        2 Hours

                                    </option>

                                    <option value="180" @selected(old('duration_minutes') == 180)>

                                        3 Hours

                                    </option>

                                </select>

                            </div>


                            {{-- Date --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Reservation Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="date" name="reservation_date"
                                    value="{{ old('reservation_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                                    class="form-control" required>

                            </div>


                            {{-- Time --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Reservation Time
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="time" name="reservation_time" value="{{ old('reservation_time') }}"
                                    class="form-control" required>

                            </div>


                            {{-- Table --}}

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Table

                                </label>

                                <select name="restaurant_table_id" class="form-select">

                                    <option value="">
                                        Any Available Table
                                    </option>

                                    @foreach($tables as $table)

                                        <option value="{{ $table->id }}" @selected(old('restaurant_table_id') == $table->id)>

                                            {{ $table->table_number }}

                                            @if($table->name)
                                                - {{ $table->name }}
                                            @endif

                                            ({{ $table->capacity }} Seats)

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">
                                    Leave blank if table will be assigned later.
                                </small>

                            </div>


                            {{-- Status --}}

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Reservation Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="pending">
                                        Pending
                                    </option>

                                    <option value="confirmed">
                                        Confirmed
                                    </option>

                                </select>

                            </div>


                            {{-- Special Request --}}

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Special Request
                                </label>

                                <textarea name="special_request" rows="3" class="form-control"
                                    placeholder="Birthday, anniversary, Jain food, window seat, etc.">{{ old('special_request') }}</textarea>

                            </div>


                            {{-- Notes --}}

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Internal Notes
                                </label>

                                <textarea name="notes" rows="2" class="form-control"
                                    placeholder="Internal staff notes">{{ old('notes') }}</textarea>

                            </div>

                        </div>


                        <div class="d-flex justify-content-between mt-4">

                            <a href="{{ route('admin.reservations.index') }}" class="btn btn-light">

                                <i class="bi bi-arrow-left me-2"></i>

                                Back

                            </a>


                            <button type="submit" class="btn btn-danger">

                                <i class="bi bi-calendar-check me-2"></i>

                                Create Reservation

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

        let customerSearchTimer = null;

        const searchInput = document.getElementById(
            'customer_search'
        );

        const resultsBox = document.getElementById(
            'customer_results'
        );

        const customerId = document.getElementById(
            'customer_id'
        );

        const newCustomerFields = document.getElementById(
            'new_customer_fields'
        );


        searchInput.addEventListener('input', function () {

            const search = this.value.trim();

            clearTimeout(customerSearchTimer);

            customerId.value = '';

            if (search.length < 2) {

                resultsBox.style.display = 'none';

                newCustomerFields.style.display = 'none';

                return;
            }


            customerSearchTimer = setTimeout(() => {

                fetch(
                    `{{ route('admin.customers.search') }}?search=${encodeURIComponent(search)}`
                )

                    .then(response => response.json())

                    .then(customers => {

                        resultsBox.innerHTML = '';

                        if (!customers.length) {

                            resultsBox.innerHTML = `
                        <div class="list-group-item">

                            <div class="text-muted mb-2">
                                Customer not found
                            </div>

                            <button
                                type="button"
                                class="btn btn-sm btn-danger"
                                onclick="createNewCustomer()">

                                <i class="bi bi-plus-lg me-1"></i>

                                Create New Customer

                            </button>

                        </div>
                    `;

                            resultsBox.style.display = 'block';

                            return;
                        }


                        customers.forEach(customer => {

                            const item =
                                document.createElement('button');

                            item.type = 'button';

                            item.className =
                                'list-group-item list-group-item-action';

                            item.innerHTML = `

                        <div class="fw-semibold">
                            ${customer.name}
                        </div>

                        <small class="text-muted">
                            ${customer.phone}
                            ${customer.email
                                    ? ' • ' + customer.email
                                    : ''}
                        </small>

                    `;

                            item.addEventListener(
                                'click',
                                () => selectCustomer(customer)
                            );

                            resultsBox.appendChild(item);

                        });


                        resultsBox.style.display = 'block';

                    });

            }, 300);

        });


        function selectCustomer(customer) {
            customerId.value = customer.id;

            searchInput.value =
                `${customer.name} - ${customer.phone}`;

            document.getElementById('customer_name')
                .value = customer.name;

            document.getElementById('phone')
                .value = customer.phone;

            document.getElementById('email')
                .value = customer.email ?? '';

            newCustomerFields.style.display =
                'none';

            resultsBox.style.display =
                'none';
        }


        function createNewCustomer() {
            customerId.value = '';

            newCustomerFields.style.display =
                'flex';

            document.getElementById('phone')
                .value = searchInput.value;

            resultsBox.style.display =
                'none';

            document.getElementById('customer_name')
                .focus();
        }

    </script>

@endpush