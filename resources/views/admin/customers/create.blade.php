@extends('layouts.admin')

@section('title', 'Add Customer')

@section('page-title', 'Add Customer')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <div class="mb-4">

                    <h4 class="fw-bold">
                        Add Customer
                    </h4>

                    <p class="text-muted">
                        Create a new restaurant customer.
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


                <form
                    method="POST"
                    action="{{ route('admin.customers.store') }}">

                    @csrf

                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Customer Name
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control"
                                placeholder="e.g. John Patel"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Mobile Number
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="form-control"
                                placeholder="e.g. 9876543210"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                placeholder="customer@example.com">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old('date_of_birth') }}"
                                class="form-control">

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                class="form-control"
                                placeholder="Customer address">{{ old('address') }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="form-control"
                                placeholder="Additional customer notes">{{ old('notes') }}</textarea>

                        </div>


                        <div class="col-12">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    class="form-check-input"
                                    id="is_active"
                                    checked>

                                <label
                                    class="form-check-label"
                                    for="is_active">

                                    Active Customer

                                </label>

                            </div>

                        </div>

                    </div>


                    <div class="mt-4">

                        <a
                            href="{{ route('admin.customers.index') }}"
                            class="btn btn-light me-2">

                            Cancel

                        </a>

                        <button
                            type="submit"
                            class="btn btn-danger">

                            <i class="bi bi-check-lg me-2"></i>

                            Save Customer

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection