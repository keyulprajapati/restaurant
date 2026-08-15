@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('page-title', 'Edit Customer')

@section('content')

<div class="row justify-content-center">

    <div class="col-xl-8">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4 p-lg-5">

                <div class="mb-4">

                    <h4 class="fw-bold">
                        Edit Customer
                    </h4>

                    <p class="text-muted">
                        Update customer information.
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
                    action="{{ route(
                        'admin.customers.update',
                        $customer
                    ) }}">

                    @csrf

                    @method('PUT')


                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Customer Name
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old(
                                    'name',
                                    $customer->name
                                ) }}"
                                class="form-control"
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
                                value="{{ old(
                                    'phone',
                                    $customer->phone
                                ) }}"
                                class="form-control"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old(
                                    'email',
                                    $customer->email
                                ) }}"
                                class="form-control">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                value="{{ old(
                                    'date_of_birth',
                                    $customer->date_of_birth
                                        ? $customer->date_of_birth->format('Y-m-d')
                                        : ''
                                ) }}"
                                class="form-control">

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                class="form-control">{{ old(
                                    'address',
                                    $customer->address
                                ) }}</textarea>

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                                class="form-control">{{ old(
                                    'notes',
                                    $customer->notes
                                ) }}</textarea>

                        </div>


                        <div class="col-12">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="is_active"
                                    value="1"
                                    class="form-check-input"
                                    id="is_active"
                                    @checked(
                                        old(
                                            'is_active',
                                            $customer->is_active
                                        )
                                    )>

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

                            Update Customer

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection